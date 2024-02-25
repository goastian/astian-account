<?php

namespace App\Models\OAuth;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class Scope implements Arrayable, Jsonable
{
    /**
     * The name / ID of the scope.
     *
     * @var string
     */
    public $id;

    /**
     * The scope description.
     *
     * @var string
     */
    public $description;

    /**
     * The type scope Public Or Private
     *
     * @var Boolean
     */
    public $public;

    /**
     * Create a new scope instance.
     *
     * @param  string  $id
     * @param  string  $description
     * @return void
     */
    public function __construct($id, $description, $public)
    {
        $this->id = $id;
        $this->description = $description;
        $this->public = $public;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'public' => $this->public,
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
