<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administrador') }}
        </h2>
    </x-slot>

    <x-alert-error/>
    <x-input-sucess/>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form 
                        action="{{ Route('stocks.update', $stocksEdit->id) }}" 
                        method="POST"
                    >
                        @csrf()
                        @method('PUT')

                        @include('administrator.partials.formStocks')

                        <x-primary-button>
                            {{ __('Alterar') }}
                        </x-primary-button>
                        
                        <x-link-as-secondary-button href="{{ Route('stocks') }}">
                            {{ __('Voltar') }}
                        </x-link-as-secondary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg text-gray-800 leading-tight">
                        Agrupamentos/Desdobramentos
                    </h3>
                    
                    <form 
                        action="{{ Route('split.store', $stocksEdit->id) }}" 
                        method="POST"
                        id="splitStore"
                    >
                        @csrf()
                        @method('POST')

                        @include('administrator.partials.formSplits')

                        <x-create-button>
                            {{ __('Incluir') }}
                        </x-create-button>
                    </form>
                    
                    <div class="my-4">
                        <form 
                            action="{{ Route('split.destroy') }}" 
                            method="POST"
                        >
                            @csrf()
                            @method('DELETE')

                            <table id="splitTable">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Agrupamento</th>
                                        <th>Desdobramento</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody id="splitTableBody">
        
                                </tbody>
                                <tfoot>
                                    <td>
                                        <x-danger-button>
                                            {{ __('Excluir') }}
                                        </x-danger-button>
                                    </td>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/splits.js')
</x-app-layout>
