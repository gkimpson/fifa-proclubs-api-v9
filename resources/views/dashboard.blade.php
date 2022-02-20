<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div
    class="container mx-auto px-4 sm:px-8">


        <div class="py-8">            
            <div>
                <h2 class="text-2xl font-semibold leading-tight">Matches</h2>
            </div>
            <div class="mx-auto px-4 sm:px-8 text-center">
                <p class="p-1"><a href="//www.youtube.com/watch?v=M82Eua9wkQc" data-lity>GOAL OF THE MONTH</a><br></p>
            </div>
            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                <div x-data="{selected:null}" class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Home
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    
                                </th>
                                <th
                                    class="hidden sm:table-cell px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Away
                                </th>
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
                                Max Streaks - 
                                Wins {{ $streaks['max']['W']['amount'] }} -- Losses {{ $streaks['max']['L']['amount'] }} -- Draws {{ $streaks['max']['D']['amount'] }}
                        </div>                            
                            

                            @foreach ($results as $key => $result)
                            <tr class="md:hidden">
                                <td class="visible sm:table-cell bg-white text-sm">
                                    <div class="mx-5 px-3 py-3">
                                            <img class="w-full h-full rounded-full"
                                                src="{{$result->home_team_crest_url}}"
                                                alt="Crest" />
                                    </div>
                                </td>
                                <td class="visible sm:table-cell text-center bg-white text-xs text-gray-500" colspan="2">
                                    {{ $result->match_date }}<br>@isset($result->match_data)
                                    <button @click="selected !== {{ $loop->iteration }} ? selected = {{ $loop->iteration }} : selected = null">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                      </svg>
                                    </button>
                                    @endisset                       

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
                                    
                                                <!-- content -->
                                                {{-- <form class="highlightsForm">
                                                    <input type="hidden" name="matchId" value="{{ $result->match_id }}">
                                                    @csrf
                                                    <div class="mb-6">
                                                      <label for="youtube" class="text-sm font-medium text-gray-900 block mb-2">Youtube URL</label>
                                                      <input type="text" name="youtube" id="youtube" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="https://www.youtube.com/watch?v=" required>
                                                    </div>
                                                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Submit</button>
                                                </form>                                    --}}


                                                <form class="w-64 mx-auto" x-data="contactForm({{ $result->match_id }})" @submit.prevent="submitData">
                                                    @csrf
                                                    <div class="mb-4">
                                                        {{-- {{ $result->match_id }} --}}
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
                                                                          
                                </td>
                                <td class="visible sm:table-cell bg-white text-sm">
                                    <div class="mx-5 px-3 py-3">
                                        <img class="w-full h-full rounded-full"
                                            src="{{$result->away_team_crest_url}}"
                                            alt="Crest" />
                                    </div>                                 
                                </td>
                            </tr>
                            <tr data-matchId="{{ $result->match_id }}">
                                <td class="px-2 py-2 md:px-5 md:py-5 border-b border-gray-200 bg-white text-sm w-2/5 
                                @if($result->outcome === 'homewin' && $result->home_team_id === $myClubId) bg-green-200 
                                @elseif($result->outcome === 'awaywin' && $result->home_team_id === $myClubId) bg-red-200 
                                @endif">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-10 h-10 hidden sm:table-cell">
                                            <img class="w-full h-full rounded-full"
                                                src="{{$result->home_team_crest_url}}"
                                                alt="Crest" />
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                {{ $result->properties['clubs'][0]['name'] }}                                             
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap text-center">{{ $result->home_team_goals }}</p>
                                </td>
                                <td class="hidden md:table-cell border-b border-gray-200 bg-white text-xs text-center text-gray-500">

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
                                    
                                                <!-- content -->
                                                {{-- <form class="highlightsForm">
                                                    <input type="hidden" name="matchId" value="{{ $result->match_id }}">
                                                    @csrf
                                                    <div class="mb-6">
                                                      <label for="youtube" class="text-sm font-medium text-gray-900 block mb-2">Youtube URL</label>
                                                      <input type="text" name="youtube" id="youtube" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="https://www.youtube.com/watch?v=" required>
                                                    </div>
                                                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Submit</button>
                                                </form>                                    --}}


                                                <form class="w-64 mx-auto" x-data="contactForm({{ $result->match_id }})" @submit.prevent="submitData">
                                                    @csrf
                                                    <div class="mb-4">
                                                        {{-- {{ $result->match_id }} --}}
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
                                                  
                                    @isset($result->match_data)
                                    <button @click="selected !== {{ $loop->iteration }} ? selected = {{ $loop->iteration }} : selected = null">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                      </svg>
                                    </button>
                                    @endisset
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
                                            <p class="text-gray-900 whitespace-no-wrap text-right">
                                                {{ $result->properties['clubs'][1]['name'] }}                                                
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0 w-10 h-10 hidden sm:table-cell">
                                            <img class="w-full h-full rounded-full"
                                                src="{{$result->away_team_crest_url}}"
                                                alt="Crest" />
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr style="display: none;" x-show="selected == {{ $loop->iteration }}">
                                <td
                                    class="text-center text-xs" colspan="5">
                                    @isset($result->match_data)
                                    <div class="grid grid-cols-3 gap-4 w-100 md:w-1/2 mx-auto border-b py-2">
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->home_team_id]->shots }}</div>
                                        <div class="text-center text-xs md:text-sm">Shots on Target</div>
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->away_team_id]->shots }}</div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4 w-100 md:w-1/2 mx-auto border-b py-2">
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->home_team_id]->saves }}</div>
                                        <div class="text-center text-xs md:text-sm">Saves (Human GK)</div>
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->away_team_id]->saves }}</div>
                                    </div>                                    

                                    <div class="grid grid-cols-3 gap-4 w-100 md:w-1/2 mx-auto border-b py-2">
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->home_team_id]->redcards }}</div>
                                        <div class="text-center text-xs md:text-sm">Red Cards</div>
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->away_team_id]->redcards }}</div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4 w-100 md:w-1/2 mx-auto border-b py-2">
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->home_team_id]->tacklesmade }}</div>
                                        <div class="text-center text-xs md:text-sm">Tackles Made</div>
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->away_team_id]->tacklesmade }}</div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4 w-100 md:w-1/2 mx-auto border-b py-2">
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->home_team_id]->tackleattempts }}</div>
                                        <div class="text-center text-xs md:text-sm">Tackle Attempts</div>
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->away_team_id]->tackleattempts }}</div>
                                    </div>       
                                    
                                    <div class="grid grid-cols-3 gap-4 w-100 md:w-1/2 mx-auto border-b py-2">
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->home_team_id]->assists }}</div>
                                        <div class="text-center text-xs md:text-sm">Assists</div>
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->away_team_id]->assists }}</div>
                                    </div>                                      

                                    <div class="grid grid-cols-3 gap-4 w-100 md:w-1/2 mx-auto border-b py-2">
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->home_team_id]->passesmade }}</div>
                                        <div class="text-center text-xs md:text-sm">Passes Made</div>
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->away_team_id]->passesmade }}</div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4 w-100 md:w-1/2 mx-auto border-b py-2">
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->home_team_id]->passattempts }}</div>
                                        <div class="text-center text-xs md:text-sm">Pass Attempts</div>
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->away_team_id]->passattempts }}</div>
                                    </div> 

                                    {{-- <div class="grid grid-cols-3 gap-4 w-100 md:w-1/2 mx-auto border-b py-2">
                                        <div class="text-center text-xs md:text-sm">{{ round(($result->match_data[$result->home_team_id]->passesmade / $result->match_data[$result->home_team_id]->passattempts) * 100) }}%</div>
                                        <div class="text-center text-xs md:text-sm">Pass Completion %</div>
                                        <div class="text-center text-xs md:text-sm">{{ round(($result->match_data[$result->away_team_id]->passesmade / $result->match_data[$result->away_team_id]->passattempts) * 100) }}%</div>
                                    </div>                                                                        --}}
                                    @endisset
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5">
                                <div class="px-2 py-2 md:px-5 md:py-5 flex-1">{{ $results->links() }}</div></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>    

</x-app-layout>
