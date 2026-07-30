@extends('layouts.candidate')

@section('title', $job->title)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $job->title }}</h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg">{{ $job->employer->company_name }}</p>
            </div>
            @if($job->is_featured)<span class="bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 px-3 py-1 rounded text-sm">Featured</span>@endif
        </div>

        <div class="flex flex-wrap gap-3 mb-6">
            <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 px-3 py-1 rounded text-sm"><i class="fas fa-clock mr-1"></i>{{ ucfirst($job->employment_type) }}</span>
            <span class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 px-3 py-1 rounded text-sm"><i class="fas fa-building mr-1"></i>{{ ucfirst($job->workplace_type) }}</span>
            @if($job->location)<span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 px-3 py-1 rounded text-sm"><i class="fas fa-map-marker-alt mr-1"></i>{{ $job->location }}</span>@endif
            @if($job->salary_min)<span class="bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 px-3 py-1 rounded text-sm"><i class="fas fa-dollar-sign mr-1"></i>${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}/{{ $job->salary_type }}</span>@endif
            <span class="bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 px-3 py-1 rounded text-sm"><i class="fas fa-level-up-alt mr-1"></i>{{ $job->experience_level ?? 'Any' }}</span>
            @if($job->vacancy_count > 1)<span class="bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 px-3 py-1 rounded text-sm"><i class="fas fa-users mr-1"></i>{{ $job->vacancy_count }} positions</span>@endif
        </div>

        <div class="prose max-w-none mb-6">
            <h3 class="text-lg font-semibold mb-2">Description</h3>
            <p>{{ $job->description }}</p>
        </div>

        @if($job->responsibilities)
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Responsibilities</h3>
            <p>{{ $job->responsibilities }}</p>
        </div>
        @endif

        @if($job->requirements)
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Requirements</h3>
            <p>{{ $job->requirements }}</p>
        </div>
        @endif

        @if($job->benefits)
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Benefits</h3>
            <p>{{ $job->benefits }}</p>
        </div>
        @endif

        @if($job->skills_required)
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Skills Required</h3>
            <div class="flex flex-wrap gap-2">
                @foreach(explode(',', $job->skills_required) as $skill)
                <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1 rounded text-sm">{{ trim($skill) }}</span>
                @endforeach
            </div>
        </div>
        @endif

        @if($job->application_deadline)
        <p class="text-red-600 dark:text-red-400 text-sm mb-6"><i class="fas fa-calendar-alt mr-1"></i>Apply before: {{ $job->application_deadline->format('M d, Y') }}</p>
        @endif

        <div class="flex space-x-3">
            @auth
                @if(Auth::user()->isCandidate())
                    @if($hasApplied)
                        <button disabled class="px-6 py-3 bg-gray-400 dark:bg-gray-600 text-white rounded cursor-not-allowed">Already Applied</button>
                    @else
                        <a href="{{ route('candidate.applications.create', $job) }}" class="px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 transition"><i class="fas fa-paper-plane mr-2"></i>Apply Now</a>
                    @endif
                    <button onclick="toggleSaveJob({{ $job->id }})" class="px-4 py-3 border rounded hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700 transition">
                        <i id="save-icon-{{ $job->id }}" class="fas {{ $isSaved ? 'fa-bookmark text-yellow-500' : 'fa-bookmark text-gray-400' }} mr-1"></i>
                        <span id="save-text-{{ $job->id }}">{{ $isSaved ? 'Saved' : 'Save Job' }}</span>
                    </button>
                @endif
            @endauth
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleSaveJob(jobId) {
    fetch('{{ route("candidate.saved-jobs.toggle", $job) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' } })
    .then(r => r.json()).then(d => {
        document.getElementById('save-icon-' + jobId).className = 'fas ' + (d.saved ? 'fa-bookmark text-yellow-500' : 'fa-bookmark text-gray-400');
        document.getElementById('save-text-' + jobId).textContent = d.saved ? 'Saved' : 'Save Job';
    });
}
</script>
@endpush
@endsection
