<?php

namespace App\Models\Account;

use App\Assets\Timestamps;
use App\Transformers\Accounting\AccountingTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accounting extends Model
{
    use HasFactory, Timestamps;

    public $table = "accountings";

    public $view = "";

    public $transformer = AccountingTransformer::class;

    protected $fillable = [
        'description',
        'price',
        'type',
        'method',
        'code',
    ]; 
    
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = strtolower($value);
    } 
}
