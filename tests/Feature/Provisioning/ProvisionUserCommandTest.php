<?php

declare(strict_types=1);

namespace Tests\Feature\Provisioning;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProvisionUserCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        User::query()->delete();
    }

    public function test_valid_inputs_create_active_user_with_provision_metadata(): void
    {
        $this->artisan('ucof:provision-user', [
            '--name' => 'Jane Doe',
            '--email' => 'jane@example.test',
            '--password' => 'ValidPassword123',
        ])
            ->expectsOutputToContain('User jane@example.test provisioned successfully.')
            ->assertExitCode(0);

        $user = User::query()->where('email', 'jane@example.test')->firstOrFail();

        $this->assertSame('active', $user->status);
        $this->assertNotNull($user->provisioned_at);
        $this->assertNotEmpty($user->provisioned_from);
        $this->assertTrue(Hash::check('ValidPassword123', (string) $user->password));
    }

    public function test_missing_required_argument_exits_with_safe_message(): void
    {
        $this->artisan('ucof:provision-user', [
            '--email' => 'jane@example.test',
            '--password' => 'ValidPassword123',
        ])
            ->expectsOutputToContain('The name field is required.')
            ->assertExitCode(1);
    }

    public function test_invalid_email_format_exits_with_validation_message(): void
    {
        $this->artisan('ucof:provision-user', [
            '--name' => 'Jane Doe',
            '--email' => 'not-an-email',
            '--password' => 'ValidPassword123',
        ])
            ->expectsOutputToContain('The email field must be a valid email address.')
            ->assertExitCode(1);
    }

    public function test_weak_password_exits_with_policy_guidance_message(): void
    {
        $this->artisan('ucof:provision-user', [
            '--name' => 'Jane Doe',
            '--email' => 'jane@example.test',
            '--password' => 'weak-pass',
        ])
            ->expectsOutputToContain('Password must be at least 12 characters long.')
            ->assertExitCode(1);
    }

    public function test_active_duplicate_email_exits_with_safe_message_and_no_modification(): void
    {
        $existing = new User();
        $existing->fill([
            'name' => 'Jane Doe',
            'email' => 'duplicate@example.test',
            'password' => Hash::make('ValidPassword123'),
            'status' => 'active',
            'failed_signin_attempts' => 2,
        ]);
        $existing->save();

        $originalPasswordHash = (string) $existing->password;
        $originalUpdatedAt = $existing->updated_at;

        $this->artisan('ucof:provision-user', [
            '--name' => 'Jane Updated',
            '--email' => 'duplicate@example.test',
            '--password' => 'AnotherValidPassword123',
        ])
            ->expectsOutputToContain('An active account already exists for duplicate@example.test.')
            ->assertExitCode(1);

        $reloaded = User::query()->where('email', 'duplicate@example.test')->firstOrFail();

        $this->assertSame($originalPasswordHash, (string) $reloaded->password);
        $this->assertSame('active', $reloaded->status);
        $this->assertEquals($originalUpdatedAt, $reloaded->updated_at);
    }

    public function test_locked_duplicate_email_is_rejected(): void
    {
        $existing = new User();
        $existing->fill([
            'name' => 'Jane Doe',
            'email' => 'locked@example.test',
            'password' => Hash::make('ValidPassword123'),
            'status' => 'locked',
            'failed_signin_attempts' => 5,
        ]);
        $existing->save();

        $this->artisan('ucof:provision-user', [
            '--name' => 'Jane Updated',
            '--email' => 'locked@example.test',
            '--password' => 'AnotherValidPassword123',
        ])
            ->expectsOutputToContain('An active account already exists for locked@example.test.')
            ->assertExitCode(1);
    }

    public function test_inactive_user_is_reactivated_and_password_updated(): void
    {
        $existing = new User();
        $existing->fill([
            'name' => 'Jane Doe',
            'email' => 'inactive@example.test',
            'password' => Hash::make('OldValidPassword123'),
            'status' => 'inactive',
            'failed_signin_attempts' => 3,
        ]);
        $existing->save();

        $this->artisan('ucof:provision-user', [
            '--name' => 'Jane Reactivated',
            '--email' => 'inactive@example.test',
            '--password' => 'NewValidPassword456',
        ])
            ->expectsOutputToContain('User inactive@example.test reactivated and password updated.')
            ->assertExitCode(0);

        $reloaded = User::query()->where('email', 'inactive@example.test')->firstOrFail();

        $this->assertSame('active', $reloaded->status);
        $this->assertSame('Jane Reactivated', $reloaded->name);
        $this->assertTrue(Hash::check('NewValidPassword456', (string) $reloaded->password));
        $this->assertNotNull($reloaded->provisioned_at);
        $this->assertNotEmpty($reloaded->provisioned_from);
    }

    public function test_disabled_user_is_reactivated_and_password_updated(): void
    {
        $existing = new User();
        $existing->fill([
            'name' => 'Jane Doe',
            'email' => 'disabled@example.test',
            'password' => Hash::make('OldValidPassword123'),
            'status' => 'disabled',
            'failed_signin_attempts' => 4,
        ]);
        $existing->save();

        $this->artisan('ucof:provision-user', [
            '--name' => 'Jane Reactivated',
            '--email' => 'disabled@example.test',
            '--password' => 'NewValidPassword456',
        ])
            ->expectsOutputToContain('User disabled@example.test reactivated and password updated.')
            ->assertExitCode(0);

        $reloaded = User::query()->where('email', 'disabled@example.test')->firstOrFail();

        $this->assertSame('active', $reloaded->status);
        $this->assertTrue(Hash::check('NewValidPassword456', (string) $reloaded->password));
    }

    public function test_container_invoked_command_path_succeeds_with_valid_cli_args(): void
    {
        $this->artisan('ucof:provision-user', [
            '--name' => 'Container User',
            '--email' => 'container@example.test',
            '--password' => 'ValidPassword123',
        ])
            ->expectsOutputToContain('User container@example.test provisioned successfully.')
            ->assertExitCode(0);
    }

    public function test_no_command_output_line_contains_supplied_password_string(): void
    {
        $password = 'NoLeakPassword123';

        $exitCode = Artisan::call('ucof:provision-user', [
            '--name' => 'No Leak',
            '--email' => 'noleak@example.test',
            '--password' => $password,
        ]);

        $this->assertSame(0, $exitCode);
        $this->assertStringNotContainsString($password, Artisan::output());
    }

    public function test_success_output_contains_email_confirmation_without_password(): void
    {
        $password = 'SuccessOutputPassword123';

        $exitCode = Artisan::call('ucof:provision-user', [
            '--name' => 'Output User',
            '--email' => 'output@example.test',
            '--password' => $password,
        ]);

        $output = Artisan::output();

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('User output@example.test provisioned successfully.', $output);
        $this->assertStringNotContainsString($password, $output);
    }

    public function test_wrapper_script_usage_paths_do_not_echo_password(): void
    {
        $scripts = [
            base_path('deploy/scripts/local-dev/add-user.sh'),
            base_path('deploy/scripts/staging/add-user.sh'),
            base_path('deploy/scripts/production/add-user.sh'),
        ];

        foreach ($scripts as $script) {
            $content = file_get_contents($script);

            $this->assertIsString($content);
            $this->assertStringContainsString('Usage:', $content);
            $this->assertStringNotContainsString('echo "$3"', $content);
            $this->assertStringNotContainsString('printf "%s" "$3"', $content);
            $this->assertStringNotContainsString('printf %s "$3"', $content);
        }
    }

    public function test_provisioned_user_can_authenticate_and_reach_dashboard(): void
    {
        $this->artisan('ucof:provision-user', [
            '--name' => 'Login User',
            '--email' => 'login-ready@example.test',
            '--password' => 'ValidPassword123',
        ])->assertExitCode(0);

        $this->post('/login', [
            'email' => 'login-ready@example.test',
            'password' => 'ValidPassword123',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticated();
        $this->get('/dashboard')->assertOk()->assertSee('You are signed in');
    }

    public function test_provisioning_handles_db_connectivity_failure_with_safe_error_output(): void
    {
        DB::shouldReceive('connection')
            ->with('mongodb')
            ->andThrow(new \RuntimeException('mongodb unavailable: secret host details'));

        $exitCode = Artisan::call('ucof:provision-user', [
            '--name' => 'Data Store Error',
            '--email' => 'db-error@example.test',
            '--password' => 'ValidPassword123',
        ]);

        $output = Artisan::output();

        $this->assertSame(1, $exitCode);
        $this->assertStringContainsString('Provisioning failed due to a temporary data-store issue. Please retry.', $output);
        $this->assertStringNotContainsString('secret host details', $output);
    }
}
