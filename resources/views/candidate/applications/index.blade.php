@extends('layouts.candidate')

@section('title', 'My Applications')

@section('content')
<h1 class="text-2xl font-bold mb-6">My Applications</h1>
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr><th class="px-6 py-3 text-left text-xs font-medium uppercase">Job</th><th class="px-6 py-3 text-left text-xs font-medium uppercase">Company</th><th class="px-6 py-3 text-left text-xs font-medium uppercase">Status</th><th class="px-6 py-3 text-left text-xs font-medium uppercase">Applied</th><th class="px-6 py-3 text-left text-xs font-medium uppercase">Action</th></tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
            @foreach($applications as $app)
            <tr>
                <td class="px-6 py-4 font-medium">{{ $app->jobListing->title }}</td>
                <td class="px-6 py-4">{{ $app->jobListing->employer->company_name }}</td>
                <td class="px-6 py-4">
                    @php
                        $statusColors = ['applied' => 'blue', 'shortlisted' => 'yellow', 'selected' => 'green', 'interview_scheduled' => 'purple', 'hired' => 'green', 'rejected' => 'red'];
                        $color = $statusColors[$app->status] ?? 'gray';
                    @endphp
                    <span class="px-2 py-1 rounded text-xs bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 text-{{ $color }}-800 dark:text-{{ $color }}-300">{{ str_replace('_', ' ', ucfirst($app->status)) }}</span></td>
                <td class="px-6 py-4">{{ $app->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4">
                    <div class="flex space-x-2">
                        @if(in_array($app->status, ['applied', 'under_review']))
                        <form action="{{ route('candidate.applications.withdraw', $app) }}" method="POST" class="inline" onsubmit="return confirm('Withdraw application?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm">Withdraw</button>
                        </form>
                        @endif
                        <a href="{{ route('messages.open', $app) }}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 text-sm"><i class="fas fa-comments mr-1"></i>Message</a>
                        @if($app->interview)
                            <span class="text-green-600 dark:text-green-400 text-sm"><i class="fas fa-calendar-check mr-1"></i>Interview: {{ $app->interview->scheduled_date->format('M d') }}</span>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $applications->links() }}</div>
</div>
@endsection
