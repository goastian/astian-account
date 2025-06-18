<?php

namespace App\Models;
 
use Elyerr\ApiResponse\Assets\Asset;
use App\Repositories\Traits\Standard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Master extends Model
{
    use HasUuids, HasFactory, Asset, Standard;

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
     * Summary of transformer
     * @var 
     */
    public $transformer = null;


    /**
     * Retrieve metadata of the model
     * @param $transformer
     */
    public function meta($transformer = null)
    {
        $data = fractal($this, $transformer ?? $this->transformer)->toArray()['data'];
        unset($data['links']);
        return $data;
    }

    /**
     * Transform data
     * @param mixed $data
     * @param mixed $transformer
     * @return array
     */
    public function transform($data, $transformer)
    {
        return fractal($data, $transformer)->toArray()['data'];
    }
}
