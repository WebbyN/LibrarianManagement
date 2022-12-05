<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class Admins extends Controller
{
    public function customRegistration(Request $request)
    {  
        $request->validate([
            'name' => 'required|unique:users,name,except,id|alpha',
            'password' => 'required|min:6',
        ]);
           
        $data = $request->all();
        $check = $this->create($data);
         
        return redirect("dashboard")->withSuccess('You have signed-in');
    }

    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'password' => Hash::make($data['password'])
      ]);
    }   
    
    public function registration()
    {
        return view('regi');
    }
}
