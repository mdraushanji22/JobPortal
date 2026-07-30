@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                <i class="fas fa-building text-blue-600 dark:text-blue-300 text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 dark:text-gray-400 text-sm">Total Employers</h3>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalEmployers }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                <i class="fas fa-users text-green-600 dark:text-green-300 text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 dark:text-gray-400 text-sm">Total Candidates</h3>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalCandidates }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                <i class="fas fa-briefcase text-purple-600 dark:text-purple-300 text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 dark:text-gray-400 text-sm">Total Jobs</h3>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalJobs }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                <i class="fas fa-file-alt text-yellow-600 dark:text-yellow-300 text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 dark:text-gray-400 text-sm">Total Applications</h3>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalApplications }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-gray-500 dark:text-gray-400 mb-2">Active Jobs</h3>
        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $activeJobs }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-gray-500 dark:text-gray-400 mb-2">Pending Jobs</h3>
        <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pendingJobs }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-gray-500 dark:text-gray-400 mb-2">Today's Applications</h3>
        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $todayApplications }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Quick Actions</h3>
        <div class="space-y-3">
            <a href="{{ route('admin.employers.index') }}" class="block p-3 bg-blue-50 dark:bg-blue-900/30 rounded hover:bg-blue-100 dark:hover:bg-blue-900/50 transition">
                <i class="fas fa-building text-blue-600 mr-2"></i> Manage Employers
            </a>
            <a href="{{ route('admin.jobs.index') }}" class="block p-3 bg-purple-50 dark:bg-purple-900/30 rounded hover:bg-purple-100 dark:hover:bg-purple-900/50 transition">
                <i class="fas fa-briefcase text-purple-600 mr-2"></i> Manage Jobs
            </a>
            <a href="{{ route('admin.applications.index') }}" class="block p-3 bg-green-50 dark:bg-green-900/30 rounded hover:bg-green-100 dark:hover:bg-green-900/50 transition">
                <i class="fas fa-file-alt text-green-600 mr-2"></i> View Applications
            </a>
            <a href="{{ route('admin.reports.index') }}" class="block p-3 bg-yellow-50 dark:bg-yellow-900/30 rounded hover:bg-yellow-100 dark:hover:bg-yellow-900/50 transition">
                <i class="fas fa-chart-bar text-yellow-600 mr-2"></i> View Reports
            </a>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Recent Activity</h3>
        <p class="text-gray-500 dark:text-gray-400">Activity log feature coming soon.</p>
    </div>
</div>
@endsection
