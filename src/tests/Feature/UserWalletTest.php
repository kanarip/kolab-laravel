<?php

namespace Tests\Feature;

use App\User;
use App\Wallet;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserWalletTest extends TestCase
{
    /**
        Verify a wallet is created, when a user is created.

        @return void
     */
    public function testCreateUserCreatesWallet()
    {
        $user = User::firstOrCreate(
            [
                'email' => 'UserWallet1@UserWallet.com'
            ]
        );

        $this->assertTrue($user->wallets()->count() == 1);
    }

    public function testAddWallet()
    {
        $user = User::firstOrCreate(
            [
                'email' => 'UserWallet2@UserWallet.com'
            ]
        );

        $user->wallets()->save(
            new Wallet(['currency' => 'USD'])
        );

        $this->assertTrue($user->wallets()->count() >= 2);

        $user->wallets()->each(
            function ($wallet) {
                $this->assertTrue($wallet->balance === 0.00);
            }
        );
    }
}
