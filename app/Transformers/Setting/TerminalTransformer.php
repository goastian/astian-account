<?php

namespace App\Transformers\Setting;

use App\Models\Setting\Terminal;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class TerminalTransformer extends TransformerAbstract
{
    use Asset;
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
    public function transform(Terminal $terminal)
    {
        return [
            'command' => $terminal->command,
            'output' => json_decode($terminal->output),
            'status' => $terminal->status,
            'user' => [
                'name' => $terminal->user->name,
                'last_name' => $terminal->user->last_name,
                'email' => $terminal->user->email,
            ],
            'created' => $this->format_date($terminal->created_at),
            'updated' => $this->format_date($terminal->updated_at),
            'links' => [
                'index' => route('admin.terminals.index'),
                'execute' => route('admin.terminals.store')
            ]
        ];
    }



    /**
     * Return the original attribute 
     * @param mixed $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'command' => 'command',
            'output' => 'output',
            'status' => 'status',
            'created' => 'created_at',
            'updated' => 'updated_at'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
