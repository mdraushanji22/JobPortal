<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $employer = Auth::user()->employer;
        return view('employer.profile.index', compact('employer'));
    }

    public function update(Request $request)
    {
        $employer = Auth::user()->employer;
        
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            $request->validate(['logo' => 'image|mimes:jpeg,png,jpg|max:2048']);
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $employer->update($validated);

        return redirect()->route('employer.profile')->with('success', 'Profile updated successfully.');
    }
}
