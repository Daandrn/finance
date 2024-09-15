<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administrador') }}
        </h2>
    </x-slot>

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
                            @style('display: inline-block; background-color: rgba(255, 255, 255, 1); border-radius: 6px; box-shadow: 0 0 10px black; padding: 1%; max-height: 130px; margin-top: 250px;')
                        >
                            <div>
                                <button id="fecharNewStocks">{{ __('X') }}</button>
                            </div>
                            <form 
                                action="{{ Route('stocks.store') }}" 
                                method="POST"
                            >
                                @csrf()
                                @method('POST')
                                
                                @include('administrator.partials.formStocks')
                    
                                <button type="submit">{{ __('Criar') }}</button>
                            </form>
                        </span>
                    </div>
                    <div>
                        <button id="newStocks">{{ __('Nova ação') }}</button>
                        <button type="button"><a href="{{ Route('administrator') }}">{{ __('Voltar') }}</a></button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('Código') }}</th>
                                <th>{{ __('Código de negociação') }}</th>
                                <th>{{ __('Nome') }}</th>
                                <th>{{ __('Tipo') }}</th>
                                <th colspan="2">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($stocksPage->isNotEmpty())
                                @foreach ($stocksPage as $stocks)
                                <tr>
                                    <td>{{ $stocks->id }}</td>
                                    <td>{{ $stocks->ticker }}</td>
                                    <td>{{ $stocks->name }}</td>
                                    <td>{{ $stocks->stocks_types->description }}</td>
                                    <td><a href="{{ Route('stocks.edit', $stocks->id) }}">{{ __('Alterar') }}</a></td>
                                    <td>
                                        <form action="{{ Route('stocks.destroy', $stocks->id) }}" method="POST" id="deleteStock">
                                            @csrf()
                                            @method('DELETE')
                                            
                                            <input type="hidden" id="tickerDelete" value="{{ $stocks->ticker }}">
                                            <button type="submit">{{ __('Excluir') }}</button>
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
    
    @vite('resources/js/stocks.js')
    <x-alert-error/>
    
</x-app-layout>
