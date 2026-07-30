@extends('layouts.admin')

@section('title', 'Employers Management')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Employers</h1>
    <a href="{{ route('admin.employers.create') }}" class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 text-white px-4 py-2 rounded transition">
        <i class="fas fa-plus mr-2"></i>Add Employer
    </a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Company</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Industry</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Verified</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
            @foreach($employers as $employer)
            <tr>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        @if($employer->logo)
                            <img src="{{ asset('storage/' . $employer->logo) }}" class="w-10 h-10 rounded-full mr-3">
                        @else
                            <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full mr-3 flex items-center justify-center">
                                <i class="fas fa-building text-gray-500 dark:text-gray-300"></i>
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $employer->company_name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $employer->user->name }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $employer->user->email }}</td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $employer->industry ?? 'N/A' }}</td>
                <td class="px-6 py-4">
                    @if($employer->is_verified)
                        <span class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 px-2 py-1 rounded text-xs">Verified</span>
                    @else
                        <span class="bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 px-2 py-1 rounded text-xs">Unverified</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.employers.edit', $employer) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.employers.verify', $employer) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300"><i class="fas fa-check-circle"></i></button>
                        </form>
                        <form action="{{ route('admin.employers.suspend', $employer) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-300"><i class="fas fa-ban"></i></button>
                        </form>
                        <form action="{{ route('admin.employers.destroy', $employer) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $employers->links() }}</div>
</div>
@endsection
