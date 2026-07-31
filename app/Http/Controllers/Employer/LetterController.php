<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Mail\LetterMail;
use App\Models\Application;
use App\Models\Letter;
use App\Models\Notification;
use App\Services\LetterPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LetterController extends Controller
{
    public function __construct(private LetterPdfService $pdfService)
    {
    }

    private function ensureOwnsLetter(Letter $letter): void
    {
        if ($letter->employer_id !== Auth::user()->employer->id) {
            abort(403);
        }
    }

    public function index(Request $request)
    {
        $employerId = Auth::user()->employer->id;

        $letters = Letter::with('candidate.user', 'jobListing', 'application')
            ->where('employer_id', $employerId)
            ->when($request->input('type'), function ($q, $type) {
                $q->where('letter_type', $type);
            })
            ->when($request->input('status'), function ($q, $status) {
                $q->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('employer.letters.index', compact('letters'));
    }

    public function create(Application $application, Request $request)
    {
        if ($application->jobListing->employer_id !== Auth::user()->employer->id) {
            abort(403);
        }

        if ($application->status !== 'selected') {
            return redirect()->route('employer.applications.show', $application)
                ->with('error', 'You can only generate letters for selected candidates.');
        }

        $type = in_array($request->query('type'), ['offer', 'joining']) ? $request->query('type') : 'offer';

        $existing = $application->letters()
            ->where('letter_type', $type)
            ->whereIn('status', ['draft', 'sent'])
            ->latest()
            ->first();

        if ($existing) {
            return redirect()->route('employer.letters.edit', $existing)
                ->with('info', 'A ' . $type . ' letter already exists for this application. You can update or send it.');
        }

        return view('employer.letters.create', compact('application', 'type'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        $application = Application::with('candidate.user', 'jobListing.employer.user')
            ->findOrFail($validated['application_id']);

        if ($application->jobListing->employer_id !== Auth::user()->employer->id) {
            abort(403);
        }

        if ($application->status !== 'selected') {
            return redirect()->route('employer.applications.show', $application)
                ->with('error', 'You can only generate letters for selected candidates.');
        }

        $action = $request->input('action', 'draft');

        $letter = Letter::create(array_merge($validated, [
            'employer_id' => Auth::user()->employer->id,
            'candidate_id' => $application->candidate_id,
            'job_listing_id' => $application->job_listing_id,
            'status' => 'draft',
        ]));

        if ($action === 'send') {
            $this->sendLetter($letter);

            return redirect()->route('employer.letters.show', $letter)
                ->with('success', ucfirst($letter->letter_type) . ' letter created and sent successfully.');
        }

        return redirect()->route('employer.letters.edit', $letter)
            ->with('success', ucfirst($letter->letter_type) . ' letter saved as draft.');
    }

    public function show(Letter $letter)
    {
        $this->ensureOwnsLetter($letter);
        $letter->load('application', 'candidate.user', 'jobListing', 'employer.user');

        return view('employer.letters.show', compact('letter'));
    }

    public function edit(Letter $letter)
    {
        $this->ensureOwnsLetter($letter);
        $letter->load('application', 'candidate.user', 'jobListing', 'employer.user');

        return view('employer.letters.edit', compact('letter'));
    }

    public function update(Request $request, Letter $letter)
    {
        $this->ensureOwnsLetter($letter);

        $validated = $this->validatedData($request);
        $action = $request->input('action', 'draft');

        $letter->update($validated);

        if ($action === 'send') {
            $this->sendLetter($letter);

            return redirect()->route('employer.letters.show', $letter)
                ->with('success', ucfirst($letter->letter_type) . ' letter updated and sent successfully.');
        }

        return redirect()->route('employer.letters.edit', $letter)
            ->with('success', ucfirst($letter->letter_type) . ' letter saved as draft.');
    }

    public function send(Letter $letter)
    {
        $this->ensureOwnsLetter($letter);

        $this->sendLetter($letter);

        return redirect()->route('employer.letters.show', $letter)
            ->with('success', ucfirst($letter->letter_type) . ' letter sent successfully.');
    }

    private function sendLetter(Letter $letter): void
    {
        $this->pdfService->generate($letter);
        $letter->update(['status' => 'sent', 'sent_at' => now()]);

        $candidateUser = $letter->candidate->user;
        $job = $letter->jobListing;

        Notification::create([
            'user_id' => $candidateUser->id,
            'type' => 'letter',
            'title' => ucfirst($letter->letter_type) . ' Letter Issued',
            'message' => 'Your ' . $letter->letter_type . ' letter for ' . $job->title . ' at ' . $letter->employer->company_name . ' has been issued.',
            'data' => [
                'letter_id' => $letter->id,
                'url' => route('candidate.letters.show', $letter),
            ],
        ]);

        Mail::to($candidateUser->email)->send(new LetterMail($letter));
    }

    public function pdf(Letter $letter)
    {
        $this->ensureOwnsLetter($letter);

        return $this->pdfService->download($letter);
    }

    public function regenerate(Letter $letter)
    {
        $this->ensureOwnsLetter($letter);

        $this->pdfService->generate($letter);

        return redirect()->route('employer.letters.show', $letter)
            ->with('success', ucfirst($letter->letter_type) . ' letter PDF regenerated successfully.');
    }

    public function destroy(Letter $letter)
    {
        $this->ensureOwnsLetter($letter);

        if ($letter->pdf_path) {
            \Illuminate\Support\Facades\Storage::disk('local')->delete($letter->pdf_path);
        }

        $letter->delete();

        return redirect()->route('employer.letters.index')
            ->with('success', ucfirst($letter->letter_type) . ' letter deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'application_id' => 'required|exists:applications,id',
            'letter_type' => 'required|in:offer,joining',
            'offer_date' => 'nullable|date',
            'joining_date' => 'nullable|date',
            'salary_ctc' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'work_location' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|max:255',
            'reporting_manager' => 'nullable|string|max:255',
            'hr_name' => 'nullable|string|max:255',
            'hr_email' => 'nullable|email|max:255',
            'hr_phone' => 'nullable|string|max:255',
            'terms' => 'nullable|string',
            'signature_name' => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ]);
    }
}
