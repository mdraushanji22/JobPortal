@extends('layouts.admin')

@section('title', 'Letters')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Offer &amp; Joining Letters</h1>
</div>

<div class="mb-6">
    <form method="GET" action="{{ route('admin.letters.index') }}" class="flex flex-wrap gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search candidate, company or job..." class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 w-72">
        <select name="type" class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
            <option value="">All Types</option>
            <option value="offer" {{ request('type') == 'offer' ? 'selected' : '' }}>Offer</option>
            <option value="joining" {{ request('type') == 'joining' ? 'selected' : '' }}>Joining</option>
        </select>
        <select name="status" class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
            <option value="">All Statuses</option>
            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Filter</button>
    </form>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Company</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Candidate</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Job</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Issued</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
            @forelse($letters as $letter)
            <tr>
                <td class="px-6 py-4 dark:text-gray-300">{{ $letter->employer->company_name }}</td>
                <td class="px-6 py-4 dark:text-gray-300">{{ $letter->candidate->user->name }}</td>
                <td class="px-6 py-4 dark:text-gray-300">{{ $letter->jobListing->title }}</td>
                <td class="px-6 py-4"><span class="px-2 py-1 rounded text-xs {{ $letter->isOffer() ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' }}">{{ ucfirst($letter->letter_type) }}</span></td>
                <td class="px-6 py-4"><span class="px-2 py-1 rounded text-xs {{ $letter->status == 'sent' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : ($letter->status == 'draft' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300') }}">{{ ucfirst($letter->status) }}</span></td>
                <td class="px-6 py-4 dark:text-gray-300">{{ $letter->sent_at?->format('M d, Y') ?? '-' }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.letters.show', $letter) }}" class="text-blue-600 dark:text-blue-400"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('admin.letters.pdf', $letter) }}" class="ml-2 text-green-600 dark:text-green-400"><i class="fas fa-download"></i></a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">No letters found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $letters->links() }}</div>
</div>
@endsection
