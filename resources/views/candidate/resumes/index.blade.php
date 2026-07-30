@extends('layouts.candidate')

@section('title', 'My Resumes')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">My Resumes</h1>
    <button onclick="document.getElementById('uploadForm').classList.toggle('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"><i class="fas fa-upload mr-2"></i>Upload Resume</button>
</div>

<div id="uploadForm" class="hidden bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
    <form action="{{ route('candidate.resumes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium mb-2">Title</label>
                <input type="text" name="title" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400 dark:border-gray-600" placeholder="e.g. Main Resume" required></div>
            <div><label class="block text-sm font-medium mb-2">File (PDF, DOC, DOCX)</label>
                <input type="file" name="resume" accept=".pdf,.doc,.docx" class="w-full dark:text-gray-300" required></div>
        </div>
        <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Upload</button>
    </form>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    @forelse($resumes as $resume)
    <div class="p-4 border-b dark:border-gray-700 flex items-center justify-between">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full mr-4"><i class="fas fa-file-alt text-blue-600 text-xl"></i></div>
            <div>
                <p class="font-medium">{{ $resume->title }} @if($resume->is_default)<span class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs px-2 py-1 rounded ml-2">Default</span>@endif</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ strtoupper($resume->file_type) }} - Uploaded {{ $resume->created_at->format('M d, Y') }}</p>
            </div>
        </div>
        <div class="flex space-x-2">
            @if(!$resume->is_default)
            <form action="{{ route('candidate.resumes.default', $resume) }}" method="POST" class="inline">@csrf <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300"><i class="fas fa-check"></i></button></form>
            @endif
            <a href="{{ route('candidate.resumes.download', $resume) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"><i class="fas fa-download"></i></a>
            <form action="{{ route('candidate.resumes.destroy', $resume) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE') <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"><i class="fas fa-trash"></i></button></form>
        </div>
    </div>
    @empty
    <div class="p-6 text-center text-gray-500 dark:text-gray-400">No resumes uploaded yet.</div>
    @endforelse
</div>
@endsection
