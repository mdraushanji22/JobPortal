<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\SavedJob;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedJobController extends Controller
{
    public function index()
    {
        $candidate = Auth::user()->candidate;
        $savedJobs = SavedJob::where('candidate_id', $candidate->id)
            ->with('jobListing.employer')
            ->paginate(10);
        return view('candidate.saved-jobs.index', compact('savedJobs'));
    }

    public function toggle(JobListing $job)
    {
        $candidate = Auth::user()->candidate;
        
        $saved = SavedJob::where('candidate_id', $candidate->id)
            ->where('job_listing_id', $job->id)->first();

        if ($saved) {
            $saved->delete();
            return response()->json(['saved' => false]);
        }

        SavedJob::create([
            'candidate_id' => $candidate->id,
            'job_listing_id' => $job->id,
        ]);

        return response()->json(['saved' => true]);
    }

    public function remove(SavedJob $savedJob)
    {
        if ($savedJob->candidate_id !== Auth::user()->candidate->id) abort(403);
        $savedJob->delete();
        return redirect()->route('candidate.saved-jobs.index')->with('success', 'Job removed from saved.');
    }
}
