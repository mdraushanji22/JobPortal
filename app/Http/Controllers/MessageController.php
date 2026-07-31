<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    protected const ALLOWED_EXTENSIONS = [
        'jpg', 'jpeg', 'png', 'webp',
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'zip',
    ];

    protected const BLOCKED_EXTENSIONS = [
        'exe', 'bat', 'php', 'php3', 'php4', 'phtml', 'phar',
        'js', 'sh', 'cmd', 'com', 'msi', 'dll', 'so', 'bin',
        'ps1', 'vbs', 'jar', 'scr', 'hta', 'msc', 'cpl', 'reg',
        'py', 'pl', 'rb', 'cgi',
    ];
    public function index()
    {
        $user = Auth::user();

        $conversations = Conversation::whereHas('application', function ($q) use ($user) {
            if ($user->isEmployer()) {
                $q->whereHas('jobListing', function ($q) use ($user) {
                    $q->where('employer_id', $user->employer->id);
                });
            } elseif ($user->isCandidate()) {
                $q->where('candidate_id', $user->candidate->id);
            } else {
                $q->whereRaw('1 = 0');
            }
        })
            ->with(['application.candidate.user', 'application.jobListing.employer.user'])
            ->with('lastMessage')
            ->withCount(['messages as unread_count' => function ($q) use ($user) {
                $q->where('receiver_id', $user->id)->where('is_read', false);
            }])
            ->orderByDesc('updated_at')
            ->get();

        return view('messages.index', compact('conversations'));
    }

    public function open(Application $application)
    {
        $user = Auth::user();

        $isEmployer = $user->isEmployer()
            && $application->jobListing->employer_id === $user->employer->id;
        $isCandidate = $user->isCandidate()
            && $application->candidate_id === $user->candidate->id;

        if (!$isEmployer && !$isCandidate) {
            abort(403, 'Unauthorized access to this conversation.');
        }

        $conversation = $application->conversation()->firstOrCreate([
            'application_id' => $application->id,
        ]);

        return redirect()->route('messages.index', ['conversation' => $conversation->id]);
    }

    public function conversation(Conversation $conversation)
    {
        $user = Auth::user();

        if (!$conversation->isParticipant($user)) {
            abort(403, 'Unauthorized access to this conversation.');
        }

        $conversation->messages()
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'conversation' => [
                'id' => $conversation->id,
                'application_id' => $conversation->application_id,
                'job_title' => $conversation->jobTitle(),
                'other_user' => $conversation->otherUserFor($user),
            ],
            'messages' => $messages,
        ]);
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required_without:file|nullable|string|max:10000',
            'file' => 'nullable|file|max:10240',
        ], [
            'file.max' => 'The file must not exceed 10 MB.',
            'message.max' => 'The message must not exceed 10000 characters.',
        ]);

        $conversation = Conversation::with(['application.candidate.user', 'application.jobListing.employer.user'])
            ->findOrFail($validated['conversation_id']);

        $user = Auth::user();
        $application = $conversation->application;

        $isEmployer = $user->isEmployer()
            && $application->jobListing->employer_id === $user->employer->id;
        $isCandidate = $user->isCandidate()
            && $application->candidate_id === $user->candidate->id;

        if (!$isEmployer && !$isCandidate) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        $receiverId = $isEmployer
            ? $application->candidate->user_id
            : $application->jobListing->employer->user_id;

        $messageData = [
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message_type' => 'text',
            'message' => $validated['message'] ?? null,
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());
            $mime = $file->getMimeType();

            if (in_array($extension, self::BLOCKED_EXTENSIONS, true) || !in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
                return response()->json([
                    'error' => 'Unsupported file type. Allowed: JPG, JPEG, PNG, WEBP, PDF, DOC, DOCX, XLS, XLSX, TXT, ZIP.',
                ], 422);
            }

            $allowedMimes = [
                'image/jpeg', 'image/png', 'image/webp',
                'application/pdf', 'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'text/plain',
                'application/zip', 'application/x-zip-compressed', 'application/octet-stream',
            ];

            if (!in_array($mime, $allowedMimes, true)) {
                return response()->json([
                    'error' => 'The file content does not match an allowed type.',
                ], 422);
            }

            $messageData['message_type'] = str_starts_with($mime, 'image/') ? 'image' : 'file';
            $messageData['file_path'] = $file->store('chat-files', 'local');
            $messageData['file_type'] = $extension;
            $messageData['original_file_name'] = $file->getClientOriginalName();
            $messageData['file_size'] = $file->getSize();
            $messageData['mime_type'] = $mime;
        }

        if ($messageData['message_type'] === 'text') {
            $duplicate = $conversation->messages()
                ->where('sender_id', $user->id)
                ->where('receiver_id', $receiverId)
                ->where('message', $validated['message'] ?? null)
                ->where('created_at', '>', now()->subSeconds(5))
                ->exists();

            if ($duplicate) {
                return response()->json(['message' => 'Message already sent.']);
            }
        }

        $message = Message::create($messageData);

        return response()->json($message->load('sender'));
    }

    public function preview(Message $message)
    {
        $this->authorizeAttachment($message);

        return Storage::disk('local')->response(
            $message->file_path,
            $message->original_file_name,
            [],
            'inline'
        );
    }

    public function download(Message $message)
    {
        $this->authorizeAttachment($message);

        return Storage::disk('local')->response(
            $message->file_path,
            $message->original_file_name,
            [],
            'attachment'
        );
    }

    private function authorizeAttachment(Message $message): void
    {
        if (!$message->file_path || !$message->conversation) {
            abort(404);
        }

        if (!Storage::disk('local')->exists($message->file_path)) {
            abort(404);
        }

        if (!$message->conversation->isParticipant(Auth::user())) {
            abort(403, 'Unauthorized access to this attachment.');
        }
    }
}
