<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/jobs', [App\Http\Controllers\JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [App\Http\Controllers\JobController::class, 'show'])->name('jobs.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'employer' => redirect()->route('employer.dashboard'),
            'candidate' => redirect()->route('candidate.dashboard'),
            default => redirect()->route('home'),
        };
    })->name('dashboard');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('/employers', App\Http\Controllers\Admin\EmployerController::class);
        Route::post('/employers/{employer}/verify', [App\Http\Controllers\Admin\EmployerController::class, 'verify'])->name('employers.verify');
        Route::post('/employers/{employer}/suspend', [App\Http\Controllers\Admin\EmployerController::class, 'suspend'])->name('employers.suspend');

        Route::resource('/candidates', App\Http\Controllers\Admin\CandidateController::class);
        Route::get('/candidates/{candidate}/show', [App\Http\Controllers\Admin\CandidateController::class, 'show'])->name('candidates.show');

        Route::get('/jobs', [App\Http\Controllers\Admin\JobController::class, 'index'])->name('jobs.index');
        Route::get('/jobs/{job}/edit', [App\Http\Controllers\Admin\JobController::class, 'edit'])->name('jobs.edit');
        Route::put('/jobs/{job}', [App\Http\Controllers\Admin\JobController::class, 'update'])->name('jobs.update');
        Route::delete('/jobs/{job}', [App\Http\Controllers\Admin\JobController::class, 'destroy'])->name('jobs.destroy');
        Route::post('/jobs/{job}/approve', [App\Http\Controllers\Admin\JobController::class, 'approve'])->name('jobs.approve');
        Route::post('/jobs/{job}/reject', [App\Http\Controllers\Admin\JobController::class, 'reject'])->name('jobs.reject');
        Route::post('/jobs/{job}/featured', [App\Http\Controllers\Admin\JobController::class, 'featured'])->name('jobs.featured');

        Route::get('/applications', [App\Http\Controllers\Admin\ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [App\Http\Controllers\Admin\ApplicationController::class, 'show'])->name('applications.show');

        Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');

        Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    });

    // Employer Routes
    Route::middleware(['role:employer'])->prefix('employer')->name('employer.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Employer\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [App\Http\Controllers\Employer\ProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\Employer\ProfileController::class, 'update'])->name('profile.update');

        Route::get('/jobs', [App\Http\Controllers\Employer\JobController::class, 'index'])->name('jobs.index');
        Route::get('/jobs/create', [App\Http\Controllers\Employer\JobController::class, 'create'])->name('jobs.create');
        Route::post('/jobs', [App\Http\Controllers\Employer\JobController::class, 'store'])->name('jobs.store');
        Route::get('/jobs/{job}/edit', [App\Http\Controllers\Employer\JobController::class, 'edit'])->name('jobs.edit');
        Route::put('/jobs/{job}', [App\Http\Controllers\Employer\JobController::class, 'update'])->name('jobs.update');
        Route::delete('/jobs/{job}', [App\Http\Controllers\Employer\JobController::class, 'destroy'])->name('jobs.destroy');
        Route::post('/jobs/{job}/close', [App\Http\Controllers\Employer\JobController::class, 'close'])->name('jobs.close');
        Route::post('/jobs/{job}/reopen', [App\Http\Controllers\Employer\JobController::class, 'reopen'])->name('jobs.reopen');

        Route::get('/applications', [App\Http\Controllers\Employer\ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [App\Http\Controllers\Employer\ApplicationController::class, 'show'])->name('applications.show');
        Route::post('/applications/{application}/status', [App\Http\Controllers\Employer\ApplicationController::class, 'updateStatus'])->name('applications.status');
        Route::post('/applications/{application}/schedule-interview', [App\Http\Controllers\Employer\ApplicationController::class, 'scheduleInterview'])->name('applications.schedule-interview');

        Route::get('/interviews', [App\Http\Controllers\Employer\InterviewController::class, 'index'])->name('interviews.index');
        Route::post('/interviews/{interview}/status', [App\Http\Controllers\Employer\InterviewController::class, 'updateStatus'])->name('interviews.status');
    });

    // Candidate Routes
    Route::middleware(['role:candidate'])->prefix('candidate')->name('candidate.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Candidate\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [App\Http\Controllers\Candidate\ProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\Candidate\ProfileController::class, 'update'])->name('profile.update');

        Route::get('/jobs', [App\Http\Controllers\Candidate\JobController::class, 'index'])->name('jobs.index');
        Route::get('/jobs/{job}', [App\Http\Controllers\Candidate\JobController::class, 'show'])->name('jobs.show');

        Route::get('/applications', [App\Http\Controllers\Candidate\ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/create/{job}', [App\Http\Controllers\Candidate\ApplicationController::class, 'create'])->name('applications.create');
        Route::post('/applications/{job}', [App\Http\Controllers\Candidate\ApplicationController::class, 'store'])->name('applications.store');
        Route::delete('/applications/{application}/withdraw', [App\Http\Controllers\Candidate\ApplicationController::class, 'withdraw'])->name('applications.withdraw');

        Route::get('/resumes', [App\Http\Controllers\Candidate\ResumeController::class, 'index'])->name('resumes.index');
        Route::post('/resumes', [App\Http\Controllers\Candidate\ResumeController::class, 'store'])->name('resumes.store');
        Route::post('/resumes/{resume}/default', [App\Http\Controllers\Candidate\ResumeController::class, 'setDefault'])->name('resumes.default');
        Route::delete('/resumes/{resume}', [App\Http\Controllers\Candidate\ResumeController::class, 'destroy'])->name('resumes.destroy');

        Route::get('/saved-jobs', [App\Http\Controllers\Candidate\SavedJobController::class, 'index'])->name('saved-jobs.index');
        Route::post('/saved-jobs/{job}/toggle', [App\Http\Controllers\Candidate\SavedJobController::class, 'toggle'])->name('saved-jobs.toggle');
        Route::delete('/saved-jobs/{savedJob}', [App\Http\Controllers\Candidate\SavedJobController::class, 'remove'])->name('saved-jobs.remove');
    });

    // Resume Download (accessible by employer & candidate)
    Route::get('/resumes/{resume}/download', [App\Http\Controllers\Candidate\ResumeController::class, 'download'])->name('candidate.resumes.download');

    // Messaging
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/conversation/{user}', [MessageController::class, 'conversation'])->name('messages.conversation');
    Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');
});

require __DIR__.'/auth.php';
