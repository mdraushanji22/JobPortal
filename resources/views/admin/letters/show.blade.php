@extends('layouts.admin')

@section('title', ucfirst($letter->letter_type) . ' Letter')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6 flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ ucfirst($letter->letter_type) }} Letter</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ $letter->employer->company_name }} &middot; {{ $letter->candidate->user->name }} &middot; {{ $letter->jobListing->title }}
                <span class="ml-2 px-2 py-0.5 rounded text-xs {{ $letter->status == 'sent' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : ($letter->status == 'draft' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300') }}">{{ ucfirst($letter->status) }}</span>
            </p>
        </div>
        <div class="flex flex-wrap space-x-2">
            <a href="{{ route('admin.letters.pdf', $letter) }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"><i class="fas fa-download mr-1"></i>Download PDF</a>
            @unless($letter->status == 'archived')
            <form action="{{ route('admin.letters.archive', $letter) }}" method="POST" class="inline" onsubmit="return confirm('Archive this letter?');">
                @csrf
                <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700"><i class="fas fa-archive mr-1"></i>Archive</button>
            </form>
            @endunless
            <form action="{{ route('admin.letters.destroy', $letter) }}" method="POST" class="inline" onsubmit="return confirm('Delete this letter permanently?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"><i class="fas fa-trash mr-1"></i>Delete</button>
            </form>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 overflow-x-auto">
        @include('letters.show', ['letter' => $letter])
    </div>
</div>
@endsection
