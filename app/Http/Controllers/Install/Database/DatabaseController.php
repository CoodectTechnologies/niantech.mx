<?php

namespace App\Http\Controllers\Install\Database;

use App\Http\Controllers\Controller;
use Database\Seeders\ModuleWebSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class DatabaseController extends Controller
{
    public function index() {
        return view('install.database.index');
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'connection' => 'required',
            'host' => 'required',
            'port' => 'required',
            'database' => 'required',
            'username' => 'required',
            'password' => 'nullable',
            'withSeeders' => 'nullable',
        ]);
        try {
            setEnvValue('DB_CONNECTION', $validated['connection']);
            setEnvValue('DB_HOST', $validated['host']);
            setEnvValue('DB_PORT', $validated['port']);
            setEnvValue('DB_DATABASE', $validated['database']);
            setEnvValue('DB_USERNAME', $validated['username']);
            setEnvValue('DB_PASSWORD', isset($validated['password']) ? $validated['password'] : '');
            if (file_exists(App::getCachedConfigPath())) {
                Artisan::call('config:cache');
            }
            $withSeeders = false;
            if (isset($validated['withSeeders'])) {
                $withSeeders = true;
            }

            return Redirect::route('install.database.test-connection', ['withSeeders' => $withSeeders]);
        } catch (Exception $e) {
            report($e);
            Session::flash('alert', 'Ocurrio un error: '.$e->getMessage());
            Session::flash('alert-type', 'warning');

            return Redirect::route('install.database.index');
        }
    }
    public function testConnection(Request $request) {
        try {
            DB::connection()->getPdo();
            if ($request->withSeeders) {
                Artisan::call('migrate:fresh', ['--seed' => true]);
            } else {
                Artisan::call('migrate:fresh');
                $seeder = new PermissionSeeder;
                $seeder->run();

                $seeder = new RoleSeeder;
                $seeder->run();

                $seeder = new ModuleWebSeeder;
                $seeder->run();
            }

            return Redirect::route('install.email.index');
        } catch (Exception $e) {
            report($e);
            Session::flash('alert', 'Ocurrio un error en la conexión: '.$e->getMessage());
            Session::flash('alert-type', 'warning');

            return Redirect::route('install.database.index');
        }
    }
}
