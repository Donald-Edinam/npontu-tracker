<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class AuthButtonLoadingStateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Assert a submit button reports in-flight state for the given Livewire action.
     */
    private function assertShowsLoadingState(TestResponse $response, string $action, string $busyLabel): void
    {
        $response->assertOk();

        $response->assertSee('wire:loading.attr="disabled" wire:target="'.$action.'"', false);
        $response->assertSee('<span wire:loading wire:target="'.$action.'" class="inline-block animate-spin', false);
        $response->assertSee('<span wire:loading.remove wire:target="'.$action.'">', false);
        $response->assertSee($busyLabel, false);
    }

    public function test_login_button_shows_loading_state(): void
    {
        $this->assertShowsLoadingState($this->get('/login'), 'login', 'Logging in...');
    }

    public function test_register_button_shows_loading_state(): void
    {
        $this->assertShowsLoadingState($this->get('/register'), 'register', 'Registering...');
    }

    public function test_forgot_password_button_shows_loading_state(): void
    {
        $this->assertShowsLoadingState($this->get('/forgot-password'), 'sendPasswordResetLink', 'Sending...');
    }

    public function test_reset_password_button_shows_loading_state(): void
    {
        $response = $this->get(route('password.reset', ['token' => 'test-token']));

        $this->assertShowsLoadingState($response, 'resetPassword', 'Resetting...');
    }

    public function test_confirm_password_button_shows_loading_state(): void
    {
        $response = $this->actingAs(User::factory()->create())->get('/confirm-password');

        $this->assertShowsLoadingState($response, 'confirmPassword', 'Confirming...');
    }

    public function test_verify_email_button_shows_loading_state(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/verify-email');

        $this->assertShowsLoadingState($response, 'sendVerification', 'Sending...');
    }
}
