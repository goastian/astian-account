<?php
namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\OAuth\Scopes;
use Illuminate\Http\Request;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController as Controller;

final class PersonalAccessTokenController extends Controller
{

    use Scopes;

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
        ])->validate();

        return $request->user()->createToken(
            $request->name, $request->scopes ?: []
        );
    }

    /**
     * Get the scopes for actual user
     */
    private function scopesForUser()
    {
        return $this->scopes()->pluck('id')->toArray();
    }
}
