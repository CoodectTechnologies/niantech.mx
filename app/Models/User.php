<?php

namespace App\Models;

use App\Notifications\Auth\ResetPasswordNotification;
use App\Notifications\Auth\VerifyEmail;
use App\Traits\HasPlans;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Cashier\Billable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, Viewable
{
    use Billable;
    use CausesActivity;
    use HasFactory;
    use HasPlans;
    use HasRoles;
    use InteractsWithViews;
    use LogsActivity;
    use Notifiable;
    use Sluggable;

    protected $guarded = [];
    protected $removeViewsOnDelete = true;
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'connected_google' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Usuario')
            ->setDescriptionForEvent(fn (string $eventName) => "Un usuario ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function getRouteKeyName() {
        return 'slug';
    }
    public function sluggable(): array {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }
    public function country() {
        return $this->belongsTo(Country::class);
    }
    public function profile() {
        return $this->hasOne(Profile::class);
    }
    public function image() {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function reviews() {
        return $this->morphMany(Review::class, 'reviewable');
    }
    public function reactions() {
        return $this->morphMany(Reaction::class, 'reactionable');
    }
    public function blogPosts() {
        return $this->hasMany(BlogPost::class);
    }
    public function orders() {
        return $this->hasMany(Order::class);
    }
    public function addresses() {
        return $this->hasMany(Address::class);
    }
    public function ordersCount() {
        return count($this->orders);
    }
    public function ordersIncome() {
        return $this->orders()->sum('total');
    }
    public function addressDefect() {
        return $this->addresses()->where('is_default', true)->validate()->first();
    }
    public function billingAddressDefect() {
        return $this->addresses()->where('is_billing', true)->validate()->where('is_billing_default', true)->first();
    }
    public function userInvoices() {
        return $this->hasMany(Invoice::class);
    }
    public function chatbots() {
        return $this->hasMany(Chatbot::class);
    }
    public function chatbotChats() {
        return $this->hasMany(ChatbotChat::class);
    }
    public function chatbotChatsMessages() {
        return $this->hasMany(ChatbotChatMessage::class);
    }
    public function subscriptionActive() {
        return $this->hasOne(Subscription::class)->where(function ($query) { $query->active(); })->latestOfMany();
    }

    // Gets
    public function accessToPanel() {
        return $this->hasPermissionTo('panel');
    }
    public function imagePreview() {
        $image = asset('assets/admin/media/avatars/blank.png');
        if ($this->image) {
            if (Storage::exists($this->image->url)) {
                $image = Storage::url($this->image->url);
            } else {
                $image = $this->image->url;
            }
        }

        return $image;
    }
    public function getDigitalProducts() {
        $productsDigitals = collect();
        $orders = $this->orders->where('payment_status', 'Aprobado')->whereNotIn('status', ['Cancelado', 'Devolución']);
        foreach ($orders as $order) {
            foreach ($order->products as $product) {
                if ($product->pivot->type == Product::TYPE_DIGITAL) {
                    $productsDigitals->push($product);
                }
            }
        }

        return $productsDigitals;
    }
    public function dateToString() {
        return Carbon::parse($this->created_at)->toFormattedDateString();
    }
    public function viewUniques() {
        return views($this)->unique()->count();
    }

    // OVERRIDE VENDOR
    public function sendEmailVerificationNotification() {
        $this->notify(new VerifyEmail);
    }
    public function sendPasswordResetNotification(#[\SensitiveParameter] $token) {
        $this->notify(new ResetPasswordNotification($token));
    }
    public function hasPermissionTo($permission, $guardName = null): bool {
        if ($this->getWildcardClass()) {
            return $this->hasWildcardPermission($permission, $guardName);
        }
        $permission = $this->filterPermission($permission, $guardName);

        return $this->hasDirectPermission($permission)
            || $this->hasPermissionViaRole($permission)
            || $this->hasPermissionViaPlan($permission);
    }
    public function getAllPermissions() {
        $permissions = $this->permissions->merge($this->getPermissionsViaRoles());
        $permissions = $permissions->merge($this->getPermissionsViaPlans());

        return $permissions->unique('id')->values();
    }
}
