<?php

namespace Tests\Unit;

use Tests\TestCase;

class UserAcountTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function test_register()
    {
        $response = $this->post(route('register'), [
            'name' => 'admin',
            'email' => 'admin@admin.com1',
            'password' => 'admin1234',
            'password_confirmation' => 'admin1234'
        ]);

        $response->assertStatus(302);
    }

    public function test_login()
    {
        $response = $this->post(route('login'), [
            'email' => 'admin@admin.com1',
            'password' => 'admin1234'
        ]);

        $response->assertStatus(302);
    }

    
}
