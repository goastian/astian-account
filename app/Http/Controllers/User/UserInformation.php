<?php
namespace App\Http\Controllers\User;


use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserInformation extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * user personal information
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function personalInformation(Request $request, User $user)
    {
        $user = $user->find(auth()->user()->id);

        $this->validate($request, [
            'name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'country' => ['required', 'max:150'],
            'city' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'max:150'],
            'dial_code' => [Rule::requiredIf(request()->phone != null), 'max:8', 'exists:countries,dial_code'],
            'phone' => [Rule::requiredIf(request()->dial_code != null), 'max:25', 'unique:users,phone,' . $user->id],
            'birthday' => ['nullable', 'date_format:Y-m-d', 'before: ' . User::setBirthday()],
        ]);

        $this->checkMethod('put');
        $this->checkContentType($this->getUpdateHeader());

        DB::transaction(function () use ($user, $request) {

            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->country = $request->country;
            $user->city = $request->city;
            $user->address = $request->address;
            $user->dial_code = $request->dial_code;
            $user->phone = $request->phone;
            $user->birthday = $request->birthday;
            $user->push();

        });

        return $this->showOne($user, $user->transformer);
    }


    /**
     * Change password
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request, User $user)
    {

        $user = $user->find(auth()->user()->id);

        $this->validate($request, [
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('The password is incorrect');
                    }
                }
            ],
            'password' => ['required', 'confirmed', 'min:10', 'max:200']
        ]);

        $this->checkMethod('put');
        $this->checkContentType($this->getUpdateHeader());

        DB::transaction(function () use ($request, $user) {
            $user->password = Hash::make($request->password);
            $user->push();
        });

        return $this->message(__("password changed successfully"), 200);
    }
}
