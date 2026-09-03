<x-layouts::auth :title="__('Log in')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />


        <form method="POST" action="{{ route('login.store') }}" novalidate class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Log in') }}
                </flux:button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('click', function (e) {
                const eyeBtn = e.target.closest('button[data-flux-input-viewable], [data-flux-input-viewable], button[aria-label*="password" i]');
                if (eyeBtn) {
                    const parent = eyeBtn.closest('.relative, div') || eyeBtn.parentElement;
                    const pwdInput = parent ? parent.querySelector('input[name="password"]') : document.querySelector('input[name="password"]');
                    if (pwdInput) {
                        pwdInput.type = pwdInput.type === 'password' ? 'text' : 'password';
                    }
                }
            });
        });
    </script>
</x-layouts::auth>
