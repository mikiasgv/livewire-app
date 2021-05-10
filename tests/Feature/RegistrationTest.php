<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function registration_page_has_livewire_component()
    {
        $this->get('/register')->assertSeeLivewire('auth.register');
    }

    /** @test */

    public function can_register()
    {
        Livewire::test('auth.register')
            ->set('name', 'testuser')
            ->set('email', 'mikiasgv@gmail.com')
            ->set('password', 'secret')
            ->set('passwordConfirmation', 'secret')
            ->call('register')
            ->assertRedirect('/');

        $this->assertTrue(User::whereEmail('mikiasgv@gmail.com')->exists());
        $this->assertEquals('mikiasgv@gmail.com', auth()->user()->email);
    }

    /** @test */

    public function email_is_required()
    {
        Livewire::test('auth.register')
            ->set('name', 'testuser')
            ->set('email', '')
            ->set('password', 'secret')
            ->set('passwordConfirmation', 'secret')
            ->call('register')
            ->assertHasErrors(['email' => 'required']);
    }

    /** @test */

    public function email_is_valid_email()
    {
        Livewire::test('auth.register')
            ->set('name', 'testuser')
            ->set('email', 'mikiasgv')
            ->set('password', 'secret')
            ->set('passwordConfirmation', 'secret')
            ->call('register')
            ->assertHasErrors(['email' => 'email']);
    }

    /** @test */

    public function email_has_already_been_taken()
    {
        User::create([
            'name'  =>  'miki',
            'email' => 'mikiasgv@gmail.com',
            'password' => Hash::make('secret')
        ]);

        Livewire::test('auth.register')
            ->set('name', 'testuser')
            ->set('email', 'mikiasgv@gmail.com')
            ->set('password', 'secret')
            ->set('passwordConfirmation', 'secret')
            ->call('register')
            ->assertHasErrors(['email' => 'unique']);
    }

    /** @test */

    public function see_email_has_already_been_taken_validation_message()
    {
        User::create([
            'name'  =>  'miki',
            'email' => 'mikiasgv@gmail.com',
            'password' => Hash::make('secret')
        ]);

        Livewire::test('auth.register')
            ->set('name', 'testuser')
            ->set('email', 'mikiasg@gmail.com')
            ->assertHasNoErrors()
            ->set('email', 'mikiasgv@gmail.com')
            ->assertHasErrors(['email' => 'unique']);
    }

    /** @test */

    public function password_is_required()
    {
        Livewire::test('auth.register')
            ->set('name', 'testuser')
            ->set('email', 'mikiasgv@gmail.com')
            ->set('password', '')
            ->set('passwordConfirmation', 'secret')
            ->call('register')
            ->assertHasErrors(['password' => 'required']);
    }

    /** @test */

    public function password_is_min_six_characters()
    {
        Livewire::test('auth.register')
            ->set('name', 'testuser')
            ->set('email', 'mikiasgv@gmail.com')
            ->set('password', 'sec')
            ->set('passwordConfirmation', 'secret')
            ->call('register')
            ->assertHasErrors(['password' => 'min']);
    }

    /** @test */

    public function password_not_matching_password_confirmation()
    {
        Livewire::test('auth.register')
            ->set('name', 'testuser')
            ->set('email', 'mikiasgv@gmail.com')
            ->set('password', 'secret')
            ->set('passwordConfirmation', 'not-secret')
            ->call('register')
            ->assertHasErrors(['password' => 'same']);
    }
}
