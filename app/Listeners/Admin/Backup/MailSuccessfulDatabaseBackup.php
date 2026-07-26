<?php

namespace App\Listeners\Admin\Backup;

use Illuminate\Support\Facades\Mail;
use Spatie\Backup\Events\BackupZipWasCreated;

class MailSuccessfulDatabaseBackup
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(BackupZipWasCreated $event) {
        $this->mailBackupFile($event->pathToZip);
    }

    public function mailBackupFile($path) {
        try {
            Mail::raw('Tiene un nuevo archivo de copia de seguridad de la base de datos.', function ($message) use ($path) {
                $message->to(env('DB_BACKUP_EMAIL', 'hola@example.com'))
                    ->subject('Copia de seguridad de la base de datos Lista.')
                    ->attach($path);
            });
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
