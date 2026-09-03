<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.empty')]
#[Title('Security Credentials - Anagkazo Autoparts')]
class SecurityCredentials extends Component
{
    // Admin Fields
    public ?int $admin_id = null;
    public string $admin_name = '';
    public string $admin_email = '';
    public string $admin_password = '';
    public string $admin_password_confirmation = '';

    // Staff Fields
    public ?int $staff_id = null;
    public string $staff_name = '';
    public string $staff_email = '';
    public string $staff_password = '';
    public string $staff_password_confirmation = '';

    // New Staff Modal / Form
    public bool $showNewStaffForm = false;
    public string $new_staff_name = '';
    public string $new_staff_email = '';
    public string $new_staff_password = '';
    public string $new_staff_password_confirmation = '';

    // Selected staff for editing
    public ?int $selected_staff_id = null;

    public function mount(): void
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            session()->flash('error', 'Unauthorized access: Security credentials management is restricted to Administrators.');
            $this->redirect(route('invoices.create'), navigate: true);
            return;
        }

        $admin = auth()->user();
        $this->admin_id = $admin->id;
        $this->admin_name = $admin->name;
        $this->admin_email = $admin->email;

        // Load first staff user if available
        $staff = User::where('role', 'staff')->first();
        if ($staff) {
            $this->selectStaff($staff->id);
        }
    }

    public function selectStaff(int $id): void
    {
        $staff = User::where('role', 'staff')->find($id);
        if ($staff) {
            $this->staff_id = $staff->id;
            $this->selected_staff_id = $staff->id;
            $this->staff_name = $staff->name;
            $this->staff_email = $staff->email;
            $this->staff_password = '';
            $this->staff_password_confirmation = '';
        }
    }

    public function updateAdminCredentials()
    {
        $this->validate([
            'admin_name' => 'required|string|max:255',
            'admin_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->admin_id),
            ],
            'admin_password' => 'nullable|string|min:6|confirmed',
        ]);

        $admin = User::findOrFail($this->admin_id);
        $admin->name = $this->admin_name;
        $admin->email = $this->admin_email;

        if (! empty($this->admin_password)) {
            $admin->password = Hash::make($this->admin_password);
            $admin->remember_token = \Illuminate\Support\Str::random(60);
        }

        $admin->save();

        // Invalidate admin sessions and log out only the admin
        try {
            \Illuminate\Support\Facades\DB::table('sessions')->where('user_id', $admin->id)->delete();
        } catch (\Throwable $e) {
        }

        Auth::guard('web')->logout();
        if (request()->hasSession()) {
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }

        return redirect()->route('login')->with('status', 'Admin credentials updated successfully. Please sign in with your new login details.');
    }

    public function updateStaffCredentials()
    {
        $this->validate([
            'staff_id' => 'required|exists:users,id',
            'staff_name' => 'required|string|max:255',
            'staff_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->staff_id),
            ],
            'staff_password' => 'nullable|string|min:6|confirmed',
        ]);

        $staff = User::findOrFail($this->staff_id);
        $staff->name = $this->staff_name;
        $staff->email = $this->staff_email;

        if (! empty($this->staff_password)) {
            $staff->password = Hash::make($this->staff_password);
            $staff->remember_token = \Illuminate\Support\Str::random(60);
        }

        $staff->save();

        // Terminate any active sessions for this staff member so they are logged out
        try {
            \Illuminate\Support\Facades\DB::table('sessions')->where('user_id', $staff->id)->delete();
        } catch (\Throwable $e) {
        }

        // Reset password fields
        $this->staff_password = '';
        $this->staff_password_confirmation = '';

        session()->flash('staff_success', "Staff credentials for '{$staff->name}' ({$staff->email}) updated successfully. Any active staff session has been logged out.");
    }

    public function createStaff()
    {
        $this->validate([
            'new_staff_name' => 'required|string|max:255',
            'new_staff_email' => 'required|email|max:255|unique:users,email',
            'new_staff_password' => 'required|string|min:6|confirmed',
        ]);

        $newStaff = User::create([
            'name' => $this->new_staff_name,
            'email' => $this->new_staff_email,
            'role' => 'staff',
            'password' => Hash::make($this->new_staff_password),
            'remember_token' => \Illuminate\Support\Str::random(60),
        ]);

        $this->showNewStaffForm = false;
        $this->new_staff_name = '';
        $this->new_staff_email = '';
        $this->new_staff_password = '';
        $this->new_staff_password_confirmation = '';

        $this->selectStaff($newStaff->id);

        session()->flash('staff_success', "New staff account '{$newStaff->name}' ({$newStaff->email}) created successfully.");
    }

    public function render()
    {
        $staffMembers = User::where('role', 'staff')->orderBy('name')->get();

        return view('livewire.settings.security-credentials', [
            'staffMembers' => $staffMembers,
        ]);
    }
}
