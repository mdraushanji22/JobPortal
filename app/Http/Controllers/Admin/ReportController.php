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
        $monthlyJobs = JobListing::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month');

        $monthlyApplications = Application::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month');

        $employers = Employer::with('user')->paginate(10);
        $candidates = Candidate::with('user')->paginate(10);

        return view('admin.reports.index', compact('monthlyJobs', 'monthlyApplications', 'employers', 'candidates'));
    }
}
