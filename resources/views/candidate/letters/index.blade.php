@extends('layouts.candidate')

@section('title', 'My Letters')

@section('content')
<h1 class="text-2xl font-bold mb-6 dark:text-white">My Letters</h1>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Company</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Job</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Issued</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase dark:text-gray-300">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
            @forelse($letters as $letter)
            <tr>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded text-xs {{ $letter->isOffer() ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' }}">{{ ucfirst($letter->letter_type) }} Letter</span>
                </td>
                <td class="px-6 py-4">{{ $letter->employer->company_name }}</td>
                <td class="px-6 py-4">{{ $letter->jobListing->title }}</td>
                <td class="px-6 py-4">{{ $letter->sent_at?->format('M d, Y') }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('candidate.letters.show', $letter) }}" class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">View</a>
                    <a href="{{ route('candidate.letters.pdf', $letter) }}" class="ml-2 px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700"><i class="fas fa-download mr-1"></i>PDF</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">No letters issued yet. You will see offer and joining letters here once an employer sends them.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $letters->links() }}</div>
</div>
@endsection
