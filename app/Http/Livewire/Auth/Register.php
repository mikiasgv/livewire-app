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

    protected $rules = [
        'username' => 'required',
        'email' => 'required|email|unique:users',
        'password'  =>  'required|min:6|same:passwordConfirmation'
    ];

    public function updatedEmail()
    {
        $this->validate([
            'email' =>  'unique:users'
        ]);
    }


    public function register()
    {
        $this->validate();

        $user = User::create([
            'username'  =>  $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password)
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
