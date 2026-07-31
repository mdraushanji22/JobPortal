@extends('layouts.employer')

@section('title', 'Offer & Joining Letters')

@section('content')
<h1 class="text-2xl font-bold mb-6 dark:text-white">Offer &amp; Joining Letters</h1>

<div class="mb-6 flex flex-wrap gap-4">
    <form method="GET" action="{{ route('employer.letters.index') }}" class="flex flex-wrap gap-2">
        <select name="type" class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
            <option value="">All Types</option>
            <option value="offer" {{ request('type') == 'offer' ? 'selected' : '' }}>Offer Letter</option>
            <option value="joining" {{ request('type') == 'joining' ? 'selected' : '' }}>Joining Letter</option>
        </select>
        <select name="status" class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
            <option value="">All Statuses</option>
            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Filter</button>
    </form>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Candidate</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Job</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Issued</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
            @forelse($letters as $letter)
            <tr>
                <td class="px-6 py-4">{{ $letter->candidate->user->name }}</td>
                <td class="px-6 py-4">{{ $letter->jobListing->title }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded text-xs {{ $letter->isOffer() ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' }}">{{ ucfirst($letter->letter_type) }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded text-xs {{ $letter->status == 'sent' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' }}">{{ ucfirst($letter->status) }}</span>
                </td>
                <td class="px-6 py-4">{{ $letter->sent_at?->format('M d, Y') ?? '-' }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('employer.letters.show', $letter) }}" class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">View</a>
                    @if($letter->status == 'draft')
                    <a href="{{ route('employer.letters.edit', $letter) }}" class="ml-2 px-3 py-1 bg-gray-600 text-white rounded text-sm hover:bg-gray-700">Edit</a>
                    @endif
                    <a href="{{ route('employer.letters.pdf', $letter) }}" class="ml-2 px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">PDF</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">No letters found yet. Select a candidate to generate their first letter.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $letters->links() }}</div>
</div>
@endsection
