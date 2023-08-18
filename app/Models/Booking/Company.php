<?php

namespace App\Models\Booking;

use App\Assets\Timestamps;
use App\Transformers\Company\CompanyTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory, Timestamps;

    public $table = "companies";

    public $view = ""; 

    public $transformer = CompanyTransformer::class;

    protected $fillable = [
        'ruc',
        'company',
    ];

    public function setCompanyAttribute($value){
        $this->attributes['company'] = strtolower($value);
    }

    public function bookings(){
        return $this->hasMany(Booking::class);
    }
}
