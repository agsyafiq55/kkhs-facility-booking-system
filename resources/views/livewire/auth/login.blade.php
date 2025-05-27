<div class="flex flex-col gap-6">
    <div class="text-center lg:text-left">
        <flux:heading size="xl" class="mb-2 text-white">Log in to your account</flux:heading>
        <flux:text class="text-gray-300">Enter your email and password below to log in</flux:text>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autofocus
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Password -->
        <div class="relative">
            <flux:input
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />

            @if (Route::has('password.request'))
                <flux:link class="absolute end-0 top-0 text-sm" :href="route('password.request')" wire:navigate>
                    {{ __('Forgot your password?') }}
                </flux:link>
            @endif
        </div>

        <!-- Remember Me -->
        <flux:checkbox wire:model="remember" :label="__('Remember me')" />

        <div>
            <flux:button variant="primary" type="submit" class="w-full">{{ __('Log in') }}</flux:button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="text-center text-sm text-gray-300">
            {{ __('Don\'t have an account?') }}
            <flux:link :href="route('register')" wire:navigate class="ml-1 text-white">{{ __('Sign up') }}</flux:link>
        </div>
    @endif
</div>
