<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index(JobListing $job = null)
    {
        $employerId = Auth::user()->employer->id;
        
        if ($job) {
            if ($job->employer_id !== $employerId) abort(403);
            $applications = Application::where('job_listing_id', $job->id)
                ->with('candidate.user', 'resume')
                ->paginate(10);
        } else {
            $applications = Application::whereHas('jobListing', function ($q) use ($employerId) {
                $q->where('employer_id', $employerId);
            })->with('jobListing', 'candidate.user', 'resume')->paginate(10);
        }

        $jobs = JobListing::where('employer_id', $employerId)->get();
        return view('employer.applications.index', compact('applications', 'jobs', 'job'));
    }

    public function show(Application $application)
    {
        if ($application->jobListing->employer_id !== Auth::user()->employer->id) abort(403);
        return view('employer.applications.show', compact('application'));
    }

    public function updateStatus(Request $request, Application $application)
    {
        if ($application->jobListing->employer_id !== Auth::user()->employer->id) abort(403);

        $validated = $request->validate([
            'status' => 'required|string|in:under_review,shortlisted,selected,rejected',
            'employer_notes' => 'nullable|string',
        ]);

        $application->update($validated);

        return redirect()->route('employer.applications.index')->with('success', 'Application status updated.');
    }

    public function scheduleInterview(Request $request, Application $application)
    {
        if ($application->jobListing->employer_id !== Auth::user()->employer->id) abort(403);

        $validated = $request->validate([
            'scheduled_date' => 'required|date|after:today',
            'scheduled_time' => 'required',
            'interview_type' => 'required|in:online,offline',
            'meeting_link' => 'nullable|url',
            'notes' => 'nullable|string',
        ]);

        $interview = $application->interview()->create([
            'employer_id' => Auth::user()->employer->id,
            'candidate_id' => $application->candidate_id,
            'scheduled_date' => $validated['scheduled_date'],
            'scheduled_time' => $validated['scheduled_time'],
            'interview_type' => $validated['interview_type'],
            'meeting_link' => $validated['meeting_link'],
            'notes' => $validated['notes'],
        ]);

        $application->update(['status' => 'interview_scheduled']);

        return redirect()->route('employer.applications.index')->with('success', 'Interview scheduled successfully.');
    }
}
