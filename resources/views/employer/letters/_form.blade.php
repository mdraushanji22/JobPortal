@php
    $values = $letter ?? null;
    $job = isset($application) ? $application->jobListing : $letter->jobListing;
    $emp = $job->employer;

    $salary = '';
    if ($job && $job->salary_min !== null) {
        $salary = $job->salary_min;
        if ($job->salary_max !== null) {
            $salary .= ' - ' . $job->salary_max;
        }
        if ($job->salary_type) {
            $salary .= ' (' . $job->salary_type . ')';
        }
    }

    $f = function ($name, $fallback = '') use ($values) {
        if (old($name) !== null) return old($name);
        if ($values && $values->$name !== null && $values->$name !== '') return $values->$name;
        return $fallback;
    };
    $fd = function ($name, $fallback = '') use ($values) {
        if (old($name) !== null) return old($name);
        if ($values && $values->$name) return $values->$name->format('Y-m-d');
        return $fallback;
    };
@endphp

<form action="{{ $action }}" method="POST">
    @csrf
    @if($method == 'put') @method('PUT') @endif
    <input type="hidden" name="application_id" value="{{ $application ? $application->id : $letter->application_id }}">
    <input type="hidden" name="letter_type" value="{{ $letter ? $letter->letter_type : $type }}">

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4 dark:text-white">Candidate &amp; Job Summary</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Candidate</label>
                <input type="text" value="{{ $application ? $application->candidate->user->name : $letter->candidate->user->name }}" disabled class="w-full px-3 py-2 border rounded bg-gray-50 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Job Title</label>
                <input type="text" value="{{ $job->title }}" disabled class="w-full px-3 py-2 border rounded bg-gray-50 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Company</label>
                <input type="text" value="{{ $emp->company_name }}" disabled class="w-full px-3 py-2 border rounded bg-gray-50 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4 dark:text-white">Letter Details</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Offer Date</label>
                <input type="date" name="offer_date" value="{{ $fd('offer_date', $letter ? '' : now()->format('Y-m-d')) }}" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Joining Date</label>
                <input type="date" name="joining_date" value="{{ $fd('joining_date') }}" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Designation</label>
                <input type="text" name="designation" value="{{ $f('designation', $job->title) }}" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Department</label>
                <input type="text" name="department" value="{{ $f('department') }}" placeholder="e.g. Engineering" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Employment Type</label>
                <input type="text" name="employment_type" value="{{ $f('employment_type', $job->employment_type) }}" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Work Location</label>
                <input type="text" name="work_location" value="{{ $f('work_location', $job->location) }}" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Salary / CTC</label>
                <input type="text" name="salary_ctc" value="{{ $f('salary_ctc', $salary) }}" placeholder="e.g. 12,00,000 per annum" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Reporting Manager</label>
                <input type="text" name="reporting_manager" value="{{ $f('reporting_manager') }}" placeholder="Manager name" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">HR Contact Name</label>
                <input type="text" name="hr_name" value="{{ $f('hr_name', $emp->user->name) }}" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">HR Contact Email</label>
                <input type="email" name="hr_email" value="{{ $f('hr_email', $emp->user->email) }}" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">HR Contact Phone</label>
                <input type="text" name="hr_phone" value="{{ $f('hr_phone', $emp->phone) }}" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Authorized Signature Name</label>
                <input type="text" name="signature_name" value="{{ $f('signature_name', $emp->user->name) }}" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-2 dark:text-white">Letter Body</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Leave blank to use the default {{ $letter ? $letter->letter_type : $type }} letter text.</p>
        <textarea name="content" rows="7" placeholder="Dear ...," class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400">{{ $f('content') }}</textarea>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-2 dark:text-white">Terms &amp; Conditions</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Leave blank to use the default terms for the {{ $letter ? $letter->letter_type : $type }} letter.</p>
        <textarea name="terms" rows="6" placeholder="1. ..." class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400">{{ $f('terms') }}</textarea>
    </div>

    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-300 rounded p-4 mb-6">
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="flex space-x-3">
        <button type="submit" name="action" value="draft" class="px-6 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Save as Draft</button>
        <button type="submit" name="action" value="send" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"><i class="fas fa-paper-plane mr-1"></i>Save &amp; Send</button>
        <a href="{{ $cancel }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Cancel</a>
    </div>
</form>
