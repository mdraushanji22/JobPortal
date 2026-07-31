@extends('layouts.candidate')

@section('title', ucfirst($letter->letter_type) . ' Letter')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6 flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold dark:text-white">{{ ucfirst($letter->letter_type) }} Letter</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ $letter->employer->company_name }} &middot; {{ $letter->jobListing->title }}
                <span class="ml-2 text-green-600 dark:text-green-400"><i class="fas fa-check-circle mr-1"></i>Issued {{ $letter->sent_at?->format('M d, Y') }}</span>
            </p>
        </div>
        <a href="{{ route('candidate.letters.pdf', $letter) }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"><i class="fas fa-download mr-1"></i>Download PDF</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 overflow-x-auto">
        @include('letters.show', ['letter' => $letter])
    </div>
</div>
@endsection
