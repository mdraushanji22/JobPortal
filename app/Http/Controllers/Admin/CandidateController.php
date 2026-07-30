<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::with('user')->paginate(10);
        return view('admin.candidates.index', compact('candidates'));
    }

    public function edit(Candidate $candidate)
    {
        return view('admin.candidates.edit', compact('candidate'));
    }

    public function update(Request $request, Candidate $candidate)
    {
        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'skills' => 'nullable|string',
            'education' => 'nullable|string',
            'experience' => 'nullable|string',
        ]);

        $candidate->update($validated);

        return redirect()->route('admin.candidates.index')->with('success', 'Candidate updated successfully.');
    }

    public function destroy(Candidate $candidate)
    {
        $candidate->user()->delete();
        return redirect()->route('admin.candidates.index')->with('success', 'Candidate deleted successfully.');
    }

    public function show(Candidate $candidate)
    {
        return view('admin.candidates.show', compact('candidate'));
    }
}
