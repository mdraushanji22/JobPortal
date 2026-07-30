<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employer;
use App\Models\User;
use Illuminate\Http\Request;

class EmployerController extends Controller
{
    public function index()
    {
        $employers = Employer::with('user')->paginate(10);
        return view('admin.employers.index', compact('employers'));
    }

    public function create()
    {
        return view('admin.employers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'name' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'employer',
        ]);

        $user->employer()->create([
            'company_name' => $validated['company_name'],
            'industry' => $validated['industry'],
            'website' => $validated['website'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'description' => $validated['description'],
        ]);

        return redirect()->route('admin.employers.index')->with('success', 'Employer created successfully.');
    }

    public function edit(Employer $employer)
    {
        return view('admin.employers.edit', compact('employer'));
    }

    public function update(Request $request, Employer $employer)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $employer->update($validated);

        return redirect()->route('admin.employers.index')->with('success', 'Employer updated successfully.');
    }

    public function destroy(Employer $employer)
    {
        $employer->user()->delete();
        return redirect()->route('admin.employers.index')->with('success', 'Employer deleted successfully.');
    }

    public function verify(Employer $employer)
    {
        $employer->update(['is_verified' => !$employer->is_verified]);
        return redirect()->route('admin.employers.index')->with('success', 'Employer verification status updated.');
    }

    public function suspend(Employer $employer)
    {
        $employer->update(['is_suspended' => !$employer->is_suspended]);
        return redirect()->route('admin.employers.index')->with('success', 'Employer suspension status updated.');
    }
}
