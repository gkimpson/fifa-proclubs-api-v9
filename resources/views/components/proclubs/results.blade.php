<div x-data="{selected:null}" class="inline-block min-w-full shadow rounded-lg overflow-hidden">
    <table class="min-w-full leading-normal">
        <thead>
        <tr>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Home</th>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"></th>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"></th>
            <th class="hidden sm:table-cell px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"></th>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Away</th>
        </tr>
        </thead>
        <tbody>
        <div class="px-2 py-2 md:px-5 md:py-5 flex-1">{{ $results->links() }}</div>
        <div class="px-5 py-1">
            Current Streak - {{ $streaks['current']['streak'] }}
            @if ($streaks['current']['type'] === 'W') Wins @endif
            @if ($streaks['current']['type'] === 'D') Draws @endif
            @if ($streaks['current']['type'] === 'L') Losses @endif
        </div>
        <div class="px-5 py-1">
            Max Streaks - Wins {{ $streaks['max']['W']['amount'] }} -- Losses {{ $streaks['max']['L']['amount'] }} -- Draws {{ $streaks['max']['D']['amount'] }}
        </div>

        @foreach ($results as $key => $result)
            <!-- START MOBILE ROWS -->
            <tr class="md:hidden">
                <td class="visible sm:table-cell bg-white text-sm">
                    <div class="mx-5 px-3 py-3">
                        <img class="w-full h-full rounded-full" src="{{$result->home_team_crest_url}}" alt="Crest" />
                    </div>
                </td>
                <td class="visible sm:table-cell text-center bg-white text-xs text-gray-500" colspan="2">
                    {{ $result->match_date->diffForHumans() }}
                    <div>
                        <button @click="selected !== {{ $loop->iteration }} ? selected = {{ $loop->iteration }} : selected = null">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                            </svg>
                        </button>
                    </div>
                    <div>
                    @if (count($result->media_ids) > 0)
                    <div class="flex flex-row">
                        @foreach ($result->media_ids as $key => $media_id)
                            <div>
                                <a href="//www.youtube.com/watch?v={{ $media_id }}" data-lity>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    @endif
                    </div>
                    </div>

                    <div>
                        <!-- start of modal -->
                        <div
                            x-data="{ 'showModal': false }"
                            @keydown.escape="showModal = false"
                        >
                            <!-- Trigger for Modal -->
                            <button type="button" @click="showModal = true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                </svg>
                            </button>
                            <!-- Modal -->
                            <div style="display: none;"
                                 class="fixed inset-0 z-30 flex items-center justify-center overflow-auto bg-black bg-opacity-50"
                                 x-show="showModal"
                            >
                                <!-- Modal inner -->
                                <div
                                    class="max-w-3xl px-6 py-4 mx-auto text-left bg-white rounded shadow-lg"
                                    @click.away="showModal = false"
                                    x-transition:enter="motion-safe:ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-90"
                                    x-transition:enter-end="opacity-100 scale-100"
                                >
                                    <!-- Title / Close-->
                                    <div class="flex items-center justify-between">
                                        <h5 class="mr-3 text-black max-w-none">Add YouTube Highlight(s)</h5>
                                        <button type="button" class="z-50 cursor-pointer" @click="showModal = false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <form class="w-64 mx-auto" x-data="contactForm({{ $result->match_id }})" @submit.prevent="submitData">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="block mb-2">Youtube URL:</label>
                                            <input type="text" class="border w-full p-1" x-model="formData.youtubeURL" placeholder="www.youtube.com/watch?v=xxxxxxx" required>
                                        </div>
                                        <button @click="showModal = false" class="bg-gray-700 hover:bg-gray-800 disabled:opacity-50 text-white w-full p-2 mb-4" x-text="buttonLabel"
                                                :disabled="loading"></button>
                                        {{-- <div x-text="JSON.stringify(formData)"> --}}
                                        {{-- <p x-text="message"></p> --}}
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end of modal -->
                    </div>
                </td>
                <td class="visible sm:table-cell bg-white text-sm">
                    <div class="mx-5 px-3 py-3">
                        <img class="w-full h-full rounded-full" src="{{$result->away_team_crest_url}}" alt="Crest" />
                    </div>
                </td>
            </tr>
            <!-- END MOBILE ROWS -->


            <!-- START DESKTOP ROWS -->
            <tr data-matchId="{{ $result->match_id }}">
                <td class="px-2 py-2 md:px-5 md:py-5 border-b border-gray-200 bg-white text-sm w-2/5
                        @if($result->outcome === 'homewin' && $result->home_team_id === $myClubId) bg-green-200
                        @elseif($result->outcome === 'awaywin' && $result->home_team_id === $myClubId) bg-red-200
                        @endif">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-10 h-10 hidden sm:table-cell">
                            <img class="w-full h-full rounded-full" src="{{$result->home_team_crest_url}}" alt="Crest" />
                        </div>
                        <div class="ml-3">
                            <p class="text-gray-900 whitespace-no-wrap">{{ $result->properties['clubs'][0]['name'] }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap text-center">{{ $result->home_team_goals }}</p>
                </td>
                <td class="hidden md:table-cell border-b border-gray-200 bg-white text-xs text-center text-gray-500">
                    <div>{{ $result->match_date->diffForHumans() }}</div>
                    <!-- show stats -->
                    <button @click="selected !== {{ $loop->iteration }} ? selected = {{ $loop->iteration }} : selected = null">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                    </button>

                    <div>
                        <!-- start of modal -->
                        <div
                            x-data="{ 'showModal': false }"
                            @keydown.escape="showModal = false"
                        >
                            <!-- Trigger for Modal -->
                            <button type="button" @click="showModal = true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                </svg>
                            </button>
                            <!-- Modal -->
                            <div style="display: none;"
                                 class="fixed inset-0 z-30 flex items-center justify-center overflow-auto bg-black bg-opacity-50"
                                 x-show="showModal"
                            >
                                <!-- Modal inner -->
                                <div
                                    class="max-w-3xl px-6 py-4 mx-auto text-left bg-white rounded shadow-lg"
                                    @click.away="showModal = false"
                                    x-transition:enter="motion-safe:ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-90"
                                    x-transition:enter-end="opacity-100 scale-100"
                                >
                                    <!-- Title / Close-->
                                    <div class="flex items-center justify-between">
                                        <h5 class="mr-3 text-black max-w-none">Add YouTube Highlight(s)</h5>
                                        <button type="button" class="z-50 cursor-pointer" @click="showModal = false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <form class="w-64 mx-auto" x-data="contactForm({{ $result->match_id }})" @submit.prevent="submitData">
                                        @csrf
                                        <div class="mb-4">
                                            {{-- {{ $result->match_id }} --}}
                                            <label class="block mb-2">Youtube URL:</label>
                                            <input type="text" class="border w-full p-1" x-model="formData.youtubeURL" placeholder="www.youtube.com/watch?v=xxxxxxx" required>
                                        </div>
                                        <button @click="showModal = false" class="bg-gray-700 hover:bg-gray-800 disabled:opacity-50 text-white w-full p-2 mb-4" x-text="buttonLabel"
                                                :disabled="loading"></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end of modal -->
                    </div>

                    <div>
                    @if (count($result->media_ids) > 0)
                    <div class="flex flex-row">
                        @foreach ($result->media_ids as $key => $media_id)
                            <div>
                                <a href="//www.youtube.com/watch?v={{ $media_id }}" data-lity>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    @endif
                        </div>
                    </div>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap text-center">
                        {{ $result->away_team_goals }}
                    </p>
                </td>
                <td class="px-2 py-2 md:px-5 md:py-5 border-b border-gray-200 bg-white text-sm w-2/5
                                    @if($result->outcome === 'awaywin' && $result->away_team_id === $myClubId) bg-green-200
                                    @elseif($result->outcome === 'homewin' && $result->away_team_id === $myClubId) bg-red-200
                                    @endif">
                    <div class="flex items-center float-right">
                        <div class="mr-3">
                            <p class="text-gray-900 whitespace-no-wrap text-right">{{ $result->properties['clubs'][1]['name'] }}</p>
                        </div>
                        <div class="flex-shrink-0 w-10 h-10 hidden sm:table-cell">
                            <img class="w-full h-full rounded-full" src="{{$result->away_team_crest_url}}" alt="Crest" />
                        </div>
                    </div>
                </td>
            </tr>
            <!-- END DESKTOP ROWS -->

            <tr style="display: none;" x-show="selected == {{ $loop->iteration }}">
                <td class="text-center text-xs" colspan="5">
                    <div class="flex flex-row">
                        <div class="basis-1/4">
                            @isset($result->top_rated_players[$result->home_team_id])
                                @foreach ($result->top_rated_players[$result->home_team_id] as $k => $player)
                                    <div class="flex items-center flex justify-center items-center py-1 px-1">
                                        <div class="flex flex-col justify-between bg-white rounded-lg border border-gray-400 py-1 px-1">
                                            <div>
                                                <h4 tabindex="0" class="focus:outline-none text-gray-800 font-bold">{{ $player->name }}</h4>
                                                <p tabindex="0" class="focus:outline-none text-gray-800 text-sm"></p>
                                            </div>
                                            <div>
                                                @isset($player->properties['goals'])
                                                    @for ($i = 0; $i < $player->properties['goals']; $i++)
                                                        <span><i class="fa-solid fa-futbol"></i><span>
                                                                @endfor
                                                                @endif

                                                                @isset($player->properties['goals'])
                                                                    @for ($i = 0; $i < $player->properties['assists']; $i++)
                                                                        <span><i class="fa-solid fa-handshake-angle"></i></span>
                                                                    @endfor
                                                                @endif

                                                                @isset($player->properties['tacklesmade'])
                                                                    @for ($i = 0; $i < $player->properties['tacklesmade']; $i++)
                                                                        <span><i class="fa-solid fa-bandage"></i></span>
                                                                    @endfor
                                                @endif
                                            </div>

                                            <div class="flex w-full items-center flex justify-center items-center py-1 px-4">
                                                <p tabindex="0" class="focus:outline-none text-sm">{{ $player->rating }} </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endisset
                        </div>
                        <div class="basis-1/2">
                            @isset($result->properties['aggregate'])
                                <div class="grid grid-cols-3 mx-auto border-b py-2">
                                    <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->home_team_id]['shots'] }}</div>
                                    <div class="text-center text-xs md:text-sm">Shots on Target</div>
                                    <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->away_team_id]['shots'] }}</div>
                                </div>
                                <div class="grid grid-cols-3 mx-auto border-b py-2">
                                    <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->home_team_id]['saves'] }}</div>
                                    <div class="text-center text-xs md:text-sm">Saves (Human GK)</div>
                                    <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->away_team_id]['saves'] }}</div>
                                </div>
                                <div class="grid grid-cols-3 mx-auto border-b py-2">
                                    <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->home_team_id]['redcards'] }}</div>
                                    <div class="text-center text-xs md:text-sm">Red Cards</div>
                                    <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->away_team_id]['redcards'] }}</div>
                                </div>
                                <div class="grid grid-cols-3 mx-auto border-b py-2">
                                    <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->home_team_id]['tacklesmade'] }}</div>
                                    <div class="text-center text-xs md:text-sm">Tackles Made</div>
                                    <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->away_team_id]['tacklesmade'] }}</div>
                                </div>
                                <div class="grid grid-cols-3 mx-auto border-b py-2">
                                    <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->home_team_id]['tackleattempts'] }}</div>
                                    <div class="text-center text-xs md:text-sm">Tackles Attempts</div>
                                    <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->away_team_id]['tackleattempts'] }}</div>
                                </div>
                                <div class="grid grid-cols-3 mx-auto border-b py-2">
                                    <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->home_team_id]['assists'] }}</div>
                                    <div class="text-center text-xs md:text-sm">Assists</div>
                                    <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->away_team_id]['assists'] }}</div>
                                </div>
                                <div class="grid grid-cols-3 mx-auto border-b py-2">
                                    <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->home_team_id]['passesmade'] }}</div>
                                    <div class="text-center text-xs md:text-sm">Passes Made</div>
                                    <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->away_team_id]['passesmade'] }}</div>
                                </div>
                                <div class="grid grid-cols-3 mx-auto border-b py-2">
                                    <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->home_team_id]['passattempts'] }}</div>
                                    <div class="text-center text-xs md:text-sm">Pass Attempts</div>
                                    <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->away_team_id]['passattempts'] }}</div>
                                </div>
                            @else
                                <div>No stats recorded for this match</div>
                            @endif
                        </div>
                        <div class="basis-1/4">
                            @isset($result->top_rated_players[$result->away_team_id])
                                @foreach ($result->top_rated_players[$result->away_team_id] as $k => $player)
                                    <div class="flex items-center flex justify-center items-center py-1 px-1">
                                        <div class="flex flex-col justify-between bg-white rounded-lg border border-gray-400 py-1 px-1">
                                            <div>
                                                <h4 tabindex="0" class="focus:outline-none text-gray-800 font-bold">{{ $player->name }}</h4>
                                                <p tabindex="0" class="focus:outline-none text-gray-800 text-sm"></p>
                                            </div>

                                            <div>
                                                @isset($player->properties['goals'])
                                                    @for ($i = 0; $i < $player->properties['goals']; $i++)
                                                        <span><i class="fa-solid fa-futbol"></i><span>
                                                                @endfor
                                                                @endif

                                                                @isset($player->properties['goals'])
                                                                    @for ($i = 0; $i < $player->properties['assists']; $i++)
                                                                        <span><i class="fa-solid fa-handshake-angle"></i></span>
                                                                    @endfor
                                                                @endif

                                                                @isset($player->properties['tacklesmade'])
                                                                    @for ($i = 0; $i < $player->properties['tacklesmade']; $i++)
                                                                        <span><i class="fa-solid fa-bandage"></i></span>
                                                    @endfor
                                                @endif
                                            </div>

                                            <div class="flex w-full items-center flex justify-center items-center py-1 px-4">
                                                <p tabindex="0" class="focus:outline-none text-sm">{{ $player->rating }} </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endisset
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
