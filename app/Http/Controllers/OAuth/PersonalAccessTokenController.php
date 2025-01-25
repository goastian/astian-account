<?php
namespace App\Http\Controllers\OAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\OAuth\Scopes;
use Elyerr\ApiResponse\Assets\JsonResponser;
use App\Transformers\OAuth\PersonalTokenTransformer;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController as Controller;

final class PersonalAccessTokenController extends Controller
{

    use Scopes, JsonResponser;


    /**
     * Retrieve the all token 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function forUser(Request $request)
    {
        $tokens = $this->tokenRepository->forUser($request->user()->getAuthIdentifier());
        $tokens = $tokens->load('client')->filter(function ($token) {
            return $token->client->personal_access_client && !$token->revoked;
        })->values();

        return $this->showAll($tokens, PersonalTokenTransformer::class);
    }

    /**
     * Create a new personal access token for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Laravel\Passport\PersonalAccessTokenResult
     */
    public function store(Request $request)
    {
        $this->validation->make($request->all(), [
            'name' => 'required|max:191',
            //     'scopes' => 'array|in:' . implode(',', $this->scopesForUser()),
        ])->validate();


        $token = $request->user()->createToken(
            $request->name,
            $request->scopes ?: []
        );

        return $token;
    }

    /**
     * Get the scopes for actual user
     */
    private function scopesForUser()
    {
        return $this->scopes()->pluck('id')->toArray();
    }
}
