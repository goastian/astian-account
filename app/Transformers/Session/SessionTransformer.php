<?php

namespace App\Transformers\Session;

use League\Fractal\TransformerAbstract;

class SessionTransformer extends TransformerAbstract
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
    public function transform($session)
    {
        return [
            'id' => $session->id,
            'ip' => $session->ip_address,
            'agente' => $session->user_agent,
            'ultima_coneccion' => $session->last_activity,   
            'links' => [
                'parent' => route('sessions.index'),
                'destroy' => route('sessions.destroy', ['session' => $session->id])
            ]    
        ];
    }
}
