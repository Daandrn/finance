<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inicio') }}
        </h2>
    </x-slot>

    <x-alert-error/>
    <x-input-sucess/>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form 
                        action="{{ Route('titles.update', $title->id) }}" 
                        method="POST"
                    >
                        @csrf()
                        @method('PUT')

                        @include('main.partials.titleForm')

                        <x-primary-button>
                            {{ __('Alterar') }}
                        </x-primary-button>
                        <x-link-as-secondary-button href="{{ Route('dashboard') }}" class="ms-3">
                            {{ __('Voltar') }}
                        </x-link-as-secondary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>
