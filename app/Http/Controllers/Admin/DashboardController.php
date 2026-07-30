<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employer;
use App\Models\Candidate;
use App\Models\JobListing;
use App\Models\Application;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalEmployers' => Employer::count(),
            'totalCandidates' => Candidate::count(),
            'totalJobs' => JobListing::count(),
            'totalApplications' => Application::count(),
            'activeJobs' => JobListing::where('is_active', true)->where('status', 'approved')->count(),
            'pendingJobs' => JobListing::where('status', 'pending')->count(),
            'todayApplications' => Application::whereDate('created_at', today())->count(),
        ];
        return view('admin.dashboard', $data);
    }
}
