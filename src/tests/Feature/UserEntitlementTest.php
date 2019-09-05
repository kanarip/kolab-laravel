<?php

namespace Tests\Feature;

use App\Entitlement;
use App\Sku;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserEntitlementTest extends TestCase
{
    public function testUserAddEntitlement()
    {
        $sku = Sku::firstOrCreate(
            ['title' => 'individual']
        );

        $owner = User::firstOrCreate(
            ['email' => 'UserEntitlement1@UserEntitlement.com']
        );

        $user = User::firstOrCreate(
            ['email' => 'UserEntitled1@UserEntitlement.com']
        );

        $wallets = $owner->wallets()->get();

        $entitlement = Entitlement::firstOrCreate(
            [
                'owner_uuid' => $owner->uuid,
                'user_uuid' => $user->uuid,
                'wallet_uuid' => $wallets[0]->uuid,
                'sku_uuid' => $sku->uuid,
                'description' => "User Entitlement Test"
            ]
        );

        $owner->entitlements()->save($entitlement);

        $this->assertTrue($owner->entitlements()->count() == 1);
        $this->assertTrue($sku->entitlements()->count() == 1);
        $this->assertTrue($wallets[0]->entitlements()->count() == 1);

        $this->assertTrue($wallets[0]->fresh()->balance < 0.00);
    }

    public function testUserEntitlements()
    {
        $userA = User::firstOrCreate(
            [
                'email' => 'UserEntitlement2A@UserEntitlement.com'
            ]
        );

        $response = $this->actingAs($userA)->get("/api/user");

        $response->dumpHeaders();
        $response->dump();

        $response->assertStatus(200);
    }
}
