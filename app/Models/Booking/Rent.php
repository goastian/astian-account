<?php

namespace App\Models\Booking;

use App\Assets\Timestamps;
use App\Models\Assets\Category;
use App\Models\Assets\Room;
use App\Models\User\Client;
use App\Models\Booking\Booking;
use App\Models\Master as master;
use App\Transformers\Booking\RentTransformer; 

class Rent extends master
{ 
    public $table = "rents";

    public $view = "";

    public $transformer = RentTransformer::class;

    protected $fillable = [
        'category_id',
        'room_id',
        'price',
        'booking_id'
    ];

    protected $hidden = [
       // 'pivot'
    ];

   
    public function booking()
    {
        return $this->belongsTo(Booking::class)->withTrashed();
    }

    public function huespeds()
    {
        return $this->belongsToMany(Client::class, 'client_rent', 'client_id', 'rent_id')
                ->withPivot(['created_at']);
    }

    public function room(){
        return $this->belongsTo(Room::class)->withTrashed();
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
