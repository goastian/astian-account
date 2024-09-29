<?php

namespace App\Transformers\Tokens;
  
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class TokensTransformer extends TransformerAbstract
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
    public function transform($token)
    {
        return [
            'id' => $token->id,
            'agent' => $token->name,
            'scope' => implode(",",$token->scopes),
            'revoked' => $token->revoked,
            'expires' => $token->expires_at ? $this->format_date($token->expires_at) : null,
            'created' => $token->created_at ? $this->format_date($token->created_at) : null,
            'updated' => $token->updated_at ? $this->format_date($token->updated_at) : null,
            'links' => [
                'self' => route('tokens.index'),
                'store' => route('tokens.store'),
                'destroy' => route('tokens.destroy', ['token' => $token->id]),
                'destroyAll' => route('tokens.destroyAll')
            ]
        ];
    }
}
