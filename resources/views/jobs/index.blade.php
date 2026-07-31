@extends('layouts.public')

@section('title', 'Browse Jobs')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="font-semibold mb-4 text-gray-800 dark:text-white">Filters</h3>
                <form method="GET" action="{{ route('jobs.index') }}">
                    <div class="space-y-3">
                        <div><label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="w-full px-3 py-2 border rounded text-sm dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400 dark:border-gray-600" placeholder="Keywords..."></div>
                        <div><label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Category</label>
                            <select name="category" class="w-full px-3 py-2 border rounded text-sm dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
                                <option value="">All</option>
                                @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                            </select>
                        </div>
                        <div><label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Employment Type</label>
                            <select name="employment_type" class="w-full px-3 py-2 border rounded text-sm dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
                                <option value="">All</option>
                                @foreach(['full-time','part-time','contract','internship','freelance'] as $type)
                                    <option value="{{ $type }}" {{ request('employment_type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div><label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Workplace</label>
                            <select name="workplace_type" class="w-full px-3 py-2 border rounded text-sm dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
                                <option value="">All</option>
                                @foreach(['on-site','remote','hybrid'] as $wt)
                                    <option value="{{ $wt }}" {{ request('workplace_type') == $wt ? 'selected' : '' }}>{{ ucfirst($wt) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div><label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Experience</label>
                            <select name="experience_level" class="w-full px-3 py-2 border rounded text-sm dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
                                <option value="">All</option>
                                @foreach(['entry','junior','mid','senior','lead'] as $el)
                                    <option value="{{ $el }}" {{ request('experience_level') == $el ? 'selected' : '' }}>{{ ucfirst($el) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div><label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Location</label>
                            <input type="text" name="location" value="{{ request('location') }}" class="w-full px-3 py-2 border rounded text-sm dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400 dark:border-gray-600" placeholder="e.g. New York"></div>
                        <div><label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Min Salary</label>
                            <input type="number" name="salary_min" value="{{ request('salary_min') }}" class="w-full px-3 py-2 border rounded text-sm dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400 dark:border-gray-600" placeholder="e.g. 50000"></div>
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Apply Filters</button>
                        <a href="{{ route('jobs.index') }}" class="block text-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">Clear Filters</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-3">
            <div class="flex justify-between items-center mb-4">
                <p class="text-gray-600 dark:text-gray-400">{{ $jobs->total() }} jobs found</p>
                <div class="flex space-x-2">
                    <form method="GET" action="{{ route('jobs.index') }}">
                        @foreach(request()->except('sort', 'direction') as $key => $value)
                            @if(is_array($value)) @foreach($value as $v) <input type="hidden" name="{{ $key }}[]" value="{{ $v }}"> @endforeach
                            @else <input type="hidden" name="{{ $key }}" value="{{ $value }}"> @endif
                        @endforeach
                        <select name="sort" onchange="this.form.submit()" class="px-3 py-2 border rounded text-sm dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Latest</option>
                            <option value="salary_min" {{ request('sort') == 'salary_min' ? 'selected' : '' }}>Salary</option>
                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title</option>
                        </select>
                        <input type="hidden" name="direction" value="{{ request('direction', 'desc') }}">
                    </form>
                </div>
            </div>

            @forelse($jobs as $job)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-4 hover:shadow-lg transition">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-xl font-semibold"><a href="{{ route('jobs.show', $job) }}" class="text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">{{ $job->title }}</a></h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ $job->employer->company_name }}</p>
                    </div>
                    @if($job->is_featured)<span class="bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-xs px-2 py-1 rounded">Featured</span>@endif
                </div>
                <div class="flex flex-wrap gap-2 mt-3">
                    <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs px-2 py-1 rounded">{{ $job->employment_type }}</span>
                    <span class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs px-2 py-1 rounded">{{ $job->workplace_type }}</span>
                    @if($job->location)<span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 text-xs px-2 py-1 rounded">{{ $job->location }}</span>@endif
                    @if($job->salary_min)<span class="bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 text-xs px-2 py-1 rounded">${{ number_format($job->salary_min) }}-${{ number_format($job->salary_max) }}</span>@endif
                </div>
                <p class="text-gray-600 dark:text-gray-400 mt-3 text-sm">{{ Str::limit($job->description, 200) }}</p>
            </div>
            @empty
            <div class="text-center py-12 text-gray-500 dark:text-gray-400">No jobs found matching your criteria.</div>
            @endforelse

            <div class="mt-6">{{ $jobs->links() }}</div>
        </div>
    </div>
</div>
@endsection
