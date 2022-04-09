<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $redirecTo = '/painel';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index(){
        return view('auth.login');
    }

    public function authenticate(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email|max:100',
            'password' => 'required|min:4',
            
        ]);

        $remember = $request->input('remember', false);


        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('painel');
        } else {
            return redirect()->route('login')->with('error', 'E-mail e/ou senha inválidos')
                ->withInput();
        }



//        if(Auth::check()){
//            return redirect('painel/admin');
//        }else {
//            validator()->errors()->add('password', 'E-mail e/ou senha errados!');
//            return redirect('painel/login')->withInput();
//        }

    }
}
