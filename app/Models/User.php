<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
        'national_id',
        'organization_name',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isStaff(): bool    { return $this->role === 'staff'; }
    public function isBusiness(): bool { return $this->role === 'business'; }
    public function isCitizen(): bool  { return $this->role === 'citizen'; }

    public function hasRole(string|array $roles): bool
    {
        return in_array($this->role, (array) $roles);
    }

    // Role hierarchy check — "does this user have AT LEAST this level?"
    public function hasMinimumRole(string $minimumRole): bool
    {
        $hierarchy = ['citizen' => 1, 'business' => 2, 'staff' => 3, 'admin' => 4];
        $userLevel = $hierarchy[$this->role] ?? 0;
        $minLevel  = $hierarchy[$minimumRole] ?? 0;

        return $userLevel >= $minLevel;
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function licenseApplications()
    {
        return $this->hasMany(LicenseApplication::class);
    }

    public function domainRegistrations()
    {
        return $this->hasMany(DomainRegistration::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
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
}
