@extends('layouts.candidate')

@section('title', 'My Profile')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">My Profile</h1>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form action="{{ route('candidate.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="flex items-center mb-6">
                @if($candidate->profile_picture)
                    <img src="{{ asset('storage/' . $candidate->profile_picture) }}" class="w-20 h-20 rounded-full mr-4">
                @else
                    <div class="w-20 h-20 bg-gray-200 dark:bg-gray-700 rounded-full mr-4 flex items-center justify-center"><i class="fas fa-user text-2xl text-gray-400"></i></div>
                @endif
                <div><label class="block text-sm font-medium mb-2">Profile Picture</label><input type="file" name="profile_picture" accept="image/*" class="text-sm"></div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium mb-2">Name</label>
                    <input type="text" value="{{ $candidate->user->name }}" class="w-full px-3 py-2 border rounded bg-gray-50" disabled></div>
                <div><label class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" value="{{ $candidate->user->email }}" class="w-full px-3 py-2 border rounded bg-gray-50" disabled></div>
                <div><label class="block text-sm font-medium mb-2">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $candidate->phone) }}" class="w-full px-3 py-2 border rounded"></div>
                <div><label class="block text-sm font-medium mb-2">Skills</label>
                    <input type="text" name="skills" value="{{ old('skills', $candidate->skills) }}" class="w-full px-3 py-2 border rounded" placeholder="e.g. PHP, Laravel, MySQL"></div>
                <div class="col-span-2"><label class="block text-sm font-medium mb-2">Education</label>
                    <textarea name="education" rows="3" class="w-full px-3 py-2 border rounded">{{ old('education', $candidate->education) }}</textarea></div>
                <div class="col-span-2"><label class="block text-sm font-medium mb-2">Experience</label>
                    <textarea name="experience" rows="3" class="w-full px-3 py-2 border rounded">{{ old('experience', $candidate->experience) }}</textarea></div>
                <div><label class="block text-sm font-medium mb-2">Portfolio URL</label>
                    <input type="url" name="portfolio_url" value="{{ old('portfolio_url', $candidate->portfolio_url) }}" class="w-full px-3 py-2 border rounded"></div>
                <div><label class="block text-sm font-medium mb-2">LinkedIn URL</label>
                    <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $candidate->linkedin_url) }}" class="w-full px-3 py-2 border rounded"></div>
                <div><label class="block text-sm font-medium mb-2">GitHub URL</label>
                    <input type="url" name="github_url" value="{{ old('github_url', $candidate->github_url) }}" class="w-full px-3 py-2 border rounded"></div>
                <div class="col-span-2"><label class="block text-sm font-medium mb-2">Bio</label>
                    <textarea name="bio" rows="3" class="w-full px-3 py-2 border rounded">{{ old('bio', $candidate->bio) }}</textarea></div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update Profile</button>
            </div>
        </form>
    </div>
</div>
@endsection
