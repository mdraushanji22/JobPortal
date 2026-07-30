@extends('layouts.employer')

@section('title', 'Applications')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Applications</h1>
    <div class="flex space-x-2">
        <form method="GET">
            <select name="job_id" onchange="this.form.submit()" class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
                <option value="">All Jobs</option>
                @foreach($jobs as $j)<option value="{{ $j->id }}" {{ request('job_id') == $j->id ? 'selected' : '' }}>{{ $j->title }}</option>@endforeach
            </select>
        </form>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr><th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Candidate</th><th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Job</th><th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Status</th><th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Applied</th><th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Actions</th></tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
            @foreach($applications as $app)
            <tr>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full mr-2 flex items-center justify-center"><i class="fas fa-user text-xs text-gray-600 dark:text-gray-400"></i></div>
                        <span>{{ $app->candidate->user->name }}</span>
                    </div>
                </td>
                <td class="px-6 py-4">{{ $app->jobListing->title }}</td>
                <td class="px-6 py-4"><span class="px-2 py-1 rounded text-xs bg-{{ $app->status == 'applied' ? 'blue' : ($app->status == 'shortlisted' ? 'yellow' : ($app->status == 'selected' ? 'green' : ($app->status == 'interview_scheduled' ? 'purple' : 'red'))) }}-100 text-{{ $app->status == 'applied' ? 'blue' : ($app->status == 'shortlisted' ? 'yellow' : ($app->status == 'selected' ? 'green' : ($app->status == 'interview_scheduled' ? 'purple' : 'red'))) }}-800 dark:bg-{{ $app->status == 'applied' ? 'blue' : ($app->status == 'shortlisted' ? 'yellow' : ($app->status == 'selected' ? 'green' : ($app->status == 'interview_scheduled' ? 'purple' : 'red'))) }}-900/30 dark:text-{{ $app->status == 'applied' ? 'blue' : ($app->status == 'shortlisted' ? 'yellow' : ($app->status == 'selected' ? 'green' : ($app->status == 'interview_scheduled' ? 'purple' : 'red'))) }}-300">{{ str_replace('_', ' ', $app->status) }}</span></td>
                <td class="px-6 py-4">{{ $app->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4"><a href="{{ route('employer.applications.show', $app) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"><i class="fas fa-eye"></i></a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $applications->links() }}</div>
</div>
@endsection
