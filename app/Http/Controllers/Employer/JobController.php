<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\JobListing;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index()
    {
        $employer = Auth::user()->employer;
        $jobs = JobListing::where('employer_id', $employer->id)->with('category', 'skills')->paginate(10);
        return view('employer.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $categories = Category::all();
        $skills = Skill::all();
        return view('employer.jobs.create', compact('categories', 'skills'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'required|string',
            'responsibilities' => 'nullable|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'skills_required' => 'nullable|string',
            'experience_level' => 'nullable|string|max:100',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'salary_type' => 'required|string|in:hourly,monthly,yearly',
            'employment_type' => 'required|string|in:full-time,part-time,contract,internship,freelance',
            'location' => 'nullable|string|max:255',
            'workplace_type' => 'required|string|in:on-site,remote,hybrid',
            'application_deadline' => 'nullable|date',
            'vacancy_count' => 'nullable|integer|min:1',
        ]);

        $validated['employer_id'] = Auth::user()->employer->id;
        $validated['status'] = 'pending';

        $job = JobListing::create($validated);

        if ($request->has('skills')) {
            $job->skills()->attach($request->skills);
        }

        return redirect()->route('employer.jobs.index')->with('success', 'Job posted successfully.');
    }

    public function edit(JobListing $job)
    {
        if ($job->employer_id !== Auth::user()->employer->id) {
            abort(403);
        }
        $categories = Category::all();
        $skills = Skill::all();
        return view('employer.jobs.edit', compact('job', 'categories', 'skills'));
    }

    public function update(Request $request, JobListing $job)
    {
        if ($job->employer_id !== Auth::user()->employer->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'required|string',
            'responsibilities' => 'nullable|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'skills_required' => 'nullable|string',
            'experience_level' => 'nullable|string|max:100',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'salary_type' => 'required|string',
            'employment_type' => 'required|string',
            'location' => 'nullable|string|max:255',
            'workplace_type' => 'required|string',
            'application_deadline' => 'nullable|date',
            'vacancy_count' => 'nullable|integer|min:1',
        ]);

        $job->update($validated);

        if ($request->has('skills')) {
            $job->skills()->sync($request->skills);
        }

        return redirect()->route('employer.jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(JobListing $job)
    {
        if ($job->employer_id !== Auth::user()->employer->id) {
            abort(403);
        }
        $job->delete();
        return redirect()->route('employer.jobs.index')->with('success', 'Job deleted successfully.');
    }

    public function close(JobListing $job)
    {
        if ($job->employer_id !== Auth::user()->employer->id) abort(403);
        $job->update(['status' => 'closed', 'is_active' => false]);
        return redirect()->route('employer.jobs.index')->with('success', 'Job closed successfully.');
    }

    public function reopen(JobListing $job)
    {
        if ($job->employer_id !== Auth::user()->employer->id) abort(403);
        $job->update(['status' => 'approved', 'is_active' => true]);
        return redirect()->route('employer.jobs.index')->with('success', 'Job reopened successfully.');
    }
}
