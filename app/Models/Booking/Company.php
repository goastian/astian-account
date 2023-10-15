<?php

namespace App\Models\Booking;
 
use App\Models\Master as master;
use App\Transformers\Company\CompanyTransformer; 

class Company extends master
{

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
