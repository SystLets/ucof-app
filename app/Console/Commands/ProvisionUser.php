<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use App\Rules\PasswordPolicyRule;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ProvisionUser extends Command
{
    protected $signature = 'ucof:provision-user {--name=} {--email=} {--password=}';

    protected $description = 'Provision or reactivate a login-ready user account';

    public function handle(): int
    {
        $name = $this->option('name');
        $email = $this->option('email');
        $password = $this->option('password');

        $validator = Validator::make(
            [
                'name' => $name,
                'email' => $email,
                'password' => $password,
            ],
            [
                'name' => ['required', 'string'],
                'email' => ['required', 'email'],
                'password' => ['required', 'string', new PasswordPolicyRule()],
            ],
        );

        if ($validator->fails()) {
            $this->error($validator->errors()->first());

            return self::FAILURE;
        }

        try {
            DB::connection('mongodb')->getMongoDB();

            $normalizedEmail = strtolower((string) $email);
            $existing = User::query()->where('email', $normalizedEmail)->first();
            $executedAt = now();
            $executedFrom = (string) (gethostname() ?: 'unknown-host');

            if ($existing instanceof User) {
                $status = (string) $existing->status;

                if (in_array($status, ['active', 'locked'], true)) {
                    $this->error(sprintf('An active account already exists for %s.', $normalizedEmail));

                    return self::FAILURE;
                }

                if (in_array($status, ['inactive', 'disabled'], true)) {
                    $existing->forceFill([
                        'name' => (string) $name,
                        'password' => Hash::make((string) $password),
                        'status' => 'active',
                        'failed_signin_attempts' => 0,
                        'provisioned_at' => $executedAt,
                        'provisioned_from' => $executedFrom,
                    ]);
                    $existing->save();

                    $this->info(sprintf('User %s reactivated and password updated.', $normalizedEmail));

                    return self::SUCCESS;
                }

                $this->error(sprintf('An account already exists for %s and cannot be provisioned in its current state.', $normalizedEmail));

                return self::FAILURE;
            }

            $user = new User();
            $user->fill([
                'name' => (string) $name,
                'email' => $normalizedEmail,
                'password' => Hash::make((string) $password),
                'status' => 'active',
                'failed_signin_attempts' => 0,
                'provisioned_at' => $executedAt,
                'provisioned_from' => $executedFrom,
            ]);
            $user->save();

            $this->info(sprintf('User %s provisioned successfully.', $normalizedEmail));

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error('Provisioning failed due to a temporary data-store issue. Please retry. ' . $e->getMessage());

            return self::FAILURE;
        }
    }
}
