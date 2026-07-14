<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanFeature extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function plans() {
        return $this->belongsToMany(Plan::class);
    }
    public function dateToString() {
        return Carbon::parse($this->created_at)->toFormattedDateString();
    }
}
