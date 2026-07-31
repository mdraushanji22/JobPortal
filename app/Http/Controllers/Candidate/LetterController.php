<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use App\Services\LetterPdfService;
use Illuminate\Support\Facades\Auth;

class LetterController extends Controller
{
    public function __construct(private LetterPdfService $pdfService)
    {
    }

    private function ensureOwnsLetter(Letter $letter): void
    {
        if ($letter->candidate_id !== Auth::user()->candidate->id || $letter->status !== 'sent') {
            abort(403);
        }
    }

    public function index()
    {
        $letters = Letter::with('jobListing', 'employer', 'application')
            ->where('candidate_id', Auth::user()->candidate->id)
            ->where('status', 'sent')
            ->latest()
            ->paginate(10);

        return view('candidate.letters.index', compact('letters'));
    }

    public function show(Letter $letter)
    {
        $this->ensureOwnsLetter($letter);
        $letter->load('candidate.user', 'jobListing', 'employer.user');

        return view('candidate.letters.show', compact('letter'));
    }

    public function pdf(Letter $letter)
    {
        $this->ensureOwnsLetter($letter);

        return $this->pdfService->download($letter);
    }
}
