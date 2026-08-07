<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\Employer;
use App\Models\JobListing;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function applicationWithResume(): array
    {
        $employerUser = User::factory()->create(['role' => 'employer']);
        $employer = Employer::create(['user_id' => $employerUser->id, 'company_name' => 'Hiring Co']);
        $candidateUser = User::factory()->create(['role' => 'candidate']);
        $candidate = Candidate::create(['user_id' => $candidateUser->id]);
        $job = JobListing::create([
            'employer_id' => $employer->id,
            'title' => 'Developer',
            'slug' => 'developer-' . uniqid(),
            'description' => 'A role.',
            'employment_type' => 'full-time',
            'status' => 'approved',
            'is_active' => true,
        ]);
        $resume = Resume::create([
            'candidate_id' => $candidate->id,
            'title' => 'My Resume',
            'file_path' => 'resumes/my-resume.pdf',
            'file_type' => 'pdf',
        ]);
        Application::create([
            'job_listing_id' => $job->id,
            'candidate_id' => $candidate->id,
            'resume_id' => $resume->id,
            'status' => 'applied',
        ]);

        return compact('employerUser', 'candidateUser', 'candidate', 'job', 'resume');
    }

    public function test_only_the_applicant_and_the_relevant_employer_can_download_a_resume(): void
    {
        Storage::fake('public');
        $data = $this->applicationWithResume();
        Storage::disk('public')->put($data['resume']->file_path, 'resume');

        $otherEmployerUser = User::factory()->create(['role' => 'employer']);
        Employer::create(['user_id' => $otherEmployerUser->id, 'company_name' => 'Other Co']);

        $this->actingAs($data['candidateUser'])
            ->get(route('candidate.resumes.download', $data['resume']))
            ->assertOk();
        $this->actingAs($data['employerUser'])
            ->get(route('candidate.resumes.download', $data['resume']))
            ->assertOk();
        $this->actingAs($otherEmployerUser)
            ->get(route('candidate.resumes.download', $data['resume']))
            ->assertForbidden();
    }

    public function test_candidate_cannot_submit_someone_elses_resume(): void
    {
        $data = $this->applicationWithResume();
        $otherCandidateUser = User::factory()->create(['role' => 'candidate']);
        $otherCandidate = Candidate::create(['user_id' => $otherCandidateUser->id]);

        $this->actingAs($otherCandidateUser)
            ->post(route('candidate.applications.store', $data['job']), ['resume_id' => $data['resume']->id])
            ->assertForbidden();

        $this->assertDatabaseCount('applications', 1);
        $this->assertNotNull($otherCandidate);
    }

    public function test_candidates_cannot_apply_to_closed_jobs(): void
    {
        $data = $this->applicationWithResume();
        $data['job']->update(['status' => 'closed', 'is_active' => false]);

        $this->actingAs($data['candidateUser'])
            ->post(route('candidate.applications.store', $data['job']))
            ->assertNotFound();

        $this->assertDatabaseCount('applications', 1);
    }
}
