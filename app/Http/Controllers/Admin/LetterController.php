<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use App\Services\LetterPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LetterController extends Controller
{
    public function __construct(private LetterPdfService $pdfService)
    {
    }

    public function index(Request $request)
    {
        $letters = Letter::with('employer', 'candidate.user', 'jobListing')
            ->when($request->input('type'), function ($q, $type) {
                $q->where('letter_type', $type);
            })
            ->when($request->input('status'), function ($q, $status) {
                $q->where('status', $status);
            })
            ->when($request->input('q'), function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->whereHas('candidate.user', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    })->orWhereHas('employer', function ($eq) use ($search) {
                        $eq->where('company_name', 'like', "%{$search}%");
                    })->orWhereHas('jobListing', function ($jq) use ($search) {
                        $jq->where('title', 'like', "%{$search}%");
                    });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.letters.index', compact('letters'));
    }

    public function show(Letter $letter)
    {
        $letter->load('candidate.user', 'jobListing', 'employer.user');

        return view('admin.letters.show', compact('letter'));
    }

    public function pdf(Letter $letter)
    {
        return $this->pdfService->download($letter);
    }

    public function archive(Letter $letter)
    {
        $letter->update(['status' => 'archived']);

        return redirect()->route('admin.letters.index')
            ->with('success', ucfirst($letter->letter_type) . ' letter archived.');
    }

    public function destroy(Letter $letter)
    {
        if ($letter->pdf_path) {
            Storage::disk('local')->delete($letter->pdf_path);
        }

        $letter->delete();

        return redirect()->route('admin.letters.index')
            ->with('success', ucfirst($letter->letter_type) . ' letter deleted.');
    }
}
