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
use Tests\TestCase;

class MessagingConversationTest extends TestCase
{
    use RefreshDatabase;

    private function makeJob(): array
    {
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

        return compact('employerUser', 'employer', 'candidateUser', 'candidate', 'otherCandidateUser', 'otherCandidate', 'job', 'application');
    }

    public function test_candidate_can_open_conversation_and_redirects(): void
    {
        $d = $this->makeJob();

        $response = $this->actingAs($d['candidateUser'])->get(route('messages.open', $d['application']));

        $response->assertRedirect();
        $this->assertDatabaseHas('conversations', ['application_id' => $d['application']->id]);
        $conversation = Conversation::where('application_id', $d['application']->id)->first();
        $this->assertNotNull($conversation);
    }

    public function test_non_applicant_candidate_cannot_open_conversation(): void
    {
        $d = $this->makeJob();

        $response = $this->actingAs($d['otherCandidateUser'])->get(route('messages.open', $d['application']));

        $response->assertForbidden();
        $this->assertDatabaseCount('conversations', 0);
    }

    public function test_employer_can_open_conversation(): void
    {
        $d = $this->makeJob();

        $response = $this->actingAs($d['employerUser'])->get(route('messages.open', $d['application']));

        $response->assertRedirect();
        $this->assertDatabaseCount('conversations', 1);
    }

    public function test_send_and_conversation_fetch_flow(): void
    {
        $d = $this->makeJob();
        $conversation = Conversation::create(['application_id' => $d['application']->id]);

        $send = $this->actingAs($d['candidateUser'])->postJson(route('messages.send'), [
            'conversation_id' => $conversation->id,
            'message' => 'Hello there',
        ]);

        $send->assertOk();
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $d['candidateUser']->id,
            'receiver_id' => $d['employerUser']->id,
            'message' => 'Hello there',
        ]);

        $fetch = $this->actingAs($d['employerUser'])->getJson(route('messages.conversation', $conversation));
        $fetch->assertOk()
            ->assertJsonPath('conversation.job_title', 'Laravel Developer')
            ->assertJsonPath('messages.0.message', 'Hello there')
            ->assertJsonPath('conversation.other_user.email', $d['candidateUser']->email);

        $this->assertDatabaseHas('messages', ['message' => 'Hello there', 'is_read' => true]);
    }

    public function test_non_participant_cannot_fetch_or_send(): void
    {
        $d = $this->makeJob();
        $conversation = Conversation::create(['application_id' => $d['application']->id]);

        $this->actingAs($d['otherCandidateUser'])
            ->getJson(route('messages.conversation', $conversation))
            ->assertForbidden();

        $this->actingAs($d['otherCandidateUser'])
            ->postJson(route('messages.send'), [
                'conversation_id' => $conversation->id,
                'message' => 'Hijack',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('messages', 0);
    }

    public function test_duplicate_message_is_prevented(): void
    {
        $d = $this->makeJob();
        $conversation = Conversation::create(['application_id' => $d['application']->id]);

        $this->actingAs($d['candidateUser'])->postJson(route('messages.send'), [
            'conversation_id' => $conversation->id,
            'message' => 'Duplicate',
        ])->assertOk();

        $this->actingAs($d['candidateUser'])->postJson(route('messages.send'), [
            'conversation_id' => $conversation->id,
            'message' => 'Duplicate',
        ])->assertOk();

        $this->assertEquals(1, Message::where('message', 'Duplicate')->count());
    }

    public function test_index_lists_only_relevant_conversations(): void
    {
        $d = $this->makeJob();
        Conversation::create(['application_id' => $d['application']->id]);

        $employerView = $this->actingAs($d['employerUser'])->get(route('messages.index'));
        $employerView->assertOk()->assertSee('Laravel Developer');

        $candidateView = $this->actingAs($d['candidateUser'])->get(route('messages.index'));
        $candidateView->assertOk()->assertSee('Laravel Developer');

        $otherView = $this->actingAs($d['otherCandidateUser'])->get(route('messages.index'));
        $otherView->assertOk()->assertSee('No conversations yet.');
    }
}
