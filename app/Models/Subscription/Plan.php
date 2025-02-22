<?php
namespace App\Models\Subscription;

use App\Models\Master;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Master
{
    use HasFactory;

    public $table = "plans";


    protected $fillable = [

    ];

}
