<?php

use App\Livewire\Settings\SecurityCredentials;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::firstOrCreate(
        ['email' => 'admin@anagkazo.co.tz'],
        [
            'name' => 'Super Admin',
            'role' => 'admin',
            'password' => Hash::make('superuser@2026'),
        ]
    );

    $this->staff = User::firstOrCreate(
        ['email' => 'staff@anagkazo.co.tz'],
        [
            'name' => 'Sales Staff',
            'role' => 'staff',
            'password' => Hash::make('staffwaanagkazo@2026'),
        ]
    );
});

test('unauthenticated guests cannot access security credentials page', function () {
    $response = $this->get(route('security-credentials.index'));
    $response->assertRedirect(route('login'));
});

test('staff users are forbidden from accessing security credentials', function () {
    $this->actingAs($this->staff);

    $response = $this->get(route('security-credentials.index'));
    $response->assertStatus(403);
});

test('admin can view security credentials page and see both admin and staff details', function () {
    $this->actingAs($this->admin);

    $response = $this->get(route('security-credentials.index'));
    $response->assertStatus(200);
    $response->assertSee('Security Credentials');
    $response->assertSee('Administrator Credentials');
    $response->assertSee('Staff Login Credentials');
});

test('admin can update admin credentials and is logged out immediately', function () {
    $this->actingAs($this->admin);

    Livewire::test(SecurityCredentials::class)
        ->set('admin_name', 'Lead Administrator')
        ->set('admin_email', 'chief.admin@anagkazo.co.tz')
        ->set('admin_password', 'NewSuperSecret@2026')
        ->set('admin_password_confirmation', 'NewSuperSecret@2026')
        ->call('updateAdminCredentials')
        ->assertRedirect(route('login'));

    $this->admin->refresh();
    expect($this->admin->name)->toBe('Lead Administrator');
    expect($this->admin->email)->toBe('chief.admin@anagkazo.co.tz');
    expect(Hash::check('NewSuperSecret@2026', $this->admin->password))->toBeTrue();
    expect(Auth::check())->toBeFalse();
});

test('admin can update staff credentials, staff token is refreshed, and admin remains logged in', function () {
    $this->actingAs($this->admin);

    Livewire::test(SecurityCredentials::class)
        ->call('selectStaff', $this->staff->id)
        ->set('staff_name', 'Senior Desk Staff')
        ->set('staff_email', 'sales.head@anagkazo.co.tz')
        ->set('staff_password', 'NewStaffSecret@2026')
        ->set('staff_password_confirmation', 'NewStaffSecret@2026')
        ->call('updateStaffCredentials')
        ->assertSee('Senior Desk Staff');

    $this->staff->refresh();
    expect($this->staff->name)->toBe('Senior Desk Staff');
    expect($this->staff->email)->toBe('sales.head@anagkazo.co.tz');
    expect(Hash::check('NewStaffSecret@2026', $this->staff->password))->toBeTrue();
    expect(Auth::check())->toBeTrue();
    expect(Auth::id())->toBe($this->admin->id);
});

test('admin can create a new staff account and admin remains logged in', function () {
    $this->actingAs($this->admin);

    Livewire::test(SecurityCredentials::class)
        ->set('new_staff_name', 'Wholesale Desk Kariakoo')
        ->set('new_staff_email', 'kariakoo.desk@anagkazo.co.tz')
        ->set('new_staff_password', 'Kariakoo@2026')
        ->set('new_staff_password_confirmation', 'Kariakoo@2026')
        ->call('createStaff')
        ->assertSee('Wholesale Desk Kariakoo');

    $newStaff = User::where('email', 'kariakoo.desk@anagkazo.co.tz')->first();
    expect($newStaff)->not->toBeNull();
    expect($newStaff->name)->toBe('Wholesale Desk Kariakoo');
    expect($newStaff->role)->toBe('staff');
    expect(Hash::check('Kariakoo@2026', $newStaff->password))->toBeTrue();
    expect(Auth::check())->toBeTrue();
    expect(Auth::id())->toBe($this->admin->id);
});
