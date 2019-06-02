<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username'          => ['required', 'string', 'min:6', 'max:20', 'unique:users'],
            'password'          => ['required', 'string', 'min:6', 'max:20', 'confirmed'],
            'security_question' => ['required', 'string', 'max:255'],
            'classified_answer' => ['required', 'string', 'max:255'],
//            'code'              => ['code', 'required', 'string', 'max:255'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
//        $user = User::where('code', strtoupper($data['code']))->first();
//        $user->increment('invite');
//        do {
//            $code = strtoupper(initCode());
//        } while (User::where('code', $code)->count());

        return User::create([
            'pid'               => 0,
            'username'          => $data['username'],
            'security_question' => $data['security_question'],
            'classified_answer' => $data['classified_answer'],
//            'code'              => $code,
            'password'          => Hash::make($data['password']),
        ]);
    }
}
