@extends('layouts.admin')

@section('title', 'Applications')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Applications</h1>
</div>
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Job</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Candidate</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
            @foreach($applications as $app)
            <tr>
                <td class="px-6 py-4 dark:text-gray-300">{{ $app->jobListing->title }}</td>
                <td class="px-6 py-4 dark:text-gray-300">{{ $app->candidate->user->name }}</td>
                <td class="px-6 py-4"><span class="px-2 py-1 rounded text-xs bg-{{ $app->status == 'applied' ? 'blue' : ($app->status == 'shortlisted' ? 'yellow' : ($app->status == 'selected' ? 'green' : 'red')) }}-100 dark:bg-{{ $app->status == 'applied' ? 'blue' : ($app->status == 'shortlisted' ? 'yellow' : ($app->status == 'selected' ? 'green' : 'red')) }}-900/30 text-{{ $app->status == 'applied' ? 'blue' : ($app->status == 'shortlisted' ? 'yellow' : ($app->status == 'selected' ? 'green' : 'red')) }}-800 dark:text-{{ $app->status == 'applied' ? 'blue' : ($app->status == 'shortlisted' ? 'yellow' : ($app->status == 'selected' ? 'green' : 'red')) }}-300">{{ $app->status }}</span></td>
                <td class="px-6 py-4 dark:text-gray-300">{{ $app->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4"><a href="{{ route('admin.applications.show', $app) }}" class="text-blue-600 dark:text-blue-400"><i class="fas fa-eye"></i></a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $applications->links() }}</div>
</div>
@endsection
