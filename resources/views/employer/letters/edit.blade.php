@extends('layouts.employer')

@section('title', 'Edit ' . ucfirst($letter->letter_type) . ' Letter')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold dark:text-white">Edit {{ ucfirst($letter->letter_type) }} Letter</h1>
        <a href="{{ route('employer.letters.show', $letter) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Preview</a>
    </div>

    @include('employer.letters._form', [
        'letter' => $letter,
        'application' => null,
        'type' => $letter->letter_type,
        'action' => route('employer.letters.update', $letter),
        'method' => 'put',
        'cancel' => route('employer.letters.show', $letter),
    ])
</div>
@endsection
