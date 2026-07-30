@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4 dark:text-white">Monthly Jobs Report</h2>
        <canvas id="jobsChart" height="200"></canvas>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4 dark:text-white">Monthly Applications Report</h2>
        <canvas id="applicationsChart" height="200"></canvas>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4 dark:text-white">Employer Report</h2>
        <table class="w-full dark:text-gray-300"><thead><tr><th class="text-left py-2">Company</th><th class="text-left py-2">Jobs</th></tr></thead><tbody>
        @foreach($employers as $emp)
        <tr><td class="py-1">{{ $emp->company_name }}</td><td>{{ $emp->jobListings->count() }}</td></tr>
        @endforeach
        </tbody></table>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4 dark:text-white">Candidate Report</h2>
        <table class="w-full dark:text-gray-300"><thead><tr><th class="text-left py-2">Name</th><th class="text-left py-2">Applications</th></tr></thead><tbody>
        @foreach($candidates as $can)
        <tr><td class="py-1">{{ $can->user->name }}</td><td>{{ $can->applications->count() }}</td></tr>
        @endforeach
        </tbody></table>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('jobsChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode(range(1, 12)) !!},
        datasets: [{ label: 'Jobs', data: {!! json_encode(array_values($monthlyJobs->toArray())) !!}, backgroundColor: '#3b82f6' }]
    }
});
new Chart(document.getElementById('applicationsChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode(range(1, 12)) !!},
        datasets: [{ label: 'Applications', data: {!! json_encode(array_values($monthlyApplications->toArray())) !!}, borderColor: '#10b981', tension: 0.3 }]
    }
});
</script>
@endpush
@endsection
