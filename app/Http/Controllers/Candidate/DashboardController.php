<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\SavedJob;
use App\Models\Interview;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $candidate = Auth::user()->candidate;
        
        $data = [
            'appliedJobs' => Application::where('candidate_id', $candidate->id)->count(),
            'savedJobs' => SavedJob::where('candidate_id', $candidate->id)->count(),
            'interviews' => Interview::where('candidate_id', $candidate->id)->count(),
            'unreadNotifications' => Auth::user()->notifications()->where('is_read', false)->count(),
            'notifications' => Auth::user()->notifications()->latest()->take(5)->get(),
        ];
        
        return view('candidate.dashboard', $data);
    }
}
