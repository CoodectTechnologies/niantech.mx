<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $countryDefaultId = Country::query()->where('default', true)->first()->id ?? null;
        $administrator = User::create([
            'country_id' => $countryDefaultId,
            'name' => 'Admin',
            'email' => 'coodect.manager@gmail.com',
            'password' => Hash::make('coodect2020'),
            'connected_google' => 1,
        ]);
        $administrator->assignRole('Administrador');
        $administrator->markEmailAsVerified();

        if (! User::where('email', config('contact.email'))->exists()) {
            $administrator = User::create([
                'country_id' => $countryDefaultId,
                'name' => 'Contacto',
                'email' => config('contact.email'),
                'password' => Hash::make('coodect2020'),
                'connected_google' => 1,
            ]);
            $administrator->assignRole('Administrador');
            $administrator->markEmailAsVerified();
        }
    }
}
