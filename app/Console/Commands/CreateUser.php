<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

/**
 * Creates a login for /admin or resets its password. A command instead of a
 * seeder, so no default password ends up in the repository.
 */
class CreateUser extends Command
{
    protected $signature = 'user:create {--name= : Name} {--email= : E-mail address (login)}';

    protected $description = 'Create a login for /admin or reset its password';

    public function handle(): int
    {
        $data = [
            'name' => $this->option('name') ?: $this->ask('Name'),
            'email' => $this->option('email') ?: $this->ask('E-mail'),
            'password' => $this->secret('Password (min. 10 characters)'),
        ];

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', Password::min(10)],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::updateOrCreate(
            ['email' => $data['email']],
            ['name' => $data['name'], 'password' => Hash::make($data['password'])],
        );

        $this->info(($user->wasRecentlyCreated ? 'Created: ' : 'Password updated: ').$user->email);

        return self::SUCCESS;
    }
}
