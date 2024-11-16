<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inicio') }}
        </h2>
    </x-slot>

    <x-input-sucess/>
    <x-input-errors/>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <x-link-as-secondary-button href="{{ Route('dashboard') }}">
                        {{ __('Voltar') }}
                    </x-link-as-secondary-button>
                    
                    <table>
                        <thead>
                            <th>{{ __('Ticker') }}</th>
                            <th>{{ __('Quantidade') }}</th>
                            <th>{{ __('Valor médio') }}</th>
                            <th>{{ __('Valor atual') }}</th>
                            <th>{{ __('Valorização') }}</th>
                            <th>{{ __('Percentual') }}</th>
                            <th>{{ __('Ações') }}</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $showUserStocks->stocks->ticker }}</td>
                                <td>{{ @valueFormat($showUserStocks->quantity) }}</td>
                                <td>R${{ @valueRealFormat($showUserStocks->average_value) }}</td>
                                <td>R${{ @valueRealFormat($showUserStocks->current_value) }}</td>
                                <td>R${{ @valueRealFormat($showUserStocks->gain) }}</td>
                                <td>{{ @valueFormat($showUserStocks->gain_percent) }}%</td>
                                <td>
                                    <form id="userStocksDelete" action="{{ Route('userStocks.destroy', $showUserStocks->id) }}" method="POST">
                                        @csrf()
                                        @method('DELETE')

                                        <input type="hidden" id="StocksUserStocksDelete" value="{{ $showUserStocks->ticker }}">

                                        <x-danger-button>
                                            {{ __('Excluir') }}
                                        </x-danger-button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <x-link-as-secondary-button href="{{ Route('userStocksMovement.create', $showUserStocks->stocks_id) }}">
                        {{ __('Nova movimentação') }}
                    </x-link-as-secondary-button>
                    
                    <x-link-as-secondary-button href="{{ Route('dashboard') }}" class="ms-3">
                        {{ __('Voltar') }}
                    </x-link-as-secondary-button>
                    
                    <table class="text-center">
                        <thead>
                            <th>{{ __('Id') }}</th>
                            <th>{{ __('Tipo') }}</th>
                            <th>{{ __('Quantidade') }}</th>
                            <th>{{ __('Valor médio') }}</th>
                            <th>{{ __('Valor total') }}</th>
                            <th>{{ __('Data') }}</th>
                            <th><div id="selectAllMovements" @style('cursor: pointer;')>{{ __('Todos') }}</div></th>
                        </thead>
                        <tbody>
                            <form id="userStocksMovementDelete" action="{{ Route('userStocksMovement.destroy') }}" method="POST">
                                @csrf()
                                @method('DELETE')

                                @forelse ($userStocksMovements as $movement)
                                <tr>
                                    <td>{{ $movement->id }}</td>
                                    <td>{{ $movement->stocks_movement_types->description }}</td>
                                    <td>{{ @valueFormat($movement->quantity) }}</td>
                                    <td>R${{ @valueRealFormat($movement->value) }}</td>
                                    <td>R${{ @valueRealFormat($movement->value_total) }}</td>
                                    <td>{{ @carbonDate($movement->date) }}</td>
                                    <td>
                                        <input type="checkbox" name="movementsDelete[]" value="{{ implode(', ', [$movement->id, $movement->date]) }}" class="rounded">
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">{{ __('Não há movimentações para esta ação!') }}</td>
                                    </tr>
                                @endforelse
                                    <td>
                                        <x-danger-button>
                                            {{ __('Excluir') }}
                                        </x-danger-button>
                                    </td>
                            </form>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/userStocksMovement.js')
    
</x-app-layout>

