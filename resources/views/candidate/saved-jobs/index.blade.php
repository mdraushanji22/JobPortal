@extends('layouts.candidate')

@section('title', 'Saved Jobs')

@section('content')
<h1 class="text-2xl font-bold mb-6">Saved Jobs</h1>

@forelse($savedJobs as $saved)
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-4">
    <div class="flex justify-between items-start">
        <div>
            <h3 class="text-xl font-semibold"><a href="{{ route('candidate.jobs.show', $saved->jobListing) }}" class="text-gray-800 dark:text-white hover:text-blue-600">{{ $saved->jobListing->title }}</a></h3>
            <p class="text-gray-600 dark:text-gray-400">{{ $saved->jobListing->employer->company_name }}</p>
            <div class="flex flex-wrap gap-2 mt-2">
                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ $saved->jobListing->employment_type }}</span>
                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">{{ $saved->jobListing->workplace_type }}</span>
                @if($saved->jobListing->location)<span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">{{ $saved->jobListing->location }}</span>@endif
            </div>
        </div>
        <form action="{{ route('candidate.saved-jobs.remove', $saved) }}" method="POST" class="inline">@csrf @method('DELETE')
            <button type="submit" class="text-red-500 hover:text-red-700"><i class="fas fa-bookmark"></i></button>
        </form>
    </div>
</div>
@empty
<div class="text-center py-12 text-gray-500">No saved jobs yet. <a href="{{ route('candidate.jobs.index') }}" class="text-blue-600 underline">Browse jobs</a></div>
@endforelse

<div class="mt-6">{{ $savedJobs->links() }}</div>
@endsection
