@extends('layouts.admin')

@section('title', 'Edit Job')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Edit Job</h1>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form action="{{ route('admin.jobs.update', $job) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2 dark:text-gray-300">Title</label>
                <input type="text" name="title" value="{{ old('title', $job->title) }}" placeholder="e.g. Senior PHP Developer" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2 dark:text-gray-300">Description</label>
                <textarea name="description" rows="5" placeholder="Detailed job description" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400" required>{{ old('description', $job->description) }}</textarea>
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="is_featured" value="1" {{ $job->is_featured ? 'checked' : '' }} class="mr-2">
                <label class="text-sm font-medium dark:text-gray-300">Featured Job</label>
            </div>
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.jobs.index') }}" class="px-4 py-2 border rounded dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 text-white rounded">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
