@extends('layouts.employer')

@section('title', 'Employer Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full"><i class="fas fa-briefcase text-blue-600 dark:text-blue-300 text-2xl"></i></div>
            <div class="ml-4"><h3 class="text-gray-500 dark:text-gray-400 text-sm">Total Jobs</h3><p class="text-2xl font-bold">{{ $totalJobs }}</p></div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full"><i class="fas fa-check-circle text-green-600 dark:text-green-300 text-2xl"></i></div>
            <div class="ml-4"><h3 class="text-gray-500 dark:text-gray-400 text-sm">Active Jobs</h3><p class="text-2xl font-bold">{{ $activeJobs }}</p></div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full"><i class="fas fa-times-circle text-red-600 dark:text-red-300 text-2xl"></i></div>
            <div class="ml-4"><h3 class="text-gray-500 dark:text-gray-400 text-sm">Closed Jobs</h3><p class="text-2xl font-bold">{{ $closedJobs }}</p></div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full"><i class="fas fa-file-alt text-yellow-600 dark:text-yellow-300 text-2xl"></i></div>
            <div class="ml-4"><h3 class="text-gray-500 dark:text-gray-400 text-sm">Total Applications</h3><p class="text-2xl font-bold">{{ $totalApplications }}</p></div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full"><i class="fas fa-star text-purple-600 dark:text-purple-300 text-2xl"></i></div>
            <div class="ml-4"><h3 class="text-gray-500 dark:text-gray-400 text-sm">Shortlisted</h3><p class="text-2xl font-bold">{{ $shortlistedCandidates }}</p></div>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('employer.jobs.create') }}" class="p-4 bg-blue-50 dark:bg-blue-900/30 rounded text-center hover:bg-blue-100 dark:hover:bg-blue-900/50"><i class="fas fa-plus-circle text-2xl text-blue-600 dark:text-blue-300 mb-2"></i><p>Post Job</p></a>
        <a href="{{ route('employer.jobs.index') }}" class="p-4 bg-green-50 dark:bg-green-900/30 rounded text-center hover:bg-green-100 dark:hover:bg-green-900/50"><i class="fas fa-briefcase text-2xl text-green-600 dark:text-green-300 mb-2"></i><p>My Jobs</p></a>
        <a href="{{ route('employer.applications.index') }}" class="p-4 bg-purple-50 dark:bg-purple-900/30 rounded text-center hover:bg-purple-100 dark:hover:bg-purple-900/50"><i class="fas fa-users text-2xl text-purple-600 dark:text-purple-300 mb-2"></i><p>Applications</p></a>
        <a href="{{ route('employer.profile') }}" class="p-4 bg-yellow-50 dark:bg-yellow-900/30 rounded text-center hover:bg-yellow-100 dark:hover:bg-yellow-900/50"><i class="fas fa-building text-2xl text-yellow-600 dark:text-yellow-300 mb-2"></i><p>Profile</p></a>
    </div>
</div>
@endsection
