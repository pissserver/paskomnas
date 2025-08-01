<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use App\Models\Messsage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'location',
        'about_me',
        'role',
        'photo',
        'google_id',
        'last_active_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getIsOnlineAttribute()
    {
        return $this->last_active_at && Carbon::parse($this->last_active_at)->gt(now()->subMinutes(15));
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function lastMessageWith($currentUserId)
    {
        return Message::where(function ($q) use ($currentUserId) {
            $q->where('sender_id', $this->id)
            ->where('receiver_id', $currentUserId);
        })->orWhere(function ($q) use ($currentUserId) {
            $q->where('sender_id', $currentUserId)
            ->where('receiver_id', $this->id);
        })->latest('created_at')->first();
    }

    public function unreadMessagesCount($currentUserId)
    {
        return Message::where('sender_id', $this->id)
            ->where('receiver_id', $currentUserId)
            ->where('is_read', false)
            ->count();
    }

    public static function totalUnreadCount($userId)
    {
        return Message::where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();
    }
}
