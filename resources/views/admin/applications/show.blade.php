@extends('layouts.admin')

@section('title', 'Application Details')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Application Details</h1>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div><strong>Job:</strong> {{ $application->jobListing->title }}</div>
            <div><strong>Candidate:</strong> {{ $application->candidate->user->name }}</div>
            <div><strong>Status:</strong> {{ $application->status }}</div>
            <div><strong>Applied:</strong> {{ $application->created_at->format('M d, Y H:i') }}</div>
        </div>
        @if($application->cover_letter)
        <div class="mb-4">
            <h3 class="font-semibold mb-2">Cover Letter</h3>
            <p class="text-gray-600 dark:text-gray-400">{{ $application->cover_letter }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
