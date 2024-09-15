<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inicio') }}
        </h2>
    </x-slot>

    <x-input-sucess/>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <button type="button"><a href="{{ Route('dashboard') }}">{{ __('Voltar') }}</a></button>
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
                                        <button type="submit">{{ __('Excluir') }}</button>
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
                    <a href="{{ Route('userStocksMovement.create') }}">{{ __('Nova movimentação') }}</a>
                    <button type="button"><a href="{{ Route('dashboard') }}">{{ __('Voltar') }}</a></button>
                    <table>
                        <thead>
                            <th>{{ __('Id') }}</th>
                            <th>{{ __('Tipo') }}</th>
                            <th>{{ __('Quantidade') }}</th>
                            <th>{{ __('Valor médio') }}</th>
                            <th>{{ __('Valor total') }}</th>
                            <th>{{ __('Data') }}</th>
                            <th>{{ __('Ações') }}</th>
                        </thead>
                        <tbody>
                            @forelse ($userStocksMovements as $movement)
                            <tr>
                                <td>{{ $movement->id }}</td>
                                <td>{{ $movement->stocks_movement_types->description }}</td>
                                <td>{{ @valueFormat($movement->quantity) }}</td>
                                <td>R${{ @valueRealFormat($movement->value) }}</td>
                                <td>R${{ @valueRealFormat($movement->value_total) }}</td>
                                <td>{{ @carbonDate($movement->date) }}</td>
                                <td>
                                    <form id="userStocksMovementDelete" action="{{ Route('userStocksMovement.destroy', $movement->id) }}" method="POST">
                                        @csrf()
                                        @method('DELETE')

                                        <input type="hidden" value="{{ $movement->id }}">
                                        <button type="submit">{{ __('Excluir') }}</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="8">{{ __('Não há movimentações para esta ação!') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/userStocksMovement.js')
    
</x-app-layout>