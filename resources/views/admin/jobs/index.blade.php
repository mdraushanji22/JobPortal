@extends('layouts.admin')

@section('title', 'Jobs Management')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Jobs</h1>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Employer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Featured</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
            @foreach($jobs as $job)
            <tr>
                <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">{{ $job->title }}</td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $job->employer->company_name }}</td>
                <td class="px-6 py-4">
                    @if($job->status == 'approved')
                        <span class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 px-2 py-1 rounded text-xs">Approved</span>
                    @elseif($job->status == 'pending')
                        <span class="bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 px-2 py-1 rounded text-xs">Pending</span>
                    @elseif($job->status == 'rejected')
                        <span class="bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 px-2 py-1 rounded text-xs">Rejected</span>
                    @else
                        <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 px-2 py-1 rounded text-xs">{{ $job->status }}</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @if($job->is_featured)
                        <span class="bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 px-2 py-1 rounded text-xs">Featured</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex space-x-2">
                        @if($job->status == 'pending')
                            <form action="{{ route('admin.jobs.approve', $job) }}" method="POST" class="inline">@csrf <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300"><i class="fas fa-check"></i></button></form>
                            <form action="{{ route('admin.jobs.reject', $job) }}" method="POST" class="inline">@csrf <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"><i class="fas fa-times"></i></button></form>
                        @endif
                        <form action="{{ route('admin.jobs.featured', $job) }}" method="POST" class="inline">@csrf <button type="submit" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-300"><i class="fas fa-star"></i></button></form>
                        <a href="{{ route('admin.jobs.edit', $job) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE') <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $jobs->links() }}</div>
</div>
@endsection
