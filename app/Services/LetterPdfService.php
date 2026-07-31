<?php

namespace App\Services;

use App\Models\Letter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class LetterPdfService
{
    public function render(Letter $letter): string
    {
        return Pdf::loadView('letters.pdf', ['letter' => $letter])
            ->setPaper('a4')
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isRemoteEnabled', false)
            ->output();
    }

    public function generate(Letter $letter): Letter
    {
        $contents = $this->render($letter);

        $filename = 'letter_' . $letter->letter_type . '_' . $letter->application_id . '_' . $letter->id . '_' . now()->format('YmdHis') . '.pdf';

        if ($letter->pdf_path) {
            Storage::disk('local')->delete($letter->pdf_path);
        }

        $path = Storage::disk('local')->put('letters/' . $filename, $contents);

        $letter->update(['pdf_path' => 'letters/' . $filename]);

        return $letter;
    }

    public function download(Letter $letter)
    {
        $this->generate($letter);

        return Storage::disk('local')->download($letter->pdf_path, $this->downloadName($letter));
    }

    public function downloadName(Letter $letter): string
    {
        $candidateName = str_replace(' ', '_', $letter->candidate->user->name);
        $jobTitle = str_replace(' ', '_', $letter->jobListing->title);

        return strtoupper($letter->letter_type) . '_Letter_' . $candidateName . '_' . $jobTitle . '.pdf';
    }
}
