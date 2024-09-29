<?php

namespace App\Models\Setting;

use App\Models\Master;
use App\Transformers\Setting\AppTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class App extends Master
{
    use HasFactory;

    public $table = "apps";

    public $view = "";

    public $transformer = AppTransformer::class;

    protected $fillable = [
        'name',
        'url',
        'icon',
        'description',
    ];

}
