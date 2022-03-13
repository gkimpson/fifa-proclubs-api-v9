<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use stdClass;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    // use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_login()
    {
        $user = [
            'email' => 'gkimpson@gmail.com',
            'password' => 'password'
        ];
        
 
        $this->browse(function ($browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user['email'])
                    ->type('password', $user['password'])
                    ->click('@login-button')
                    ->assertPathIs('/dashboard');
        });
    }
}
