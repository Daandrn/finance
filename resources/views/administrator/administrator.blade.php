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
                        <x-responsive-nav-link href="{{ Route('users') }}">
                            {{ __('Usuários') }}
                        </x-responsive-nav-link>
                    </div>
                    <div>
                        <x-responsive-nav-link href="{{ Route('modalities') }}">
                            {{ __('Modalidades') }}
                        </x-responsive-nav-link>
                    </div>
                    <div>
                        <x-responsive-nav-link href="{{ Route('stocks') }}">
                            {{ __('Ações') }}
                        </x-responsive-nav-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
