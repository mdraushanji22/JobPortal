@extends('layouts.employer')

@section('title', 'My Jobs')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">My Jobs</h1>
    <a href="{{ route('employer.jobs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"><i class="fas fa-plus mr-2"></i>Post New Job</a>
</div>
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr><th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Title</th><th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Status</th><th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Applications</th><th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Created</th><th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Actions</th></tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
            @foreach($jobs as $job)
            <tr>
                <td class="px-6 py-4 font-medium">{{ $job->title }}</td>
                <td class="px-6 py-4">
                    @if($job->status == 'approved')<span class="bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 px-2 py-1 rounded text-xs">Approved</span>
                    @elseif($job->status == 'pending')<span class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 px-2 py-1 rounded text-xs">Pending</span>
                    @elseif($job->status == 'closed')<span class="bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 px-2 py-1 rounded text-xs">Closed</span>
                    @elseif($job->status == 'rejected')<span class="bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 px-2 py-1 rounded text-xs">Rejected</span>
                    @endif
                </td>
                <td class="px-6 py-4">{{ $job->applications->count() }}</td>
                <td class="px-6 py-4">{{ $job->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4">
                    <div class="flex space-x-2">
                        <a href="{{ route('employer.jobs.edit', $job) }}" class="text-blue-600 dark:text-blue-400"><i class="fas fa-edit"></i></a>
                        @if($job->status != 'closed')
                        <form action="{{ route('employer.jobs.close', $job) }}" method="POST" class="inline">@csrf <button type="submit" class="text-yellow-600 dark:text-yellow-400"><i class="fas fa-lock"></i></button></form>
                        @else
                        <form action="{{ route('employer.jobs.reopen', $job) }}" method="POST" class="inline">@csrf <button type="submit" class="text-green-600 dark:text-green-400"><i class="fas fa-unlock"></i></button></form>
                        @endif
                        <form action="{{ route('employer.jobs.destroy', $job) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE') <button type="submit" class="text-red-600 dark:text-red-400"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $jobs->links() }}</div>
</div>
@endsection
