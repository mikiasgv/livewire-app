<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $username = '';
    public $email = '';
    public $password = '';
    public $passwordConfirmation = '';

    public function updatedEmail()
    {
        $this->validate([
            'email' =>  'unique:users'
        ]);
    }


    public function register()
    {
        $data = $this->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password'  =>  'required|min:6|same:passwordConfirmation'
        ]);

        $user = User::create([
            'username'  =>  $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        auth()->login($user);

        return redirect('/');
    }
    public function render()
    {
        return view('livewire.auth.register')
            ->layout('layouts.auth');
    }
}
