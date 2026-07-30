@extends('layouts.employer')

@section('title', 'Application Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Application Details</h1>
        <div class="flex space-x-2">
            <form action="{{ route('employer.applications.status', $application) }}" method="POST" class="inline">
                @csrf
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 border rounded">
                    <option value="under_review" {{ $application->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Shortlist</option>
                    <option value="selected" {{ $application->status == 'selected' ? 'selected' : '' }}>Select</option>
                    <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                </select>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Job Details</h2>
                <p class="text-xl font-bold">{{ $application->jobListing->title }}</p>
                <p class="text-gray-600 dark:text-gray-400">{{ $application->jobListing->employer->company_name }}</p>
                @if($application->cover_letter)
                <div class="mt-4">
                    <h3 class="font-semibold mb-2">Cover Letter</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ $application->cover_letter }}</p>
                </div>
                @endif
                <div class="mt-4">
                    <h3 class="font-semibold mb-2">Employer Notes</h3>
                    <form action="{{ route('employer.applications.status', $application) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="{{ $application->status }}">
                        <textarea name="employer_notes" rows="3" class="w-full px-3 py-2 border rounded">{{ $application->employer_notes }}</textarea>
                        <button type="submit" class="mt-2 px-4 py-2 bg-gray-600 text-white rounded text-sm">Save Notes</button>
                    </form>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Candidate Info</h2>
                <p class="font-medium">{{ $application->candidate->user->name }}</p>
                <p class="text-gray-600 dark:text-gray-400">{{ $application->candidate->user->email }}</p>
                @if($application->candidate->phone)<p class="text-gray-600 dark:text-gray-400">{{ $application->candidate->phone }}</p>@endif
                @if($application->candidate->skills)<p class="mt-2"><strong>Skills:</strong> {{ $application->candidate->skills }}</p>@endif
                @if($application->resume)
                    <a href="{{ route('candidate.resumes.download', $application->resume) }}" class="mt-4 inline-block text-blue-600"><i class="fas fa-download mr-1"></i>Download Resume</a>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Schedule Interview</h2>
                <form action="{{ route('employer.applications.schedule-interview', $application) }}" method="POST">
                    @csrf
                    <div class="space-y-3">
                        <div><label class="block text-sm font-medium mb-1">Date</label><input type="date" name="scheduled_date" class="w-full px-3 py-2 border rounded" required></div>
                        <div><label class="block text-sm font-medium mb-1">Time</label><input type="time" name="scheduled_time" class="w-full px-3 py-2 border rounded" required></div>
                        <div><label class="block text-sm font-medium mb-1">Type</label>
                            <select name="interview_type" class="w-full px-3 py-2 border rounded">
                                <option value="online">Online</option>
                                <option value="offline">Offline</option>
                            </select>
                        </div>
                        <div><label class="block text-sm font-medium mb-1">Meeting Link</label><input type="url" name="meeting_link" class="w-full px-3 py-2 border rounded"></div>
                        <div><label class="block text-sm font-medium mb-1">Notes</label><textarea name="notes" rows="2" class="w-full px-3 py-2 border rounded"></textarea></div>
                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Schedule Interview</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
