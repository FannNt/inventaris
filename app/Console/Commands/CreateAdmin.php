<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        validator([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validate();
        $input['name'] = $this->ask('Enter Username?');
        $input['email'] = $this->ask('Enter Email?');
        $input['password'] = $this->secret('Enter Password?');
        $input['password_confirmation'] = $this->secret('Confirm Password?');
        $input['is_admin'] = 1;
        if ($input['password'] != $input['password_confirmation']) {
            $this->error('Passwords do not match');
        } else {
            try {
                Hash::make($input['password']);
                $user = User::create($input);
                $this->info('Admin added successfully!');

            }catch (\Exception $exception){
                $this->error('Got error: ' . $exception->getMessage());
            }
        }

    }
}
