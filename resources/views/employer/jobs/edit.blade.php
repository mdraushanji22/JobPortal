@extends('layouts.employer')

@section('title', 'Edit Job')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Job</h1>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form action="{{ route('employer.jobs.update', $job) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2"><label class="block text-sm font-medium mb-2">Job Title</label><input type="text" name="title" value="{{ old('title', $job->title) }}" class="w-full px-3 py-2 border rounded" required></div>
                <div><label class="block text-sm font-medium mb-2">Category</label>
                    <select name="category_id" class="w-full px-3 py-2 border rounded">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id', $job->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium mb-2">Employment Type</label>
                    <select name="employment_type" class="w-full px-3 py-2 border rounded" required>
                        @foreach(['full-time','part-time','contract','internship','freelance'] as $type)
                            <option value="{{ $type }}" {{ old('employment_type', $job->employment_type) == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium mb-2">Workplace Type</label>
                    <select name="workplace_type" class="w-full px-3 py-2 border rounded" required>
                        @foreach(['on-site','remote','hybrid'] as $wt)
                            <option value="{{ $wt }}" {{ old('workplace_type', $job->workplace_type) == $wt ? 'selected' : '' }}>{{ ucfirst($wt) }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium mb-2">Experience Level</label><input type="text" name="experience_level" value="{{ old('experience_level', $job->experience_level) }}" class="w-full px-3 py-2 border rounded"></div>
                <div><label class="block text-sm font-medium mb-2">Location</label><input type="text" name="location" value="{{ old('location', $job->location) }}" class="w-full px-3 py-2 border rounded"></div>
                <div><label class="block text-sm font-medium mb-2">Salary Min ($)</label><input type="number" name="salary_min" value="{{ old('salary_min', $job->salary_min) }}" class="w-full px-3 py-2 border rounded" step="0.01"></div>
                <div><label class="block text-sm font-medium mb-2">Salary Max ($)</label><input type="number" name="salary_max" value="{{ old('salary_max', $job->salary_max) }}" class="w-full px-3 py-2 border rounded" step="0.01"></div>
                <div><label class="block text-sm font-medium mb-2">Salary Type</label>
                    <select name="salary_type" class="w-full px-3 py-2 border rounded">
                        @foreach(['hourly','monthly','yearly'] as $st)<option value="{{ $st }}" {{ old('salary_type', $job->salary_type) == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>@endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium mb-2">Vacancy</label><input type="number" name="vacancy_count" value="{{ old('vacancy_count', $job->vacancy_count) }}" class="w-full px-3 py-2 border rounded" min="1"></div>
                <div><label class="block text-sm font-medium mb-2">Deadline</label><input type="date" name="application_deadline" value="{{ old('application_deadline', $job->application_deadline?->format('Y-m-d')) }}" class="w-full px-3 py-2 border rounded"></div>
                <div class="col-span-2"><label class="block text-sm font-medium mb-2">Skills Required</label><input type="text" name="skills_required" value="{{ old('skills_required', $job->skills_required) }}" class="w-full px-3 py-2 border rounded"></div>
                <div class="col-span-2"><label class="block text-sm font-medium mb-2">Description</label>
                    <textarea name="description" rows="5" class="w-full px-3 py-2 border rounded" required>{{ old('description', $job->description) }}</textarea></div>
                <div class="col-span-2"><label class="block text-sm font-medium mb-2">Responsibilities</label>
                    <textarea name="responsibilities" rows="4" class="w-full px-3 py-2 border rounded">{{ old('responsibilities', $job->responsibilities) }}</textarea></div>
                <div class="col-span-2"><label class="block text-sm font-medium mb-2">Requirements</label>
                    <textarea name="requirements" rows="4" class="w-full px-3 py-2 border rounded">{{ old('requirements', $job->requirements) }}</textarea></div>
                <div class="col-span-2"><label class="block text-sm font-medium mb-2">Benefits</label>
                    <textarea name="benefits" rows="4" class="w-full px-3 py-2 border rounded">{{ old('benefits', $job->benefits) }}</textarea></div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('employer.jobs.index') }}" class="px-4 py-2 border rounded">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded">Update Job</button>
            </div>
        </form>
    </div>
</div>
@endsection
