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
                    <div 
                        id="newStocksModal" 
                        @disabled(true) 
                        @readonly(true) 
                        @style('display:none;')
                    >
                        <span 
                            @style('display: inline-block; background-color: rgba(255, 255, 255, 1); border-radius: 6px; box-shadow: 0 0 10px black; padding: 1%; max-height: 170px; margin-top: 250px;')
                        >
                            <div>
                                <x-danger-button id="fecharNewStocks">
                                    {{ __('X') }}
                                </x-danger-button>
                            </div>
                            <form 
                                action="{{ Route('stocks.store') }}" 
                                method="POST"
                            >
                                @csrf()
                                @method('POST')
                                
                                @include('administrator.partials.formStocks')
                    
                                <x-create-button class="mt-1">
                                    {{ __('Criar') }}
                                </x-create-button>
                            </form>
                        </span>
                    </div>
                    <div id="buttons">
                        <x-secondary-button id="newStocks">
                            {{ __('Nova ação') }}
                        </x-secondary-button>
                        
                        <x-link-as-secondary-button href="{{ Route('administrator') }}" class="ms-3">
                            {{ __('Voltar') }}
                        </x-link-as-secondary-button>
                        
                        <x-primary-button id="btUpdateValues" class="ms-3">
                            {{ __('Atualizar Valores') }}
                        </x-primary-button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('Código') }}</th>
                                <th>{{ __('Código de negociação') }}</th>
                                <th>{{ __('Nome') }}</th>
                                <th>{{ __('Tipo') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th colspan="2">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($stocksPage->isNotEmpty())
                                @foreach ($stocksPage as $stocks)
                                <tr class="text-center">
                                    <td>{{ $stocks->id }}</td>
                                    <td>{{ $stocks->ticker }}</td>
                                    <td>{{ $stocks->name }}</td>
                                    <td>{{ $stocks->stocks_types->description }}</td>
                                    <td>{{ $stocks->status ? 'Ativo' : 'Inativo' }}</td>
                                    <td>
                                        <x-link-as-secondary-button href="{{ Route('stocks.edit', $stocks->id) }}" class="ms-3">
                                            {{ __('Alterar') }}
                                        </x-link-as-secondary-button>
                                    </td>
                                    <td>
                                        <form action="{{ Route('stocks.destroy', $stocks->id) }}" method="POST" id="deleteStock">
                                            @csrf()
                                            @method('DELETE')
                                            
                                            <input type="hidden" id="tickerDelete" value="{{ $stocks->ticker }}">

                                            <x-danger-button class="ms-3">
                                                {{ __('Excluir') }}
                                            </x-danger-button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6">{{ __('Não há ações cadastradas!') }}</td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6">{{ $stocksPage->links() }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <x-modal-loading/>
    @vite('resources/js/stocks.js')
    
</x-app-layout>
