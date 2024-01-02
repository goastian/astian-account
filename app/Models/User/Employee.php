<?php

namespace App\Models\User;

use App\Models\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Transformers\Auth\EmployeeTransformer;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Employee extends Auth
{
    use SoftDeletes;
    
    public $table = "employees";

    //public $view = "";

    public $transformer = EmployeeTransformer::class;

    public function roles()
    {
       return $this->belongsToMany(Role::class, 'employee_role');
    }

     /**
     * Verify the correct user and check if they have activated 2FA.
     * 
     * @param Request $request
     */
    public static function validate(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = Employee::where('email', $request->email)->first();

        if ($user && $user->m2fa && Hash::check($request->password, $user->password)) {
            return true;
        }

        return false;
    }    
}   
