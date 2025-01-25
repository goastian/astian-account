<?php
namespace App\Models\Subscription;

use App\Models\Master;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Master
{
    use HasFactory;

    public $table = "transactions";

    public $transformer = "";

    protected $fillable = [
        //
    ];

}
