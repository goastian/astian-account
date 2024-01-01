<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Elyerr\ApiResponse\Assets\Asset; 
use Illuminate\Notifications\Notifiable;
use App\Notifications\Auth\ResetPassword;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Auth extends Authenticatable
{
    use HasUuids, HasApiTokens, HasFactory, Notifiable, Asset;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'document_type',
        'document_number',
        'country',
        'department',
        'address',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = strtolower($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function setDocumentTypeAttribute($value){
        $this->attributes['document_type'] = strtolower($value);
    }

     public function getDocumentTypeAttribute($value){
        return strtoupper($value);
    }

    public function setCountryAttribute($value)
    {
        $this->attributes['country'] = strtolower($value);
    }

    public function setDepartmentAttribute($value)
    {
        $this->attributes['department'] = strtolower($value);
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = strtolower($value);
    }

     /**
     * verifica si contiene el role de administrador
     */
    public function isAdmin()
    {
        return $this->roles()->get()->contains('name', 'admin');
    }
    
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    } 
}
