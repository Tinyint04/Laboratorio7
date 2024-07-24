<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function destroy()
    {
        auth()->logout();

        return redirect('/tasks');
    }

    
    public function create()
    {
        return view('auth.login');
    }
    

    public function store()
    {
        $data = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($data)){
            throw ValidationException::withMessages([
                'email' => 'Las credenciales ingresadas no se encuentran en nuestra base de datos. Por favor, verifique su información e intente nuevamente.',
            ]);
        }

        session()->regenerate();

        return redirect('/tasks');



    }
}
