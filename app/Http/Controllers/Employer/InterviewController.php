<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewController extends Controller
{
    public function index()
    {
        $interviews = Interview::where('employer_id', Auth::user()->employer->id)
            ->with('candidate.user', 'application.jobListing')
            ->paginate(10);
        return view('employer.interviews.index', compact('interviews'));
    }

    public function updateStatus(Request $request, Interview $interview)
    {
        if ($interview->employer_id !== Auth::user()->employer->id) abort(403);

        $validated = $request->validate([
            'status' => 'required|in:completed,cancelled,rescheduled',
        ]);

        $interview->update($validated);

        if ($validated['status'] === 'completed') {
            $interview->application->update(['status' => 'selected']);
        }

        return redirect()->route('employer.interviews.index')->with('success', 'Interview status updated.');
    }
}
