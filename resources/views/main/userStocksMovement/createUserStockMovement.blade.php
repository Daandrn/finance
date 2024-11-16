<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inicio') }}
        </h2>
    </x-slot>

    <x-alert-error/>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form 
                        action="{{ Route('userStocksMovement.store') }}" 
                        method="post"
                    >
                        @csrf()
                        @method('POST')

                        @include('main.partials.userStocksMovementForm')

                        <x-create-button id="createUserStockMovement">
                            {{ __('Criar') }}
                        </x-create-button>

                        <x-secondary-button onclick="window.history.back()" class="ms-3">
                            {{ __('Voltar') }}
                        </x-secondary-button>
                    </form>
                </div>

                <div class="p-6 text-gray-900">
                    <form 
                        id="userStocksMovement_import"
                        action="{{ Route('userStocksMovement.import') }}" 
                        method="post"
                    >
                        @csrf()
                        @method('POST')

                        <div>
                            <input 
                                type="file" 
                                name="fileUpload" 
                                id="fileUpload"
                                accept=".xlsx"
                            >
                        </div>

                        <x-create-button>
                            {{ __('Importar') }}
                        </x-create-button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <x-modal-loading/>
    @vite('resources/js/userStocksMovement.js')
    
</x-app-layout>
