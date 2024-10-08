<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administrador') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <a href="{{ Route('users') }}">{{ __('Usuários') }}</a>
                    </div>
                    <div>
                        <a href="{{ Route('modalities') }}">{{ __('Modalidades') }}</a>
                    </div>
                    <div>
                        <a href="{{ Route('stocks') }}">{{ __('Ações') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
