@extends('layouts.admin')

@section('title', 'Edit Job')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Edit Job</h1>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form action="{{ route('admin.jobs.update', $job) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Title</label>
                <input type="text" name="title" value="{{ old('title', $job->title) }}" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Description</label>
                <textarea name="description" rows="5" class="w-full px-3 py-2 border rounded" required>{{ old('description', $job->description) }}</textarea>
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="is_featured" value="1" {{ $job->is_featured ? 'checked' : '' }} class="mr-2">
                <label class="text-sm font-medium">Featured Job</label>
            </div>
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.jobs.index') }}" class="px-4 py-2 border rounded">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
