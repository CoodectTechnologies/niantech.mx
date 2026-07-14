<?php

namespace App\Services\User;

use App\Models\Country;
use App\Models\Newsletter;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegistrationService
{
    public function __construct() {}
    public function register(array $data, bool $markVerified = false): ?User {
        return DB::transaction(function () use ($data, $markVerified) {
            $countryId = $data['country'] ?? Country::query()->validate()->where('default', true)->first()->id ?? null;
            $user = User::create([
                'country_id' => $countryId,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => isset($data['password']) ? Hash::make($data['password']) : null,
            ]);

            $user->assignRole('Cliente');
            if ($markVerified) {
                $user->markEmailAsVerified();
            }

            Newsletter::firstOrCreate(['email' => $user->email]);
            DB::afterCommit(fn () => event(new Registered($user)));

            return $user;
        });
    }
}
