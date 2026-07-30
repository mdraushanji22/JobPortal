@extends('layouts.admin')

@section('title', 'Candidate Details')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Candidate Details</h1>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center mb-6">
            @if($candidate->profile_picture)
                <img src="{{ asset('storage/' . $candidate->profile_picture) }}" class="w-20 h-20 rounded-full mr-4">
            @else
                <div class="w-20 h-20 bg-gray-300 dark:bg-gray-600 rounded-full mr-4 flex items-center justify-center">
                    <i class="fas fa-user text-3xl text-gray-500"></i>
                </div>
            @endif
            <div>
                <h2 class="text-xl font-semibold">{{ $candidate->user->name }}</h2>
                <p class="text-gray-600 dark:text-gray-400">{{ $candidate->user->email }}</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div><strong>Phone:</strong> {{ $candidate->phone ?? 'N/A' }}</div>
            <div><strong>Skills:</strong> {{ $candidate->skills ?? 'N/A' }}</div>
            <div class="col-span-2"><strong>Education:</strong> {{ $candidate->education ?? 'N/A' }}</div>
            <div class="col-span-2"><strong>Experience:</strong> {{ $candidate->experience ?? 'N/A' }}</div>
            <div><strong>Portfolio:</strong> @if($candidate->portfolio_url) <a href="{{ $candidate->portfolio_url }}" target="_blank" class="text-blue-600">Link</a> @else N/A @endif</div>
            <div><strong>LinkedIn:</strong> @if($candidate->linkedin_url) <a href="{{ $candidate->linkedin_url }}" target="_blank" class="text-blue-600">Link</a> @else N/A @endif</div>
        </div>
        <div class="mt-6">
            <h3 class="font-semibold mb-2">Applied Jobs</h3>
            @foreach($candidate->applications as $app)
                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded mb-2">
                    <p>{{ $app->jobListing->title }} - <span class="text-sm text-gray-500">{{ $app->status }}</span></p>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
