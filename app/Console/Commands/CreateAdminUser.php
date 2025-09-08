<?php

namespace App\Console\Commands;

use App\Data\Admin\User\AdminCreateData;
use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-admin {emails} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $password = Str::random(12);

        $data = new AdminCreateData(
            $this->argument('emails'),
            $this->argument('name'),
            $password,
        );

        $user = User::create([
            'emails' => $data->email,
            'name' => $data->name,
            'password' => $password,
        ]);

        $user->assignRole(RolesEnum::Admin);
        $user->givePermissionTo([
            PermissionsEnum::UserCreate,
            PermissionsEnum::UserView,
            PermissionsEnum::UserUpdate,
            PermissionsEnum::UserDelete
        ]);

        $this->info("Password: $data->password");

        return 0;
    }

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array<string, string>
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => __('form.create_admin_name'),
            'emails' => __('form.create_admin_email')
        ];
    }
}
