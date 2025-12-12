<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Redirect; // <--- TAMBAHKAN INI

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // Nilai ini bisa diabaikan atau dibiarkan saja karena kita akan override di metode authenticated()
    protected $redirectTo = '/home'; 

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
    
    // ==========================================================
    // FUNGSI UNTUK MENGUBAH LOGIN FIELD DARI EMAIL KE USERNAME
    // ==========================================================
    
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username'; // Mengubah kunci autentikasi ke username
    }

    // ==========================================================
    // TAMBAHAN UTAMA: FUNGSI UNTUK MENGARAHKAN BERDASARKAN ROLE
    // ==========================================================
    
    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated($request, $user)
    {
        if ($user->role === 'admin') {
            // Jika user adalah admin, arahkan ke Admin Panel
            return Redirect::intended('/admin/posters');
        }

        // Jika user adalah user (atau role lain), arahkan ke Display Board
        return Redirect::intended('/display');
    }
}