<?php

namespace Tests\Feature;

use App\Mail\LetterMail;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Employer;
use App\Models\JobListing;
use App\Models\Letter;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LetterTest extends TestCase
{
    use RefreshDatabase;

    private function makeSelectedApplication(): array
    {
        $employerUser = User::factory()->create(['role' => 'employer']);
        $employer = Employer::create(['user_id' => $employerUser->id, 'company_name' => 'Acme Corp', 'address' => '123 Main St']);
        $candidateUser = User::factory()->create(['role' => 'candidate']);
        $candidate = Candidate::create(['user_id' => $candidateUser->id]);
        $otherCandidateUser = User::factory()->create(['role' => 'candidate']);
        $otherCandidate = Candidate::create(['user_id' => $otherCandidateUser->id]);
        $otherEmployerUser = User::factory()->create(['role' => 'employer']);
        $otherEmployer = Employer::create(['user_id' => $otherEmployerUser->id, 'company_name' => 'Rival Inc']);
        $job = JobListing::create([
            'employer_id' => $employer->id,
            'title' => 'Laravel Developer',
            'slug' => 'laravel-developer-' . uniqid(),
            'description' => 'Build things.',
            'employment_type' => 'full-time',
            'location' => 'Remote',
            'status' => 'approved',
            'is_active' => true,
        ]);
        $application = Application::create([
            'job_listing_id' => $job->id,
            'candidate_id' => $candidate->id,
            'status' => 'selected',
        ]);

        return compact(
            'employerUser', 'employer', 'candidateUser', 'candidate',
            'otherCandidateUser', 'otherCandidate', 'otherEmployerUser', 'otherEmployer',
            'job', 'application'
        );
    }

    private function letterData(array $d, string $type = 'offer'): array
    {
        return [
            'application_id' => $d['application']->id,
            'letter_type' => $type,
            'offer_date' => '2026-08-01',
            'joining_date' => '2026-08-15',
            'salary_ctc' => '12,00,000 per annum',
            'designation' => 'Senior Laravel Developer',
            'employment_type' => 'full-time',
            'work_location' => 'Remote',
            'hr_email' => 'hr@acme.com',
        ];
    }

    public function test_employer_can_open_letter_form_for_selected_application(): void
    {
        $d = $this->makeSelectedApplication();

        $this->actingAs($d['employerUser'])
            ->get(route('employer.applications.show', $d['application']))
            ->assertOk()
            ->assertSee('Generate Offer Letter')
            ->assertSee('Generate Joining Letter');

        $response = $this->actingAs($d['employerUser'])
            ->get(route('employer.letters.create', ['application' => $d['application'], 'type' => 'offer']));

        $response->assertOk()->assertSee('Generate Offer Letter');
    }

    public function test_non_selected_application_cannot_generate_letter(): void
    {
        $d = $this->makeSelectedApplication();
        $d['application']->update(['status' => 'shortlisted']);

        $response = $this->actingAs($d['employerUser'])
            ->get(route('employer.letters.create', $d['application']));

        $response->assertRedirect(route('employer.applications.show', $d['application']));
        $this->assertDatabaseCount('letters', 0);
    }

    public function test_employer_can_save_letter_as_draft(): void
    {
        $d = $this->makeSelectedApplication();

        $response = $this->actingAs($d['employerUser'])
            ->post(route('employer.letters.store'), $this->letterData($d) + ['action' => 'draft']);

        $response->assertRedirect();
        $this->assertDatabaseHas('letters', [
            'application_id' => $d['application']->id,
            'employer_id' => $d['employer']->id,
            'candidate_id' => $d['candidate']->id,
            'letter_type' => 'offer',
            'status' => 'draft',
        ]);
    }

    public function test_employer_can_send_letter_creates_pdf_notification_and_mail(): void
    {
        Mail::fake();
        Storage::fake('local');
        $d = $this->makeSelectedApplication();

        $response = $this->actingAs($d['employerUser'])
            ->post(route('employer.letters.store'), $this->letterData($d) + ['action' => 'send']);

        $response->assertRedirect();
        $this->assertDatabaseHas('letters', ['application_id' => $d['application']->id, 'status' => 'sent']);

        $letter = Letter::where('application_id', $d['application']->id)->first();
        $this->assertNotNull($letter->pdf_path);
        Storage::disk('local')->assertExists($letter->pdf_path);
        $this->assertNotNull($letter->sent_at);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $d['candidateUser']->id,
            'type' => 'letter',
            'title' => 'Offer Letter Issued',
        ]);

        Mail::assertSent(LetterMail::class, fn ($mail) => $mail->letter->id === $letter->id);
    }

    public function test_other_employer_cannot_access_letter(): void
    {
        $d = $this->makeSelectedApplication();
        $letter = Letter::create($this->letterData($d) + [
            'employer_id' => $d['employer']->id,
            'candidate_id' => $d['candidate']->id,
            'job_listing_id' => $d['job']->id,
            'status' => 'draft',
        ]);

        $this->actingAs($d['otherEmployerUser'])
            ->get(route('employer.letters.show', $letter))
            ->assertForbidden();
    }

    public function test_employer_can_view_and_edit_letter(): void
    {
        $d = $this->makeSelectedApplication();
        $letter = Letter::create($this->letterData($d) + [
            'employer_id' => $d['employer']->id,
            'candidate_id' => $d['candidate']->id,
            'job_listing_id' => $d['job']->id,
            'status' => 'draft',
        ]);

        $this->actingAs($d['employerUser'])
            ->get(route('employer.letters.show', $letter))
            ->assertOk()
            ->assertSee('Send Letter')
            ->assertSee('Offer Letter');

        $this->actingAs($d['employerUser'])
            ->get(route('employer.letters.edit', $letter))
            ->assertOk()
            ->assertSee('Edit Offer Letter');
    }

    public function test_candidate_sees_only_sent_letters(): void
    {
        $d = $this->makeSelectedApplication();
        Letter::create($this->letterData($d) + [
            'employer_id' => $d['employer']->id,
            'candidate_id' => $d['candidate']->id,
            'job_listing_id' => $d['job']->id,
            'status' => 'draft',
        ]);
        Letter::create($this->letterData($d, 'joining') + [
            'employer_id' => $d['employer']->id,
            'candidate_id' => $d['candidate']->id,
            'job_listing_id' => $d['job']->id,
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $response = $this->actingAs($d['candidateUser'])->get(route('candidate.letters.index'));

        $response->assertOk()->assertSee('Joining Letter')->assertDontSee('Offer Letter');
    }

    public function test_candidate_cannot_view_draft_letter(): void
    {
        $d = $this->makeSelectedApplication();
        $letter = Letter::create($this->letterData($d) + [
            'employer_id' => $d['employer']->id,
            'candidate_id' => $d['candidate']->id,
            'job_listing_id' => $d['job']->id,
            'status' => 'draft',
        ]);

        $this->actingAs($d['candidateUser'])
            ->get(route('candidate.letters.show', $letter))
            ->assertForbidden();
    }

    public function test_candidate_cannot_view_other_candidate_letter(): void
    {
        $d = $this->makeSelectedApplication();
        $letter = Letter::create($this->letterData($d) + [
            'employer_id' => $d['employer']->id,
            'candidate_id' => $d['candidate']->id,
            'job_listing_id' => $d['job']->id,
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $this->actingAs($d['otherCandidateUser'])
            ->get(route('candidate.letters.show', $letter))
            ->assertForbidden();
    }

    public function test_candidate_can_download_sent_letter_pdf(): void
    {
        Storage::fake('local');
        $d = $this->makeSelectedApplication();
        $letter = Letter::create($this->letterData($d) + [
            'employer_id' => $d['employer']->id,
            'candidate_id' => $d['candidate']->id,
            'job_listing_id' => $d['job']->id,
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $response = $this->actingAs($d['candidateUser'])
            ->get(route('candidate.letters.pdf', $letter));

        $response->assertOk();
        $this->assertNotNull($letter->fresh()->pdf_path);
        Storage::disk('local')->assertExists($letter->fresh()->pdf_path);
    }

    public function test_admin_can_view_and_archive_letter(): void
    {
        $d = $this->makeSelectedApplication();
        $letter = Letter::create($this->letterData($d) + [
            'employer_id' => $d['employer']->id,
            'candidate_id' => $d['candidate']->id,
            'job_listing_id' => $d['job']->id,
            'status' => 'sent',
            'sent_at' => now(),
        ]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.letters.show', $letter))->assertOk();
        $this->actingAs($admin)->post(route('admin.letters.archive', $letter))->assertRedirect();

        $this->assertDatabaseHas('letters', ['id' => $letter->id, 'status' => 'archived']);
    }

    public function test_letter_regeneration_updates_pdf(): void
    {
        Storage::fake('local');
        $d = $this->makeSelectedApplication();
        $letter = Letter::create($this->letterData($d) + [
            'employer_id' => $d['employer']->id,
            'candidate_id' => $d['candidate']->id,
            'job_listing_id' => $d['job']->id,
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $this->actingAs($d['employerUser'])
            ->post(route('employer.letters.regenerate', $letter))
            ->assertRedirect();

        $fresh = $letter->fresh();
        Storage::disk('local')->assertExists($fresh->pdf_path);
    }
}
