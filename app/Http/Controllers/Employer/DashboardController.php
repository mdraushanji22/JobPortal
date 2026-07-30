<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $employer = Auth::user()->employer;
        $jobs = $employer->jobListings();
        
        $data = [
            'totalJobs' => $jobs->count(),
            'activeJobs' => $jobs->where('is_active', true)->where('status', 'approved')->count(),
            'closedJobs' => $jobs->where('status', 'closed')->count(),
            'totalApplications' => $jobs->withCount('applications')->get()->sum('applications_count'),
            'shortlistedCandidates' => $jobs->withCount(['applications' => function ($q) {
                $q->where('status', 'shortlisted');
            }])->get()->sum('applications_count'),
        ];
        
        return view('employer.dashboard', $data);
    }
}
