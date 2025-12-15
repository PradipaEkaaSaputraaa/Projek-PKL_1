<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // Diperlukan

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME; 

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Mengubah field login dari 'email' menjadi 'username'.
     */
    public function username()
    {
        return 'username';
    }


    /**
     * Redirect berdasarkan role setelah otentikasi.
     * (Admin ke Admin Panel, User ke Display Board)
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.posters.index');
        }

        // Jika role='user'
        return redirect()->route('display.board');
    }
}