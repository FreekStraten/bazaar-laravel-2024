<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{

    //check if register page exists
    public function test_register(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->assertSee('Register');
        });
    }


    use DatabaseTransactions;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testUserCanRegister()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->pause(1000)
                ->assertPresent('#name')
                ->assertPresent('#email')
                ->assertPresent('#user_type')
                ->assertPresent('#street')
                ->assertPresent('#house_number')
                ->assertPresent('#city')
                ->assertPresent('#zip_code')
                ->assertPresent('#password')
                ->assertPresent('#password_confirmation')
                ->assertPresent('#register-as-button')
                ->type('#name', 'John Dusk')
                ->type('#email', 'john-dusk@example.com')
                ->select('#user_type', 'private')
                ->type('#street', '123 Main St')
                ->type('#house_number', '456')
                ->type('#city', 'Anytown')
                ->type('#zip_code', '12345')
                ->type('#password', 'password123')
                ->type('#password_confirmation', 'password123')
                ->click('#register-as-button')
                ->assertPathIs('/');
        });
    }
}




