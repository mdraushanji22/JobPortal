<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\Conversation;
use App\Models\Employer;
use App\Models\JobListing;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MessagingAttachmentTest extends TestCase
{
    use RefreshDatabase;

    private array $d;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');

        $employerUser = User::factory()->create(['role' => 'employer']);
        $employer = Employer::create(['user_id' => $employerUser->id, 'company_name' => 'Acme Corp']);
        $candidateUser = User::factory()->create(['role' => 'candidate']);
        $candidate = Candidate::create(['user_id' => $candidateUser->id]);
        $otherCandidateUser = User::factory()->create(['role' => 'candidate']);
        $otherCandidate = Candidate::create(['user_id' => $otherCandidateUser->id]);
        $job = JobListing::create([
            'employer_id' => $employer->id,
            'title' => 'Laravel Developer',
            'slug' => 'laravel-developer-' . uniqid(),
            'description' => 'Build things.',
            'employment_type' => 'full-time',
            'status' => 'approved',
            'is_active' => true,
        ]);
        $application = Application::create([
            'job_listing_id' => $job->id,
            'candidate_id' => $candidate->id,
            'status' => 'applied',
        ]);

        $this->d = compact('employerUser', 'candidateUser', 'otherCandidateUser', 'application');
        $this->d['conversation'] = Conversation::create(['application_id' => $application->id]);
    }

    public function test_candidate_can_send_image(): void
    {
        $response = $this->actingAs($this->d['candidateUser'])->postJson(route('messages.send'), [
            'conversation_id' => $this->d['conversation']->id,
            'file' => UploadedFile::fake()->image('photo.jpg'),
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $this->d['conversation']->id,
            'sender_id' => $this->d['candidateUser']->id,
            'message_type' => 'image',
            'original_file_name' => 'photo.jpg',
            'mime_type' => 'image/jpeg',
        ]);
        $this->assertNotNull(Message::first()->file_path);
    }

    public function test_employer_can_send_document(): void
    {
        $response = $this->actingAs($this->d['employerUser'])->postJson(route('messages.send'), [
            'conversation_id' => $this->d['conversation']->id,
            'file' => UploadedFile::fake()->create('resume.pdf', 120, 'application/pdf'),
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $this->d['conversation']->id,
            'sender_id' => $this->d['employerUser']->id,
            'message_type' => 'file',
            'original_file_name' => 'resume.pdf',
            'mime_type' => 'application/pdf',
        ]);
        $message = Message::first();
        $this->assertNotNull($message->file_size);
        $this->assertNotNull($message->attachment_url);
        $this->assertNotNull($message->download_url);
    }

    public function test_text_message_has_text_type(): void
    {
        $this->actingAs($this->d['candidateUser'])->postJson(route('messages.send'), [
            'conversation_id' => $this->d['conversation']->id,
            'message' => 'Just a text message',
        ])->assertOk();

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $this->d['conversation']->id,
            'message' => 'Just a text message',
            'message_type' => 'text',
            'file_path' => null,
        ]);
    }

    public function test_dangerous_extension_is_rejected(): void
    {
        foreach (['virus.exe', 'script.php', 'script.js', 'run.bat'] as $name) {
            $response = $this->actingAs($this->d['candidateUser'])->postJson(route('messages.send'), [
                'conversation_id' => $this->d['conversation']->id,
                'file' => UploadedFile::fake()->create($name, 10),
            ]);

            $response->assertStatus(422);
            $this->assertArrayHasKey('error', $response->json());
        }

        $this->assertDatabaseCount('messages', 0);
    }

    public function test_oversized_file_is_rejected(): void
    {
        $response = $this->actingAs($this->d['candidateUser'])->postJson(route('messages.send'), [
            'conversation_id' => $this->d['conversation']->id,
            'file' => UploadedFile::fake()->create('huge.pdf', 11 * 1024, 'application/pdf'),
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('messages', 0);
    }

    public function test_unsupported_type_is_rejected(): void
    {
        $response = $this->actingAs($this->d['candidateUser'])->postJson(route('messages.send'), [
            'conversation_id' => $this->d['conversation']->id,
            'file' => UploadedFile::fake()->create('music.mp3', 100, 'audio/mpeg'),
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('messages', 0);
    }

    public function test_participants_can_preview_and_download(): void
    {
        $message = Message::create([
            'conversation_id' => $this->d['conversation']->id,
            'sender_id' => $this->d['candidateUser']->id,
            'receiver_id' => $this->d['employerUser']->id,
            'message_type' => 'file',
            'file_path' => 'chat-files/resume.pdf',
            'file_type' => 'pdf',
            'original_file_name' => 'resume.pdf',
            'file_size' => 2048,
            'mime_type' => 'application/pdf',
        ]);
        Storage::disk('local')->put('chat-files/resume.pdf', 'fake content');

        $this->actingAs($this->d['employerUser'])
            ->get(route('messages.attachment', $message))
            ->assertOk();
        $this->actingAs($this->d['employerUser'])
            ->get(route('messages.download', $message))
            ->assertOk();
    }

    public function test_non_participant_cannot_preview_or_download(): void
    {
        $message = Message::create([
            'conversation_id' => $this->d['conversation']->id,
            'sender_id' => $this->d['candidateUser']->id,
            'receiver_id' => $this->d['employerUser']->id,
            'message_type' => 'image',
            'file_path' => 'chat-files/photo.jpg',
            'file_type' => 'jpg',
            'original_file_name' => 'photo.jpg',
            'file_size' => 1024,
            'mime_type' => 'image/jpeg',
        ]);
        Storage::disk('local')->put('chat-files/photo.jpg', 'fake image');

        $this->actingAs($this->d['otherCandidateUser'])
            ->get(route('messages.attachment', $message))
            ->assertForbidden();
        $this->actingAs($this->d['otherCandidateUser'])
            ->get(route('messages.download', $message))
            ->assertForbidden();
    }

    public function test_missing_file_returns_404(): void
    {
        $message = Message::create([
            'conversation_id' => $this->d['conversation']->id,
            'sender_id' => $this->d['candidateUser']->id,
            'receiver_id' => $this->d['employerUser']->id,
            'message_type' => 'file',
            'file_path' => 'chat-files/missing.pdf',
            'file_type' => 'pdf',
            'original_file_name' => 'missing.pdf',
            'file_size' => 1024,
            'mime_type' => 'application/pdf',
        ]);

        $this->actingAs($this->d['employerUser'])
            ->get(route('messages.download', $message))
            ->assertNotFound();
    }
}
