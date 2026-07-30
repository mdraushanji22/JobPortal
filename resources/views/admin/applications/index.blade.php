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
                <th class="px-6 py-3 text-left text-xs font-medium uppercase">Job</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase">Candidate</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($applications as $app)
            <tr>
                <td class="px-6 py-4">{{ $app->jobListing->title }}</td>
                <td class="px-6 py-4">{{ $app->candidate->user->name }}</td>
                <td class="px-6 py-4"><span class="px-2 py-1 rounded text-xs bg-{{ $app->status == 'applied' ? 'blue' : ($app->status == 'shortlisted' ? 'yellow' : ($app->status == 'selected' ? 'green' : 'red')) }}-100 text-{{ $app->status == 'applied' ? 'blue' : ($app->status == 'shortlisted' ? 'yellow' : ($app->status == 'selected' ? 'green' : 'red')) }}-800">{{ $app->status }}</span></td>
                <td class="px-6 py-4">{{ $app->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4"><a href="{{ route('admin.applications.show', $app) }}" class="text-blue-600"><i class="fas fa-eye"></i></a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $applications->links() }}</div>
</div>
@endsection
