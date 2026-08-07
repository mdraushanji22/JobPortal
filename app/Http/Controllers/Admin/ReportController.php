<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Employer;
use App\Models\Candidate;
use App\Models\JobListing;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $months = range(1, 12);

        $monthlyJobs = JobListing::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyApplications = Application::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyJobs = array_replace(array_fill_keys($months, 0), $monthlyJobs);
        $monthlyApplications = array_replace(array_fill_keys($months, 0), $monthlyApplications);

        $employers = Employer::with('user')->withCount('jobListings')->orderBy('company_name')->get();
        $candidates = Candidate::with('user')->withCount('applications')->orderBy('user_id')->get();

        return view('admin.reports.index', compact('monthlyJobs', 'monthlyApplications', 'employers', 'candidates'));
    }
}
