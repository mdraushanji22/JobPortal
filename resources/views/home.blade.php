@extends('layouts.public')

@section('content')
<div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-4">Find Your Dream Job</h1>
        <p class="text-xl mb-8 text-blue-100">Browse thousands of jobs from top companies</p>
        <form action="{{ route('jobs.index') }}" method="GET" class="max-w-3xl mx-auto">
            <div class="flex gap-2 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg">
                <input type="text" name="search" placeholder="Search jobs, skills, companies..." class="flex-1 px-4 py-3 text-gray-800 dark:text-white dark:placeholder-gray-400 rounded focus:outline-none">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded font-semibold transition">Search</button>
            </div>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-16">
    <div class="grid grid-cols-3 gap-8 mb-16">
        <div class="text-center p-8 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="text-4xl font-bold text-blue-600 mb-2">{{ $stats['totalJobs'] }}+</div>
            <div class="text-gray-600 dark:text-gray-400">Active Jobs</div>
        </div>
        <div class="text-center p-8 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="text-4xl font-bold text-green-600 mb-2">{{ $stats['totalEmployers'] }}+</div>
            <div class="text-gray-600 dark:text-gray-400">Employers</div>
        </div>
        <div class="text-center p-8 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="text-4xl font-bold text-purple-600 mb-2">{{ $stats['totalCandidates'] }}+</div>
            <div class="text-gray-600 dark:text-gray-400">Candidates</div>
        </div>
    </div>

    @if($featuredJobs->count() > 0)
    <h2 class="text-3xl font-bold mb-8 text-gray-800 dark:text-white">Featured Jobs</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($featuredJobs as $job)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $job->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ $job->employer->company_name }}</p>
                </div>
                @if($job->is_featured)
                    <span class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 text-xs px-2 py-1 rounded">Featured</span>
                @endif
            </div>
            <div class="flex flex-wrap gap-2 mb-4">
                <span class="bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 text-xs px-2 py-1 rounded">{{ $job->employment_type }}</span>
                <span class="bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 text-xs px-2 py-1 rounded">{{ $job->workplace_type }}</span>
                @if($job->location)
                <span class="bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300 text-xs px-2 py-1 rounded">{{ $job->location }}</span>
                @endif
            </div>
            <div class="flex items-center justify-between">
                @if($job->salary_min)
                <span class="text-green-600 font-semibold">${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}/{{ $job->salary_type }}</span>
                @endif
                <a href="{{ route('jobs.show', $job) }}" class="text-blue-600 hover:text-blue-800 font-semibold">View Details &rarr;</a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if($categories->count() > 0)
    <h2 class="text-3xl font-bold mt-16 mb-8 text-gray-800 dark:text-white">Browse by Category</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($categories as $category)
        <a href="{{ route('jobs.index', ['category' => $category->id]) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center hover:shadow-lg transition">
            <div class="text-2xl font-bold text-blue-600 mb-1">{{ $category->job_listings_count }}</div>
            <div class="text-gray-600 dark:text-gray-400">{{ $category->name }}</div>
        </a>
        @endforeach
    </div>
    @endif
</div>
@endsection
