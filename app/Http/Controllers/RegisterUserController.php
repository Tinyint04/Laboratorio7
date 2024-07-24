<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 

class RegisterUserController extends Controller
{
    public function create()
    {
        return view('auth.registrar');
    }
    public function store()
    {
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create($data);

        auth()->login($user);
        

        return redirect('/tasks');
    }
}
