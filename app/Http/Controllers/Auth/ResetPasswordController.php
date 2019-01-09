<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Session;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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

    public function index()
    {
        return view('auth.reset');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'username'          => ['required', 'string', 'min:6', 'max:20'],
            'password'          => ['required', 'string', 'min:6', 'max:20', 'confirmed'],
            'security_question' => ['required', 'string', 'max:255'],
            'classified_answer' => ['required', 'string', 'max:255'],
        ]);

        $user = User::where('username', $request->input('username'))->first();

        if($user && $user->security_question == $request->input('security_question') && $user->classified_answer == $request->input('classified_answer')){
            $user->password = bcrypt($request->input('password'));
            $user->save();

            Session::flash('register', '密码重置成功');
        }else{
            Session::flash('register', '密保问题或答案错误');
        }

        return back();
    }
}
