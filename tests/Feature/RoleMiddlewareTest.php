<?php

namespace Tests\Feature;

use App\Models\Employer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_is_redirected_to_their_own_dashboard_when_opening_another_role_dashboard(): void
    {
        $employerUser = User::factory()->create(['role' => 'employer']);
        Employer::create(['user_id' => $employerUser->id, 'company_name' => 'Acme']);

        $this->actingAs($employerUser)
            ->get(route('candidate.dashboard'))
            ->assertRedirect(route('employer.dashboard'));
    }
}
