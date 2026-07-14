<?php

namespace App\Console\Commands\Admin\User;

use App\Services\Synchronizers\User\UserService;
use Illuminate\Console\Command;

class UserSave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all users by odoo to local';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $userService = new UserService;
        $result = $userService->save();
        $this->info(json_encode($result, JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
