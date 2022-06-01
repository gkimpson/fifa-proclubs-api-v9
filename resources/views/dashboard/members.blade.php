<x-app-layout>
    <x-proclubs.breadcrumbs :breadcrumbs="$breadcrumbs" />

    <x-slot name="slot">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <div class="flex flex-wrap grid gap-4 grid-cols-2">
                            @foreach ($members as $memberKey => $member)
                            <div>
                                <div class="flex max-w-md mx-auto overflow-hidden bg-white rounded-lg shadow-lg dark:bg-gray-800">
                                    <div class="w-1/3 bg-cover hidden md:inline" style="background-image: url('images/silhouettes/player{{ rand(1, 3) }}.jpeg')"></div>
                                    <div class="w-2/3 p-4 md:p-4">
                                        <h1 class="text-sm md:text-2xl font-bold text-gray-800 dark:text-white">{{ $member->name }}</h1>
                                        <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">{{ ucfirst($member->favoritePosition) }} <span class="text-xs">({{ $member->proHeight }}cm)</span></p>

                                        {{-- <div class="flex mt-2 item-center">
                                            <svg class="w-5 h-5 text-gray-700 fill-current dark:text-gray-300" viewBox="0 0 24 24">
                                                <path d="M12 17.27L18.18 21L16.54 13.97L22 9.24L14.81 8.63L12 2L9.19 8.63L2 9.24L7.46 13.97L5.82 21L12 17.27Z"/>
                                            </svg>

                                            <svg class="w-5 h-5 text-gray-700 fill-current dark:text-gray-300" viewBox="0 0 24 24">
                                                <path d="M12 17.27L18.18 21L16.54 13.97L22 9.24L14.81 8.63L12 2L9.19 8.63L2 9.24L7.46 13.97L5.82 21L12 17.27Z"/>
                                            </svg>

                                            <svg class="w-5 h-5 text-gray-700 fill-current dark:text-gray-300" viewBox="0 0 24 24">
                                                <path d="M12 17.27L18.18 21L16.54 13.97L22 9.24L14.81 8.63L12 2L9.19 8.63L2 9.24L7.46 13.97L5.82 21L12 17.27Z"/>
                                            </svg>

                                            <svg class="w-5 h-5 text-gray-500 fill-current" viewBox="0 0 24 24">
                                                <path d="M12 17.27L18.18 21L16.54 13.97L22 9.24L14.81 8.63L12 2L9.19 8.63L2 9.24L7.46 13.97L5.82 21L12 17.27Z"/>
                                            </svg>

                                            <svg class="w-5 h-5 text-gray-500 fill-current" viewBox="0 0 24 24">
                                                <path d="M12 17.27L18.18 21L16.54 13.97L22 9.24L14.81 8.63L12 2L9.19 8.63L2 9.24L7.46 13.97L5.82 21L12 17.27Z"/>
                                            </svg>
                                        </div> --}}
                                        <img class="hidden md:inline" src="https://media.contentapi.ea.com/content/dam/ea/fifa/fifa-21/ratings-collective/f20assets/country-flags/{{ $member->proNationality }}.png" alt="Nationality Flag">

                                        <div class="flex justify-between mt-3 item-center">
                                            <h2 class="text-lg font-bold text-gray-500 dark:text-gray-200 md:text-xl">{{ $member->proOverall }} - {{ $member->proPosition }}</h2>
                                            <div class="rounded text-sm">
                                                <table>
                                                    <tr><td class="px-3"><i class="fa-solid fa-futbol-ball" title="Goals scored"></i></td><td>{{ $member->goals }}</td></tr>
                                                    <tr><td class="px-3"><i class="fa-solid fa-handshake-angle" title="Assists"></i></td><td>{{ $member->assists }}</td></tr>
                                                    <tr><td class="px-3"><i class="fa-solid fa-award" title="Man of the Match awards"></i></td><td>{{ $member->manOfTheMatch }}</td></tr>
                                                    <tr><td class="px-3">Win</td><td>{{ $member->winRate }} <i class="fa-solid fa-percent" title="Win percentage"></i></td></tr>
                                                </table>
                                            </div>

                                            <?php /**
                                            <a href="/memberstats/{{ $platform }}/{{ $member->name }}">
                                            <button class="px-2 py-1 text-xs font-bold text-white uppercase transition-colors duration-200 transform bg-gray-800 rounded dark:bg-gray-700 hover:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:bg-gray-700 dark:focus:bg-gray-600">FULL STATS</button>
                                            </a>
                                            */ ?>



                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>





                    </div>
                </div>
            </div>
        </div>
    </x-slot>

</x-app-layout>
