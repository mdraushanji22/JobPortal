<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $candidate = Auth::user()->candidate;
        return view('candidate.profile.index', compact('candidate'));
    }

    public function update(Request $request)
    {
        $candidate = Auth::user()->candidate;
        
        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'skills' => 'nullable|string',
            'education' => 'nullable|string',
            'experience' => 'nullable|string',
            'portfolio_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'bio' => 'nullable|string',
        ]);

        if ($request->hasFile('profile_picture')) {
            $request->validate(['profile_picture' => 'image|mimes:jpeg,png,jpg|max:2048']);
            $validated['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        }

        $candidate->update($validated);

        $profileCompletion = $this->calculateProfileCompletion($candidate);

        return redirect()->route('candidate.profile')->with('success', 'Profile updated successfully. Completion: ' . $profileCompletion . '%');
    }

    private function calculateProfileCompletion($candidate)
    {
        $fields = ['phone', 'skills', 'education', 'experience', 'bio', 'profile_picture'];
        $filled = 0;
        foreach ($fields as $field) {
            if (!empty($candidate->$field)) $filled++;
        }
        return round(($filled / count($fields)) * 100);
    }
}
