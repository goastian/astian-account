<?php
namespace App\Http\Controllers\Web\OAuth;
 
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Repositories\Traits\Scopes;
use Elyerr\ApiResponse\Assets\JsonResponser;
use App\Transformers\OAuth\PersonalTokenTransformer;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController as Controller;

class PersonalAccessTokenController extends Controller
{

    use Scopes, JsonResponser;

    /**
     * Show all
     * @param mixed $collection
     * @param mixed $transformer
     * @param mixed $code
     * @param mixed $pagination
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function showAllByArray($collection, $transformer = null, $code = 200, $pagination = true)
    {
        $collection = $this->orderBy($collection);

        if ($pagination) {
            $collection = $this->paginate($collection);
        }

        if ($transformer != null && gettype($transformer) != "integer") {
            $collection = fractal($collection, $transformer);
        }

        return $collection->toArray();
    }

    /**
     * Retrieve tokens forUser
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function forUser(Request $request)
    {
        $tokens = $this->tokenRepository->forUser($request->user()->getAuthIdentifier());
        $tokens = $tokens->load('client')->filter(function ($token) {
            return $token->client->personal_access_client && !$token->revoked;
        })->values();

        if (request()->wantsJson()) {
            return $this->showAll($tokens, PersonalTokenTransformer::class);
        }

        return Inertia::render("OAuth/Personal/Index", [
            'tokens' => $this->showAllByArray($tokens, PersonalTokenTransformer::class),
            'route' => route('passport.personal.tokens.index')
        ]);
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
            'scopes' => 'array|in:' . implode(',', $this->scopesForUser()),
            'expiration_date' => ['nullable', 'date_format:Y-m-d H:i']
        ])->validate();

        $generateToken = $request->user()->createToken(
            $request->name,
            $request->scopes ?? []
        );

        if ($request->expiration_date) {
            $generateToken->token->expires_at = $request->expiration_date;
            $generateToken->token->push();
        }

        return $generateToken;
    }

    /**
     * Get the scopes for current user
     */
    private function scopesForUser()
    {
        return $this->scopes()->pluck('id')->toArray();
    }
}
