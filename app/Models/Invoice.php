<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Invoice extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Facturación')
            ->setDescriptionForEvent(fn (string $eventName) => "Una factura ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function order() {
        return $this->belongsTo(Order::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function statusToString() {
        $status = '';
        switch ($this->status) {
            case 'Vigente':
                $status = '<div class="badge badge-success">'.__($this->status).'</div>';
                break;
            case 'Cancelado':
                $status = '<div class="badge badge-danger">'.__($this->status).'</div>';
                break;
            case 'Pendiente':
                $status = '<div class="badge badge-warning">'.__($this->status).'</div>';
                break;
            default:
                $status = '<div class="badge badge-secondary">Status no encontrado</div>';
                break;
        }

        return $status;
    }
    public function statusSatToString() {
        $statusSat = '';
        switch ($this->status_sat) {
            case 'Vigente':
                $statusSat = '<div class="badge badge-success">'.__($this->status_sat).'</div>';
                break;
            case 'Cancelado':
                $statusSat = '<div class="badge badge-danger">'.__($this->status_sat).'</div>';
                break;
            case 'Pendiente':
                $statusSat = '<div class="badge badge-warning">'.__($this->status_sat).'</div>';
                break;
            default:
                $statusSat = '';
                break;
        }

        return $statusSat;
    }
    public function isCancellableToString() {
        $isCancellable = '';
        switch ($this->is_cancellable) {
            case 'Cancelable con aceptación':
                $isCancellable = '<div class="badge badge-success">'.__($this->is_cancellable).'</div>';
                break;
            case 'Cancelable sin aceptación':
                $isCancellable = '<div class="badge badge-success">'.__($this->is_cancellable).'</div>';
                break;
            case 'No cancelable':
                $isCancellable = '<div class="badge badge-danger">'.__($this->is_cancellable).'</div>';
                break;
            default:
                $isCancellable = '';
                break;
        }

        return $isCancellable;
    }
    public function statusCancellationToString() {
        $statusCancellation = '';
        switch ($this->is_cancellable) {
            case 'En proceso':
                $statusCancellation = '<div class="badge badge-info">'.__($this->is_cancellable).'</div>';
                break;
            case 'Plazo vencido':
                $statusCancellation = '<div class="badge badge-warning">'.__($this->is_cancellable).'</div>';
                break;
            case 'Solicitud rechazada':
                $statusCancellation = '<div class="badge badge-danger">'.__($this->is_cancellable).'</div>';
                break;
            case 'Cancelado sin aceptación':
                $statusCancellation = '<div class="badge badge-dark">'.__($this->is_cancellable).'</div>';
                break;
            case 'Cancelado con aceptación':
                $statusCancellation = '<div class="badge badge-success">'.__($this->is_cancellable).'</div>';
                break;
            default:
                $statusCancellation = '';
                break;
        }

        return $statusCancellation;
    }
}
