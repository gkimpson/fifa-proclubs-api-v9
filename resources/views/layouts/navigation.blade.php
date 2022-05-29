<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img width="50" src="https://fifa21.content.easports.com/fifa/fltOnlineAssets/05772199-716f-417d-9fe0-988fa9899c4d/2021/fifaweb/crests/256x256/{{ $user->getEmblemId() }}.png" alt="emblem">
                    </a>
                </div>

            @component('components.proclubs.nav')
            @endcomponent
            </div>

            <!-- Settings Dropdown -->
            @component('components.proclubs.settings-dropdown')
            @endcomponent

            <!-- Hamburger -->
            @component('components.proclubs.hamburger')
            @endcomponent
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    @component('components.proclubs.responsive-nav')
    @endcomponent
</nav>
