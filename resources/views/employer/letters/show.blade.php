@extends('layouts.employer')

@section('title', ucfirst($letter->letter_type) . ' Letter')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6 flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold dark:text-white">{{ ucfirst($letter->letter_type) }} Letter</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ $letter->candidate->user->name }} &middot; {{ $letter->jobListing->title }}
                <span class="ml-2 px-2 py-0.5 rounded text-xs {{ $letter->status == 'sent' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' }}">{{ ucfirst($letter->status) }}</span>
            </p>
        </div>
        <div class="flex flex-wrap space-x-2">
            <a href="{{ route('employer.letters.edit', $letter) }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700"><i class="fas fa-edit mr-1"></i>Edit</a>
            @if($letter->status == 'draft')
            <form action="{{ route('employer.letters.send', $letter) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"><i class="fas fa-paper-plane mr-1"></i>Send Letter</button>
            </form>
            @endif
            <a href="{{ route('employer.letters.pdf', $letter) }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"><i class="fas fa-download mr-1"></i>Download PDF</a>
            @if($letter->status == 'sent')
            <form action="{{ route('employer.letters.regenerate', $letter) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"><i class="fas fa-sync mr-1"></i>Regenerate PDF</button>
            </form>
            @endif
            <form action="{{ route('employer.letters.destroy', $letter) }}" method="POST" class="inline" onsubmit="return confirm('Delete this letter permanently?');">
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
