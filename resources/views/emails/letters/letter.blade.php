<x-mail::message>
# {{ $letter->isOffer() ? 'Offer Letter' : 'Joining Letter' }} from {{ $letter->employer->company_name }}

Dear {{ $letter->candidate->user->name }},

Congratulations! {{ $letter->employer->company_name }} has issued your {{ strtolower($letter->letter_type) }} letter for the position of **{{ $letter->designation ?: $letter->jobListing->title }}**.

You can view and download your letter from your dashboard:

<x-mail::button :url="route('candidate.letters.show', $letter)">
    View {{ $letter->isOffer() ? 'Offer' : 'Joining' }} Letter
</x-mail::button>

If you have any questions, please contact our HR team at {{ $letter->hr_email ?: $letter->employer->phone ?: 'the company' }}.

Regards,<br>
{{ $letter->employer->company_name }}
</x-mail::message>
