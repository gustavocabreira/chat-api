<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pendingContacts(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'contacts', 'user_id', 'contact_id')
            ->join('contacts as c2', 'c2.user_id', '=', 'contacts.contact_id')
            ->whereColumn('c2.contact_id', '=', 'contacts.user_id')
            ->where('contacts.status', 'pending')
            ->withTimestamps();
    }

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'contacts', 'user_id', 'contact_id')
            ->join('contacts as c2', 'c2.user_id', '=', 'contacts.contact_id')
            ->whereColumn('c2.contact_id', '=', 'contacts.user_id')
            ->whereIn('contacts.status', ['pending', 'accepted'])
            ->withTimestamps();
    }
}
