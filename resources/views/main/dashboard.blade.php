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
                    <div>
                        <a href="{{ Route('titles.create') }}">{{ __('Novo Título') }}</a>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('Descrição') }}</th>
                                <th>{{ __('Tipo') }}</th>
                                <th>{{ __('Indexador') }}</th>
                                <th>{{ __('Vencimento') }}</th>
                                <th></th>
                                <th>{{ __('Valor na compra') }}</th>
                                <th>{{ __('Valor atual') }}</th>
                                <th>{{ __('Valorização') }}</th>
                                <th>{{ __('Percentual') }}</th>
                                <th colspan="2">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                                @if ($titles->isNotEmpty())
                                    @foreach ($titles as $title)
                                    <tr>
                                        <td>{{ $title->title }}</td>
                                        <td>{{ $title->title_type->description }}</td>
                                        @switch($title->modality->id)
                                            @case(1)
                                                <td>PRÉ {{ @valueFormat($title->tax) }}% a.a</td>
                                                @break
                                            @case(2)
                                                <td>{{ @valueFormat($title->tax)."% ".$title->modality->description }} a.a</td>
                                                @break
                                            @case(3)
                                            @case(5)
                                            @case(7)
                                                <td>{{ $title->modality->description." ".@valueFormat($title->tax)}}% a.a</td>
                                                @break
                                            @default
                                                <td>{{ @valueFormat($title->tax) }}% a.a</td>
                                        @endswitch
                                        <td id="dateDue">{{ @carbonDate($title->date_due) }}<td>
                                        <td id="valueBuy">R${{ @valueRealFormat($title->value_buy) }}</td>
                                        <td id="valueCurrent">R${{ @valueRealFormat($title->value_current) }}</td>
                                        <td id="valuegain">R${{ @valueRealFormat($title->gain) }}</td>
                                        <td>{{ @valueFormat($title->gain_percent) }}%</td>
                                        <td><a href="{{ Route('titles.show', $title->id) }}">{{ __("Ver") }}</a></td>
                                        <td><a href="{{ Route('titles.edit', $title->id) }}">{{ __("Alterar") }}</a></td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="9">{{ __('Nenhum título encontrado!') }}</td>
                                </tr>
                                @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5"></td>
                                <td>{{ __("Total aplicado") }}</td>
                                <td>{{ __("Patrimônio") }}</td>
                                <td>{{ __("Valorização") }}</td>
                                <td>{{ __("Percentual") }}</td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td>R${{ @valueRealFormat($totalizers->get('buy_cumulative')) }}</td>
                                <td>R${{ @valueRealFormat($totalizers->get('patrimony')) }}</td>
                                <td>R${{ @valueRealFormat($totalizers->get('gain_cumulative')) }}</td>
                                <td>{{ @valueFormat($totalizers->get('gain_percent_cumulative')) }}%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <a href="{{ Route('userStocksMovement.create') }}">{{ __('Nova movimentação') }}</a>
                        <a href="{{ Route('userStocks.create') }}">{{ __('Nova ação') }}</a>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('Id') }}</th>
                                <th>{{ __('Ticker') }}</th>
                                <th>{{ __('Quantidade') }}</th>
                                <th></th>
                                <th>{{ __('Valor médio') }}</th>
                                <th>{{ __('Valor atual') }}</th>
                                <th>{{ __('Valor total') }}</th>
                                <th>{{ __('Valorização') }}</th>
                                <th>{{ __('Percentual') }}</th>
                                <th colspan="2">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                                @if ($userAllStocks->isNotEmpty())
                                    @foreach ($userAllStocks as $userStocks)
                                    <tr>
                                        <td>{{ $userStocks->id }}</td>
                                        <td>{{ $userStocks->stocks->ticker }}</td>
                                        <td>{{ $userStocks->quantity }}<td>
                                        <td id="valueBuy">R${{ @valueRealFormat($userStocks->average_value) }}</td>
                                        <td id="valueCurrent">R${{ @valueRealFormat($userStocks->value_current) }}</td>
                                        <td id="valueTotal">R${{ @valueRealFormat($userStocks->value_total_current) }}</td>
                                        <td id="valuegain">R${{ @valueRealFormat($userStocks->gain_total) }}</td>
                                        <td>{{ @valueFormat($userStocks->gain_percent) }}%</td>
                                        <td><a href="{{ Route('userStocks.show', $userStocks->id) }}">{{ __("Ver") }}</a></td>
                                        <td><a href="{{ Route('userStocks.edit', $userStocks->id) }}">{{ __("Alterar") }}</a></td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="10">{{ __('Nenhuma ação encontrada!') }}</td>
                                </tr>
                                @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5"></td>
                                <td>{{ __("Total aplicado") }}</td>
                                <td>{{ __("Patrimônio") }}</td>
                                <td>{{ __("Valorização") }}</td>
                                <td>{{ __("Percentual") }}</td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td>R${{ @valueRealFormat($userStockstotalizers->get('buy_cumulative')) }}</td>
                                <td>R${{ @valueRealFormat($userStockstotalizers->get('patrimony')) }}</td>
                                <td>R${{ @valueRealFormat($userStockstotalizers->get('gain_cumulative')) }}</td>
                                <td>{{ @valueFormat($userStockstotalizers->get('gain_percent_cumulative')) }}%</td>
                            </tr>
                            <tr>
                                <td colspan="10">{{ $userAllStocks->links() }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
