<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Plan extends Model
{
    use HasFactory, HasRoles, Sluggable;

    protected $guarded = [];
    protected $guard_name = 'web';
    protected $casts = [
        'status' => 'boolean',
        'featured' => 'boolean',
        'stripe_price_month_id' => 'string',
        'stripe_price_year_id' => 'string',
        'stripe_product_name' => 'string',
    ];

    public function sluggable(): array {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }
    public function getRouteKeyName() {
        return 'slug';
    }
    public function planFeatures() {
        return $this->belongsToMany(PlanFeature::class);
    }
    public function subscriptions() {
        return $this->hasMany(Subscription::class);
    }
    public function isFree(){
        return ($this->free_trial_days === null && !$this->amount_month && !$this->amount_year);
    }
    public function statusToString() {
        switch ($this->status) {
            case true:
                return '<span class="badge badge-light-success">'.__('Activo').'</span>';
                break;
            case false:
                return '<span class="badge badge-light-secondary">'.__('Desactivado').'</span>';
                break;
            default:
                return '<span class="badge badge-light-secondary">'.__('Estado no encontrado').'</span>';
                break;
        }
    }
}
