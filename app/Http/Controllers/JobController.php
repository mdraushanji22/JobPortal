<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\JobListing;
use App\Models\Application;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = JobListing::active()->with('employer', 'category');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('skills_required', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }

        if ($request->filled('workplace_type')) {
            $query->where('workplace_type', $request->workplace_type);
        }

        if ($request->filled('experience_level')) {
            $query->where('experience_level', $request->experience_level);
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('salary_min')) {
            $query->where('salary_min', '>=', $request->salary_min);
        }

        $sortField = $request->get('sort', 'created_at');
        $sortField = in_array($sortField, ['created_at', 'salary_min', 'salary_max', 'title'], true)
            ? $sortField
            : 'created_at';
        $sortDir = $request->get('direction', 'desc') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortField, $sortDir);

        $jobs = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('jobs.index', compact('jobs', 'categories'));
    }

    public function show(JobListing $job)
    {
        if (!$job->is_active || $job->status !== 'approved') {
            abort(404);
        }

        $hasApplied = false;
        $isSaved = false;

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isCandidate() && $user->candidate) {
                $candidate = $user->candidate;
                $hasApplied = Application::where('job_listing_id', $job->id)
                    ->where('candidate_id', $candidate->id)->exists();
                $isSaved = SavedJob::where('job_listing_id', $job->id)
                    ->where('candidate_id', $candidate->id)->exists();
            }
        }

        return view('jobs.show', compact('job', 'hasApplied', 'isSaved'));
    }
}
