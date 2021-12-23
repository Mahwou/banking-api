<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserAcountTest extends TestCase
{
    // use RefreshDatabase;
    protected $user;

    public function setUp():void
    {
        parent::setUp();
        $user = User::create([
            'name' => 'admin',
            'email'=> 'admin@admin.com',
            'password' => 'admin'
        ]);
        $this->actingAs($user);
    }

    public function test_create_account()
    {
        $formData = [
            'name'    => "large",
            'balance' => 200,
        ];
        $this->withoutExceptionHandling();
        $this->post(route('account'),$formData)
        ->assertStatus(200);
    }

    // public function test_transfer_cash()
    // {
    //     $formData = [
    //         'id'    => 1,
    //         'amount' => 50,
    //         'accountNum' => 2
    //     ];
    //     $this->withoutExceptionHandling();
    //     $this->post(route('transfer'),$formData)
    //     ->assertStatus(200);
    // }

    public function test_account_hitory()
    {
        $this->post(route('history', 1))
        ->assertStatus(405);
    }

    public function test_account_balance()
    {
        $this->post(route('balance', 1))
        ->assertStatus(405);
    }
}
