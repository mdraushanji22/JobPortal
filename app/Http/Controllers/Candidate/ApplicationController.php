<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobListing;
use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index()
    {
        $candidate = Auth::user()->candidate;
        $applications = Application::where('candidate_id', $candidate->id)
            ->with('jobListing.employer', 'resume', 'interview')
            ->paginate(10);
        return view('candidate.applications.index', compact('applications'));
    }

    public function create(JobListing $job)
    {
        $this->ensureJobIsOpen($job);

        $candidate = Auth::user()->candidate;
        $resumes = Resume::where('candidate_id', $candidate->id)->get();
        
        $existingApplication = Application::where('job_listing_id', $job->id)
            ->where('candidate_id', $candidate->id)->first();
            
        if ($existingApplication) {
            return redirect()->route('candidate.jobs.show', $job)
                ->with('error', 'You have already applied for this job.');
        }

        return view('candidate.applications.create', compact('job', 'resumes'));
    }

    public function store(Request $request, JobListing $job)
    {
        $this->ensureJobIsOpen($job);

        $candidate = Auth::user()->candidate;

        $validated = $request->validate([
            'cover_letter' => 'nullable|string',
            'resume_id' => 'nullable|exists:resumes,id',
        ]);

        if (isset($validated['resume_id']) && !Resume::whereKey($validated['resume_id'])
            ->where('candidate_id', $candidate->id)
            ->exists()) {
            abort(403);
        }

        $existingApplication = Application::where('job_listing_id', $job->id)
            ->where('candidate_id', $candidate->id)->first();

        if ($existingApplication) {
            return redirect()->route('candidate.jobs.show', $job)
                ->with('error', 'You have already applied for this job.');
        }

        Application::create([
            'job_listing_id' => $job->id,
            'candidate_id' => $candidate->id,
            'resume_id' => $validated['resume_id'] ?? null,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'status' => 'applied',
            'applied_at' => now(),
        ]);

        return redirect()->route('candidate.applications.index')
            ->with('success', 'Application submitted successfully.');
    }

    private function ensureJobIsOpen(JobListing $job): void
    {
        if (!$job->is_active || $job->status !== 'approved' || ($job->application_deadline && $job->application_deadline->isPast())) {
            abort(404);
        }
    }

    public function withdraw(Application $application)
    {
        if ($application->candidate_id !== Auth::user()->candidate->id) abort(403);
        
        if (in_array($application->status, ['applied', 'under_review'])) {
            $application->delete();
            return redirect()->route('candidate.applications.index')
                ->with('success', 'Application withdrawn successfully.');
        }

        return redirect()->route('candidate.applications.index')
            ->with('error', 'Cannot withdraw application at current status.');
    }
}
