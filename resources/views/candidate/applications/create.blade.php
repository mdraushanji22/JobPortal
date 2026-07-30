@extends('layouts.candidate')

@section('title', 'Apply for Job')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-2">Apply for {{ $job->title }}</h1>
    <p class="text-gray-600 dark:text-gray-400 mb-6">{{ $job->employer->company_name }}</p>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form action="{{ route('candidate.applications.store', $job) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Cover Letter</label>
                <textarea name="cover_letter" rows="8" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400 dark:border-gray-600 @error('cover_letter') border-red-500 @enderror" placeholder="Write your cover letter here...">{{ old('cover_letter') }}</textarea>
                @error('cover_letter')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            @if($resumes->count() > 0)
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Select Resume</label>
                @foreach($resumes as $resume)
                <label class="flex items-center p-3 border rounded mb-2 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <input type="radio" name="resume_id" value="{{ $resume->id }}" {{ $resume->is_default ? 'checked' : '' }} class="mr-3">
                    <div>
                        <p class="font-medium">{{ $resume->title }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $resume->file_type }}</p>
                    </div>
                </label>
                @endforeach
            </div>
            @else
            <div class="mb-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded">
                <p class="text-yellow-700 dark:text-yellow-300">No resume uploaded. <a href="{{ route('candidate.resumes.index') }}" class="underline">Upload one now</a></p>
            </div>
            @endif

            <div class="flex justify-end space-x-3">
                <a href="{{ route('candidate.jobs.show', $job) }}" class="px-4 py-2 border rounded dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Submit Application</button>
            </div>
        </form>
    </div>
</div>
@endsection
