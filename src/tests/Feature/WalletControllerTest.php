<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WalletControllerTest extends TestCase
{
    /**
        Verify a wallet can be assigned a controller.

        @return void
     */
    public function testAssignWalletController()
    {
        $userA = User::firstOrCreate(
            [
                'email' => 'WalletControllerA@WalletController.com'
            ]
        );

        $userA->wallets()->each(
            function ($wallet) {
                $userB = User::firstOrCreate(
                    [
                        'email' => 'WalletControllerB@WalletController.com'
                    ]
                );

                $wallet->addController($userB);
            }
        );

        $userB = User::firstOrCreate(
            [
                'email' => 'WalletControllerB@WalletController.com'
            ]
        );

        $this->assertTrue($userB->accounts()->count() == 1);

        $aWallet = $userA->wallets()->get();
        $bAccount = $userB->accounts()->get();

        $this->assertTrue($bAccount[0]->uuid === $aWallet[0]->uuid);
    }
}
