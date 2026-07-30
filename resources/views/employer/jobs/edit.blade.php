@extends('layouts.employer')

@section('title', 'Edit Job')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-6 dark:text-white">Edit Job</h1>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form action="{{ route('employer.jobs.update', $job) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2"><label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Job Title</label><input type="text" name="title" value="{{ old('title', $job->title) }}" placeholder="e.g. Senior PHP Developer" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400" required></div>
                <div><label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Category</label>
                    <select name="category_id" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id', $job->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Employment Type</label>
                    <select name="employment_type" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400" required>
                        @foreach(['full-time','part-time','contract','internship','freelance'] as $type)
                            <option value="{{ $type }}" {{ old('employment_type', $job->employment_type) == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Workplace Type</label>
                    <select name="workplace_type" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400" required>
                        @foreach(['on-site','remote','hybrid'] as $wt)
                            <option value="{{ $wt }}" {{ old('workplace_type', $job->workplace_type) == $wt ? 'selected' : '' }}>{{ ucfirst($wt) }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Experience Level</label><input type="text" name="experience_level" value="{{ old('experience_level', $job->experience_level) }}" placeholder="e.g. 3-5 years" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400"></div>
                <div><label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Location</label><input type="text" name="location" value="{{ old('location', $job->location) }}" placeholder="e.g. New York, NY" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400"></div>
                <div><label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Salary Min ($)</label><input type="number" name="salary_min" value="{{ old('salary_min', $job->salary_min) }}" placeholder="0" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400" step="0.01"></div>
                <div><label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Salary Max ($)</label><input type="number" name="salary_max" value="{{ old('salary_max', $job->salary_max) }}" placeholder="0" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400" step="0.01"></div>
                <div><label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Salary Type</label>
                    <select name="salary_type" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400">
                        @foreach(['hourly','monthly','yearly'] as $st)<option value="{{ $st }}" {{ old('salary_type', $job->salary_type) == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>@endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Vacancy</label><input type="number" name="vacancy_count" value="{{ old('vacancy_count', $job->vacancy_count) }}" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400" min="1"></div>
                <div><label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Deadline</label><input type="date" name="application_deadline" value="{{ old('application_deadline', $job->application_deadline?->format('Y-m-d')) }}" placeholder="Select deadline" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400"></div>
                <div class="col-span-2"><label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Skills Required</label><input type="text" name="skills_required" value="{{ old('skills_required', $job->skills_required) }}" placeholder="e.g. PHP, Laravel, MySQL" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400"></div>
                <div class="col-span-2"><label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" rows="5" placeholder="Detailed description of the job..." class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400" required>{{ old('description', $job->description) }}</textarea></div>
                <div class="col-span-2"><label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Responsibilities</label>
                    <textarea name="responsibilities" rows="4" placeholder="List key responsibilities..." class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400">{{ old('responsibilities', $job->responsibilities) }}</textarea></div>
                <div class="col-span-2"><label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Requirements</label>
                    <textarea name="requirements" rows="4" placeholder="List requirements..." class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400">{{ old('requirements', $job->requirements) }}</textarea></div>
                <div class="col-span-2"><label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Benefits</label>
                    <textarea name="benefits" rows="4" placeholder="List benefits offered..." class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400">{{ old('benefits', $job->benefits) }}</textarea></div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('employer.jobs.index') }}" class="px-4 py-2 border rounded dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded">Update Job</button>
            </div>
        </form>
    </div>
</div>
@endsection
