@extends('layouts.employer')

@section('title', 'Interviews')

@section('content')
<h1 class="text-2xl font-bold mb-6">Scheduled Interviews</h1>
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr><th class="px-6 py-3 text-left text-xs font-medium uppercase">Candidate</th><th class="px-6 py-3 text-left text-xs font-medium uppercase">Job</th><th class="px-6 py-3 text-left text-xs font-medium uppercase">Date</th><th class="px-6 py-3 text-left text-xs font-medium uppercase">Time</th><th class="px-6 py-3 text-left text-xs font-medium uppercase">Type</th><th class="px-6 py-3 text-left text-xs font-medium uppercase">Status</th><th class="px-6 py-3 text-left text-xs font-medium uppercase">Action</th></tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
            @foreach($interviews as $interview)
            <tr>
                <td class="px-6 py-4">{{ $interview->candidate->user->name }}</td>
                <td class="px-6 py-4">{{ $interview->application->jobListing->title }}</td>
                <td class="px-6 py-4">{{ $interview->scheduled_date->format('M d, Y') }}</td>
                <td class="px-6 py-4">{{ $interview->scheduled_time }}</td>
                <td class="px-6 py-4">{{ ucfirst($interview->interview_type) }}</td>
                <td class="px-6 py-4"><span class="px-2 py-1 rounded text-xs bg-{{ $interview->status == 'scheduled' ? 'blue' : ($interview->status == 'completed' ? 'green' : 'red') }}-100">{{ $interview->status }}</span></td>
                <td class="px-6 py-4">
                    <form action="{{ route('employer.interviews.status', $interview) }}" method="POST" class="inline">
                        @csrf
                        <select name="status" onchange="this.form.submit()" class="px-2 py-1 border rounded text-sm">
                            <option value="scheduled" disabled {{ $interview->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="completed" {{ $interview->status == 'completed' ? 'selected' : '' }}>Complete</option>
                            <option value="cancelled" {{ $interview->status == 'cancelled' ? 'selected' : '' }}>Cancel</option>
                            <option value="rescheduled" {{ $interview->status == 'rescheduled' ? 'selected' : '' }}>Reschedule</option>
                        </select>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $interviews->links() }}</div>
</div>
@endsection
