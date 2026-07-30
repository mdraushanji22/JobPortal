<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredJobs = JobListing::active()->featured()->with('employer', 'category')->latest()->take(6)->get();
        $categories = Category::withCount('jobListings')->get();
        $stats = [
            'totalJobs' => JobListing::active()->count(),
            'totalEmployers' => \App\Models\Employer::count(),
            'totalCandidates' => \App\Models\Candidate::count(),
        ];
        return view('home', compact('featuredJobs', 'categories', 'stats'));
    }
}
