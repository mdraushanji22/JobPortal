@extends('layouts.candidate')

@section('title', 'Candidate Dashboard')

@section('content')
@if($notifications->isNotEmpty())
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold dark:text-white">Notifications</h3>
        <a href="{{ route('candidate.notifications.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View all</a>
    </div>
    <div class="divide-y divide-gray-200 dark:divide-gray-600">
        @foreach($notifications as $notification)
        <div class="py-3 flex items-start">
            <div class="p-2 mr-3 rounded-full {{ $notification->is_read ? 'bg-gray-100 dark:bg-gray-700' : 'bg-blue-100 dark:bg-blue-900' }}"><i class="fas fa-envelope-open-text {{ $notification->is_read ? 'text-gray-500' : 'text-blue-600 dark:text-blue-300' }}"></i></div>
            <div class="flex-1">
                <p class="font-medium dark:text-white">{{ $notification->title }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $notification->message }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
            </div>
            @unless($notification->is_read)
            <form action="{{ route('candidate.notifications.read', $notification) }}" method="POST">
                @csrf
                <button type="submit" class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Open</button>
            </form>
            @else
                @if(is_array($notification->data) && isset($notification->data['url']))
                <a href="{{ $notification->data['url'] }}" class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-50 dark:hover:bg-gray-700">View</a>
                @endif
            @endunless
        </div>
        @endforeach
    </div>
</div>
@endif

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
