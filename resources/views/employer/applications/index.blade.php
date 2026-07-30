@extends('layouts.employer')

@section('title', 'Applications')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Applications</h1>
    <div class="flex space-x-2">
        <form method="GET">
            <select name="job_id" onchange="this.form.submit()" class="px-3 py-2 border rounded">
                <option value="">All Jobs</option>
                @foreach($jobs as $j)<option value="{{ $j->id }}" {{ request('job_id') == $j->id ? 'selected' : '' }}>{{ $j->title }}</option>@endforeach
            </select>
        </form>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr><th class="px-6 py-3 text-left text-xs font-medium uppercase">Candidate</th><th class="px-6 py-3 text-left text-xs font-medium uppercase">Job</th><th class="px-6 py-3 text-left text-xs font-medium uppercase">Status</th><th class="px-6 py-3 text-left text-xs font-medium uppercase">Applied</th><th class="px-6 py-3 text-left text-xs font-medium uppercase">Actions</th></tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
            @foreach($applications as $app)
            <tr>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 rounded-full mr-2 flex items-center justify-center"><i class="fas fa-user text-xs text-gray-600"></i></div>
                        <span>{{ $app->candidate->user->name }}</span>
                    </div>
                </td>
                <td class="px-6 py-4">{{ $app->jobListing->title }}</td>
                <td class="px-6 py-4"><span class="px-2 py-1 rounded text-xs bg-{{ $app->status == 'applied' ? 'blue' : ($app->status == 'shortlisted' ? 'yellow' : ($app->status == 'selected' ? 'green' : ($app->status == 'interview_scheduled' ? 'purple' : 'red'))) }}-100 text-{{ $app->status == 'applied' ? 'blue' : ($app->status == 'shortlisted' ? 'yellow' : ($app->status == 'selected' ? 'green' : ($app->status == 'interview_scheduled' ? 'purple' : 'red'))) }}-800">{{ str_replace('_', ' ', $app->status) }}</span></td>
                <td class="px-6 py-4">{{ $app->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4"><a href="{{ route('employer.applications.show', $app) }}" class="text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $applications->links() }}</div>
</div>
@endsection
