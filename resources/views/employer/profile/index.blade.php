@extends('layouts.employer')

@section('title', 'Company Profile')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Company Profile</h1>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form action="{{ route('employer.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="flex items-center mb-6">
                @if($employer->logo)
                    <img src="{{ asset('storage/' . $employer->logo) }}" class="w-24 h-24 rounded-lg mr-4">
                @else
                    <div class="w-24 h-24 bg-gray-200 dark:bg-gray-700 rounded-lg mr-4 flex items-center justify-center"><i class="fas fa-building text-3xl text-gray-400"></i></div>
                @endif
                <div><label class="block text-sm font-medium mb-2">Company Logo</label><input type="file" name="logo" accept="image/*" class="text-sm"></div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium mb-2">Company Name</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $employer->company_name) }}" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div><label class="block text-sm font-medium mb-2">Industry</label>
                    <input type="text" name="industry" value="{{ old('industry', $employer->industry) }}" class="w-full px-3 py-2 border rounded"></div>
                <div><label class="block text-sm font-medium mb-2">Website</label>
                    <input type="url" name="website" value="{{ old('website', $employer->website) }}" class="w-full px-3 py-2 border rounded"></div>
                <div><label class="block text-sm font-medium mb-2">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $employer->phone) }}" class="w-full px-3 py-2 border rounded"></div>
                <div class="col-span-2"><label class="block text-sm font-medium mb-2">Address</label>
                    <textarea name="address" rows="2" class="w-full px-3 py-2 border rounded">{{ old('address', $employer->address) }}</textarea></div>
                <div class="col-span-2"><label class="block text-sm font-medium mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full px-3 py-2 border rounded">{{ old('description', $employer->description) }}</textarea></div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update Profile</button>
            </div>
        </form>
    </div>
</div>
@endsection
