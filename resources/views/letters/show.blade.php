<style>
    .letter-sheet {
        font-family: 'DejaVu Sans', Arial, sans-serif;
        font-size: 13px;
        line-height: 1.6;
        color: #111827;
        background: #ffffff;
    }
    .letter-sheet .letter-head {
        border-bottom: 3px solid #1d4ed8;
        padding-bottom: 14px;
        margin-bottom: 18px;
    }
    .letter-sheet .letter-logo {
        max-height: 64px;
        max-width: 64px;
    }
    .letter-sheet .letter-company {
        font-size: 18px;
        font-weight: bold;
        color: #1d4ed8;
    }
    .letter-sheet .letter-title {
        text-align: center;
        font-size: 17px;
        font-weight: bold;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #1e3a8a;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 8px;
        margin-bottom: 16px;
    }
    .letter-sheet .letter-meta {
        font-size: 12px;
        color: #374151;
        margin-bottom: 16px;
    }
    .letter-sheet .letter-body {
        margin-bottom: 18px;
        white-space: pre-line;
    }
    .letter-sheet table.letter-details {
        width: 100%;
        border-collapse: collapse;
        margin: 14px 0 18px;
    }
    .letter-sheet table.letter-details td {
        border: 1px solid #d1d5db;
        padding: 6px 10px;
        font-size: 12px;
        vertical-align: top;
    }
    .letter-sheet table.letter-details td.label {
        width: 32%;
        background: #eff6ff;
        font-weight: bold;
        color: #1e3a8a;
    }
    .letter-sheet .letter-terms {
        margin-bottom: 22px;
    }
    .letter-sheet .letter-terms ol {
        margin: 6px 0 0 18px;
        padding: 0;
    }
    .letter-sheet .letter-terms li {
        margin-bottom: 4px;
    }
    .letter-sheet .letter-sign {
        margin-top: 34px;
    }
    .letter-sheet .letter-sign-name {
        font-weight: bold;
    }
    .letter-sheet .letter-footer {
        margin-top: 26px;
        padding-top: 8px;
        border-top: 1px solid #e5e7eb;
        font-size: 11px;
        color: #6b7280;
        text-align: center;
    }
</style>

@php
    $job = $letter->jobListing;
    $emp = $letter->employer;
    $empUser = $emp->user;
    $candidate = $letter->candidate->user;
    $siteName = \App\Models\Setting::getSetting('site_name', config('app.name'));

    $logoData = null;
    if ($emp->logo) {
        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        if ($disk->exists($emp->logo)) {
            $logoData = 'data:' . $disk->mimeType($emp->logo) . ';base64,' . base64_encode($disk->get($emp->logo));
        }
    }

    $designation = $letter->designation ?: $job->title;
    $ref = strtoupper(substr($siteName, 0, 3)) . '-' . $letter->application_id . '-' . strtoupper($letter->letter_type) . '-' . $letter->id;
    $dateLine = $letter->isOffer()
        ? ($letter->offer_date?->format('d F Y') ?? now()->format('d F Y'))
        : ($letter->joining_date?->format('d F Y') ?? now()->format('d F Y'));

    $defaultBody = $letter->isOffer()
        ? "We are delighted to offer you the position of " . $designation . " at " . $emp->company_name . ". Your experience, skills and enthusiasm impressed us throughout the selection process, and we are confident that you will be a valuable addition to our team.\n\nThis letter summarizes the proposed terms and conditions of your employment. Your joining is subject to the successful completion of document verification and background verification."
        : "Following your acceptance of our offer, we are pleased to confirm your joining date and provide the details of your employment at " . $emp->company_name . ".\n\nPlease carry the required documents on your first day of joining, including your educational certificates, previous employment experience letters, identity proof and address proof.";
    $body = trim($letter->content ?: '') ?: $defaultBody;

    $defaultTerms = $letter->isOffer()
        ? "1. Your employment will be on a " . ($letter->employment_type ?: ($job->employment_type ?: 'Full-time')) . " basis with a probation period of 3 (three) months.\n2. This offer is contingent upon successful background verification and submission of relevant documents.\n3. The compensation structure includes all statutory benefits as applicable under the laws of the country.\n4. Any information provided by you during the recruitment process is subject to verification, and misrepresentation will result in termination of employment.\n5. This letter does not constitute an employment contract and is subject to the company's policies."
        : "1. Your reporting time on the first day is 9:30 AM IST, at the designated work location.\n2. Please report to the HR department / " . ($letter->reporting_manager ?: 'your reporting manager') . " on arrival.\n3. This is an offer of employment at will and is subject to the successful completion of the probationary period.\n4. You are required to comply with all company policies, code of conduct and confidentiality obligations.";
    $terms = trim($letter->terms ?: '') ?: $defaultTerms;
@endphp

<div class="letter-sheet">
    <div class="letter-head">
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="width:70px; text-align:left; vertical-align:middle;">
                    @if($logoData)
                        <img src="{{ $logoData }}" alt="Logo" class="letter-logo">
                    @endif
                </td>
                <td style="vertical-align:middle;">
                    <div class="letter-company">{{ $emp->company_name }}</div>
                    <div style="font-size:11px; color:#4b5563;">
                        {{ $emp->address }}
                        @if($emp->phone)
                            <br>Phone: {{ $emp->phone }}
                        @endif
                        @if($emp->website)
                            <br>{{ $emp->website }}
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="letter-title">{{ $letter->isOffer() ? 'Offer Letter' : 'Joining Letter' }}</div>

    <div class="letter-meta">
        <div>Ref: <strong>{{ $ref }}</strong></div>
        <div>Date: <strong>{{ $dateLine }}</strong></div>
    </div>

    <div class="letter-body">
        <div>To,</div>
        <div><strong>{{ $candidate->name }}</strong></div>
        <div>{{ $designation }}, {{ $emp->company_name }}</div>
    </div>

    <div class="letter-body">Dear {{ strtok($candidate->name, ' ') }},</div>

    <div class="letter-body">{{ $body }}</div>

    <table class="letter-details">
        <tr><td class="label">Position / Designation</td><td>{{ $designation }}</td></tr>
        @if($letter->department)<tr><td class="label">Department</td><td>{{ $letter->department }}</td></tr>@endif
        <tr><td class="label">Employment Type</td><td>{{ $letter->employment_type ?: $job->employment_type }}</td></tr>
        <tr><td class="label">Work Location</td><td>{{ $letter->work_location ?: $job->location }}</td></tr>
        @if($letter->isOffer() && $letter->offer_date)<tr><td class="label">Offer Date</td><td>{{ $letter->offer_date->format('d F Y') }}</td></tr>@endif
        @if($letter->isOffer() && $letter->salary_ctc)<tr><td class="label">Salary / CTC</td><td>{{ $letter->salary_ctc }}</td></tr>@endif
        @if($letter->joining_date)<tr><td class="label">Joining Date</td><td>{{ $letter->joining_date->format('d F Y') }}</td></tr>@endif
        @if($letter->reporting_manager)<tr><td class="label">Reporting To</td><td>{{ $letter->reporting_manager }}</td></tr>@endif
        <tr><td class="label">HR Contact</td><td>{{ $letter->hr_name ?: 'HR Department' }}{{ $letter->hr_email ? ' | ' . $letter->hr_email : '' }}{{ $letter->hr_phone ? ' | ' . $letter->hr_phone : '' }}</td></tr>
    </table>

    @if($letter->isOffer())
        <div class="letter-body">We look forward to welcoming you to {{ $emp->company_name }} and trust that you will find your association with us professionally rewarding.</div>
    @else
        <div class="letter-body">We look forward to seeing you on {{ $letter->joining_date?->format('d F Y') ?? 'your joining date' }} and wish you a successful career with {{ $emp->company_name }}.</div>
    @endif

    <div class="letter-terms">
        <strong>Terms &amp; Conditions:</strong>
        <div>{!! nl2br(e($terms)) !!}</div>
    </div>

    <div class="letter-sign">
        <div>Sincerely,</div>
        <br><br>
        <div class="letter-sign-name">{{ $letter->signature_name ?: $empUser->name }}</div>
        <div>{{ $empUser->name }}</div>
        <div>{{ $emp->company_name }}</div>
    </div>

    <div class="letter-footer">
        {{ $emp->company_name }} &middot; {{ $emp->address }}
        @if($emp->phone) &middot; {{ $emp->phone }} @endif
        @if($emp->website) &middot; {{ $emp->website }} @endif
    </div>
</div>
