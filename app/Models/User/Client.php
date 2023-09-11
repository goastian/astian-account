<?php

namespace App\Models\User;

use App\Assets\Timestamps;
use App\Models\Booking\Rent;
use App\Models\master;
use App\Transformers\Auth\ClientTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends master
{
    use HasFactory, Timestamps;

    public $table = "clients";

    public $view = "";

    public $transformer = ClientTransformer::class;

    protected $fillable = [
        'name',
        'last_name',
        'document',
        'number',
        'city',
        'country',
        'email',
        'phone',
    ];

    protected $hidden = [
       // 'pivot'
    ];

    public function rooms()
    {
        return $this->belongsToMany(Rent::class, 'client_rent', 'client_id', 'rent_id')->withTimestamps();
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = strtolower($value);
    }

    public function setDocumentAttribute($value)
    {
        $this->attributes['document'] = strtolower($value);
    }

    public function setEmailtAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function setCityAttribute($value)
    {
        $this->attributes['city'] = strtolower($value);
    }

    public function setCountryAttribute($value)
    {
        $this->attributes['country'] = strtolower($value);
    }
}
