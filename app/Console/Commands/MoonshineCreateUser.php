<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use MoonShine\Permissions\Models\MoonshineUser;

class MoonshineCreateUser extends Command
{
    protected $signature = 'moonshine:create-user {--u|username=} {--N|name=} {--p|password=}';

    protected $description = 'Create user';

    public function handle(): int
    {
        $username = $this->option('username') ?? config('moonshine.default_username');
        $name = $this->option('name') ?? config('moonshine.default_name');
        $password = $this->option('password') ?? config('moonshine.default_password');

        if (MoonshineUser::whereEmail($username)->exists()) {
            $this->info('User already exists!');
            return 0;
        }

        $this->call('moonshine:user', [
            '--username' => $username,
            '--name' => $name,
            '--password' => $password,
        ]);

        return 0;
    }
}
