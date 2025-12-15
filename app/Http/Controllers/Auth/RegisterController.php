<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/login'; 

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            // EMAIL DAN USERNAME KEDUANYA WAJIB DI SINI
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], 
            'username' => ['required', 'string', 'max:255', 'unique:users'], 
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'], // MENYIMPAN EMAIL
            'username' => $data['username'], 
            'password' => Hash::make($data['password']),
            'role' => 'user', 
        ]);
    }
}