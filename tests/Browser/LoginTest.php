<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    // use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_basic_example()
    {
        // $user = User::factory()->create([
        //     'email' => 'gkimpson@gmail.com',
        // ]);

        $user = [];
 
        $this->browse(function ($browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', 'gkimpson@gmail.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/home');
        });
    }
}
