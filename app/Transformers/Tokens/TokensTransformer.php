<?php

namespace App\Transformers\Tokens;

use League\Fractal\TransformerAbstract;

class TokensTransformer extends TransformerAbstract
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
    public function transform($token)
    {
        return [
            'identificador' => $token->id,
            'agente' => $token->name,
            'ultimo_uso' => $token->last_used_at,
            'creado' => $token->created_at,
            'actualizado' => $token->updated_at,
        ];
    }
}
