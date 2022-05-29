<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-nav-link>
    <x-nav-link :href="route('club')" :active="request()->routeIs('club')">
        {{ __('Club') }}
    </x-nav-link>
    <x-nav-link :href="route('squad')" :active="request()->routeIs('squad')">
        {{ __('Squad') }}
    </x-nav-link>
    <x-nav-link :href="route('league')" :active="request()->routeIs('league')">
        {{ __('League') }}
    </x-nav-link>
    <x-nav-link :href="route('cup')" :active="request()->routeIs('cup')">
        {{ __('Cup') }}
    </x-nav-link>
    <x-nav-link :href="route('media')" :active="request()->routeIs('media')">
        {{ __('Media') }}
    </x-nav-link>
</div>
