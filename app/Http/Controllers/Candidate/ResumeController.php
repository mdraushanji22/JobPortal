<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResumeController extends Controller
{
    public function index()
    {
        $candidate = Auth::user()->candidate;
        $resumes = Resume::where('candidate_id', $candidate->id)->get();
        return view('candidate.resumes.index', compact('resumes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $candidate = Auth::user()->candidate;

        $filePath = $request->file('resume')->store('resumes', 'public');

        $isDefault = Resume::where('candidate_id', $candidate->id)->count() === 0;

        Resume::create([
            'candidate_id' => $candidate->id,
            'title' => $validated['title'],
            'file_path' => $filePath,
            'file_type' => $request->file('resume')->getClientOriginalExtension(),
            'is_default' => $isDefault,
        ]);

        return redirect()->route('candidate.resumes.index')->with('success', 'Resume uploaded successfully.');
    }

    public function setDefault(Resume $resume)
    {
        $candidate = Auth::user()->candidate;
        if (!$candidate || $resume->candidate_id !== $candidate->id) abort(403);

        Resume::where('candidate_id', $resume->candidate_id)->update(['is_default' => false]);
        $resume->update(['is_default' => true]);

        return redirect()->route('candidate.resumes.index')->with('success', 'Default resume set.');
    }

    public function destroy(Resume $resume)
    {
        $candidate = Auth::user()->candidate;
        if (!$candidate || $resume->candidate_id !== $candidate->id) abort(403);

        Storage::disk('public')->delete($resume->file_path);
        $resume->delete();

        return redirect()->route('candidate.resumes.index')->with('success', 'Resume deleted successfully.');
    }

    public function download(Resume $resume)
    {
        $user = Auth::user();
        $isOwner = $user->isCandidate() && $user->candidate && $resume->candidate_id === $user->candidate->id;

        if (!$isOwner && !$user->isEmployer() && !$user->isAdmin()) {
            abort(403);
        }

        return Storage::disk('public')->download($resume->file_path, $resume->title . '.' . $resume->file_type);
    }
}
