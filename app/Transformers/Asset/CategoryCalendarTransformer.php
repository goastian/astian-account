<?php

namespace App\Transformers\Asset;

use League\Fractal\TransformerAbstract;

class CategoryCalendarTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($data)
    {
        return [
            'id' => $data->id,
            'dia' => $data->day,
            'disponibles' => $data->available,
            'links' => [
                'parent' => route('categories.calendars.index', ['category' => request('category')->id]),
                'store' => route('categories.calendars.store', ['category' => request('category')->id]),
                'show' => route('categories.calendars.show', ['category' => request('category')->id, 'calendar' => $data->id]),
                'update' => route('categories.calendars.update', ['category' => request('category')->id, 'calendar' => $data->id]),
            ]
        ];
    }
}
