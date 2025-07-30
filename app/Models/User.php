<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'balance',
        'password',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'balance',
        'email_verified_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'data' => 'array',
        ];
    }

    public function getRoleName() {
        return get_role_name($this->role);
    }

    public function service()
    {
        return $this->hasOne(Service::class);
    }

    public function fields()
    {
        return $this->hasMany(Field::class, 'manager_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class);
    }

    public function mutations()
    {
        return $this->hasMany(Mutation::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    // public function notifications()
    // {
    //     return $this->hasMany(Notification::class);
    // }

    public function unreadNotifications()
    {
        return $this->notifications()->where('read_at', null);
    }

    public function getImageUrl() {
        return get_image_url($this->photo, 'https://placehold.co/150x150?text=User+Image');
    }

    public function updateBalance()
    {
        DB::transaction(function () {
            $user = User::lockForUpdate()->find($this->id);
            $newBalance = $user->mutations()->sum('amount');
            $user->update(['balance' => $newBalance]);
            $this->refresh();
        });
    }
}
