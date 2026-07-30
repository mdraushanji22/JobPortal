<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobListing;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        $jobs = JobListing::with('employer', 'category')->paginate(10);
        return view('admin.jobs.index', compact('jobs'));
    }

    public function edit(JobListing $job)
    {
        return view('admin.jobs.edit', compact('job'));
    }

    public function update(Request $request, JobListing $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'is_featured' => 'boolean',
        ]);

        $job->update($validated);

        return redirect()->route('admin.jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(JobListing $job)
    {
        $job->delete();
        return redirect()->route('admin.jobs.index')->with('success', 'Job deleted successfully.');
    }

    public function approve(JobListing $job)
    {
        $job->update(['status' => 'approved', 'is_active' => true]);
        return redirect()->route('admin.jobs.index')->with('success', 'Job approved successfully.');
    }

    public function reject(Request $request, JobListing $job)
    {
        $job->update(['status' => 'rejected', 'is_active' => false]);
        return redirect()->route('admin.jobs.index')->with('success', 'Job rejected successfully.');
    }

    public function featured(JobListing $job)
    {
        $job->update(['is_featured' => !$job->is_featured]);
        return redirect()->route('admin.jobs.index')->with('success', 'Job featured status updated.');
    }
}
