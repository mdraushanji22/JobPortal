@extends('layouts.employer')

@section('title', 'Generate ' . ucfirst($type) . ' Letter')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold dark:text-white">Generate {{ ucfirst($type) }} Letter</h1>
        <a href="{{ route('employer.applications.show', $application) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Back to Application</a>
    </div>

    @include('employer.letters._form', [
        'letter' => null,
        'application' => $application,
        'type' => $type,
        'action' => route('employer.letters.store'),
        'method' => 'post',
        'cancel' => route('employer.applications.show', $application),
    ])
</div>
@endsection
