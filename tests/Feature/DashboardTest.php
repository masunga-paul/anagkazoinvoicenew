<?php

use App\Models\User;

test('unauthenticated guests are redirected to login from dashboard', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated admin users can visit the dashboard', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('dashboard links products and customers to their respective management pages', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertOk();

    // Verify product links
    $response->assertSee(route('products.index'), false);
    $response->assertSee('Open Stock Manager', false);

    // Verify customer directory links
    $response->assertSee(route('customers.index'), false);
});

test('admin can log in with created credentials', function () {
    $this->seed(\Database\Seeders\DatabaseSeeder::class);

    $response = $this->post(route('login.store'), [
        'email' => 'admin@anagkazo.co.tz',
        'password' => 'superuser@2026',
    ]);

    $response->assertRedirect(route('dashboard', absolute: false));
    $this->assertAuthenticated();
});

test('staff can log in with created credentials', function () {
    $this->seed(\Database\Seeders\DatabaseSeeder::class);

    $response = $this->post(route('login.store'), [
        'email' => 'staff@anagkazo.co.tz',
        'password' => 'staffwaanagkazo@2026',
    ]);

    $response->assertRedirect(route('dashboard', absolute: false));
    $this->assertAuthenticated();
});

