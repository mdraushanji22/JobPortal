@extends('layouts.admin')

@section('title', 'Candidates Management')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Candidates</h1>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Phone</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
            @foreach($candidates as $candidate)
            <tr>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        @if($candidate->profile_picture)
                            <img src="{{ asset('storage/' . $candidate->profile_picture) }}" class="w-10 h-10 rounded-full mr-3">
                        @else
                            <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full mr-3 flex items-center justify-center">
                                <i class="fas fa-user text-gray-500 dark:text-gray-300"></i>
                            </div>
                        @endif
                        <span class="font-medium text-gray-800 dark:text-white">{{ $candidate->user->name }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $candidate->user->email }}</td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $candidate->phone ?? 'N/A' }}</td>
                <td class="px-6 py-4">
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.candidates.show', $candidate) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.candidates.edit', $candidate) }}" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-300"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.candidates.destroy', $candidate) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $candidates->links() }}</div>
</div>
@endsection
