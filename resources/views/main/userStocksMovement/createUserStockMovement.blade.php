<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inicio') }}
        </h2>
    </x-slot>

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

                        <button type="submit" id="createUserStockMovement">{{ __('Criar') }}</button>
                        <button type="button" onclick="window.history.back()">{{ __('Voltar') }}</button>
                    </form>
                </div>

                <div class="p-6 text-gray-900">
                    <form 
                        id="userStocksMovement_import"
                        action="{{ Route('userStocksMovement.import') }}" 
                        method="post" 
                        enctype="multipart/form-data"
                    >
                        @csrf()
                        @method('POST')

                        <div>
                            <input 
                                type="file" 
                                name="fileUpload" 
                                id="fileUpload"
                            >
                        </div>

                        <button type="submit">{{ __('Importar') }}</button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <x-alert-error/>
    <x-modal-loading/>
    
    @vite('resources/js/userStocksMovement.js')
</x-app-layout>
