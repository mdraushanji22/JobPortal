@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6 dark:text-white">Site Settings</h1>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2 dark:text-gray-300">Site Name</label>
                <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? '') }}" placeholder="e.g. JobPortal" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2 dark:text-gray-300">Site Email</label>
                <input type="email" name="site_email" value="{{ old('site_email', $settings['site_email'] ?? '') }}" placeholder="e.g. admin@jobportal.com" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2 dark:text-gray-300">Site Description</label>
                <textarea name="site_description" rows="3" placeholder="Describe your job portal..." class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 text-white rounded">Save Settings</button>
        </form>
    </div>
</div>
@endsection
