<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Charts') }}
        </h2>
    </x-slot>

    <div
    class="container mx-auto px-4 sm:px-8 py-8">
        <x-login-chart />
        <x-goal-chart />
    </div>
    
</x-app-layout>
    

