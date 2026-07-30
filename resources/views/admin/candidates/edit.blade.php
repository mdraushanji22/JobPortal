@extends('layouts.admin')

@section('title', 'Edit Candidate')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Edit Candidate</h1>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form action="{{ route('admin.candidates.update', $candidate) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $candidate->phone) }}" placeholder="e.g. +1 234 567 8900" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Skills</label>
                    <input type="text" name="skills" value="{{ old('skills', $candidate->skills) }}" placeholder="e.g. PHP, Laravel, MySQL" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Education</label>
                    <textarea name="education" rows="3" placeholder="Your educational background" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400">{{ old('education', $candidate->education) }}</textarea>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Experience</label>
                    <textarea name="experience" rows="3" placeholder="Your work experience" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400">{{ old('experience', $candidate->experience) }}</textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.candidates.index') }}" class="px-4 py-2 border rounded dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 text-white rounded">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
