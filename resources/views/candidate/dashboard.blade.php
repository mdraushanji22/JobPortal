@extends('layouts.candidate')

@section('title', 'Candidate Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full"><i class="fas fa-briefcase text-blue-600 text-2xl"></i></div>
            <div class="ml-4"><h3 class="text-gray-500 dark:text-gray-400 text-sm">Applied Jobs</h3><p class="text-2xl font-bold">{{ $appliedJobs }}</p></div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full"><i class="fas fa-bookmark text-yellow-600 text-2xl"></i></div>
            <div class="ml-4"><h3 class="text-gray-500 dark:text-gray-400 text-sm">Saved Jobs</h3><p class="text-2xl font-bold">{{ $savedJobs }}</p></div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full"><i class="fas fa-calendar-check text-green-600 text-2xl"></i></div>
            <div class="ml-4"><h3 class="text-gray-500 dark:text-gray-400 text-sm">Interviews</h3><p class="text-2xl font-bold">{{ $interviews }}</p></div>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('candidate.jobs.index') }}" class="p-4 bg-blue-50 dark:bg-blue-900/30 rounded text-center hover:bg-blue-100 dark:hover:bg-blue-900/50"><i class="fas fa-search text-2xl text-blue-600 mb-2"></i><p>Browse Jobs</p></a>
        <a href="{{ route('candidate.applications.index') }}" class="p-4 bg-green-50 dark:bg-green-900/30 rounded text-center hover:bg-green-100 dark:hover:bg-green-900/50"><i class="fas fa-file-alt text-2xl text-green-600 mb-2"></i><p>My Applications</p></a>
        <a href="{{ route('candidate.saved-jobs.index') }}" class="p-4 bg-yellow-50 dark:bg-yellow-900/30 rounded text-center hover:bg-yellow-100 dark:hover:bg-yellow-900/50"><i class="fas fa-bookmark text-2xl text-yellow-600 mb-2"></i><p>Saved Jobs</p></a>
        <a href="{{ route('candidate.profile') }}" class="p-4 bg-purple-50 dark:bg-purple-900/30 rounded text-center hover:bg-purple-100 dark:hover:bg-purple-900/50"><i class="fas fa-user text-2xl text-purple-600 mb-2"></i><p>My Profile</p></a>
    </div>
</div>
@endsection
