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
                <div class="flex flex-col items-center p-6 text-gray-900">
                    <div class="w-full">
                        <div>
                            <x-link-as-secondary-button class="hover:bg-gray-800 hover:text-gray-50" href="{{ Route('titles.create') }}">
                                {{ __('Novo Título') }}
                            </x-link-as-secondary-button>
                        </div>
                        <table class="w-full mt-5">
                            <thead class="bg-gray-200 border-b-2 border-gray-300 bg">
                                <tr>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Descrição') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Tipo') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Indexador') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Vencimento') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Valor na compra') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Valor atual') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Valorização') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Percentual') }}</th>
                                    <th colspan="1">{{ __('Ações') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @if ($titles->isNotEmpty())
                                        @foreach ($titles as $title)
                                        <tr class="bg-gray-50 dark:bg-gray-700 odd:bg-gray-50 even:bg-gray-200 dark:odd:bg-gray-700 dark:even:bg-gray-600">
                                            <td class="p-3 text-sm text-gray-700 ">{{ $title->title }}</td>
                                            <td class="p-3 text-sm text-gray-700">{{ $title->title_type->description }}</td>
                                            @switch($title->modality->id)
                                                @case(1)
                                                    <td class="p-3 text-sm text-gray-700">PRÉ {{ @valueFormat($title->tax) }}% a.a</td>
                                                    @break
                                                @case(2)
                                                    <td class="p-3 text-sm text-gray-700">{{ @valueFormat($title->tax)."% ".$title->modality->description }} a.a</td>
                                                    @break
                                                @case(3)
                                                @case(5)
                                                @case(7)
                                                    <td class="p-3 text-sm text-gray-700">{{ $title->modality->description." ".@valueFormat($title->tax)}}% a.a</td>
                                                    @break
                                                @default
                                                    <td class="p-3 text-sm text-gray-700">{{ @valueFormat($title->tax) }}% a.a</td>
                                            @endswitch
                                            <td class="p-3 text-sm text-gray-700" id="dateDue">{{ @carbonDate($title->date_due) }}</td>
                                            <td class="p-3 text-sm text-gray-700" id="valueBuy">R${{ @valueRealFormat($title->value_buy) }}</td>
                                            <td class="p-3 text-sm text-gray-700" id="valueCurrent">R${{ @valueRealFormat($title->value_current) }}</td>
                                            <td class="p-3 text-sm text-gray-700" id="valuegain">R${{ @valueRealFormat($title->gain) }}</td>
                                            <td class="p-3 text-sm text-gray-700">{{ @valueFormat($title->gain_percent) }}%</td>
                                            <td>
                                                <x-link-as-secondary-button href="{{ Route('titles.show', $title->id) }}" class="ms-1 hover:bg-gray-800 hover:text-gray-50">
                                                    {{ __("Ver") }}
                                                </x-link-as-secondary-button>
                                                <x-link-as-secondary-button href="{{ Route('titles.edit', $title->id) }}" class="ms-1 btn hover:bg-gray-800 hover:text-gray-50">
                                                    {{ __("Alterar") }}
                                                </x-link-as-secondary-button>
                                            </td>
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
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex flex-col items-center p-6 text-gray-900">
                    <div class="x-fit">
                        <div>
                            <x-link-as-secondary-button href="{{ Route('userStocksMovement.create') }}">
                                {{ __('Nova movimentação') }}
                            </x-link-as-secondary-button>

                            <x-link-as-secondary-button href="{{ Route('userStocks.create') }}" class="ms-3">
                                {{ __('Nova ação') }}
                            </x-link-as-secondary-button>
                        </div>
                        <table class="x-fit text-center">
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
                                            <td>
                                                <x-link-as-secondary-button href="{{ Route('userStocks.show', $userStocks->id) }}" class="ms-3">
                                                    {{ __("Ver") }}
                                                </x-link-as-secondary-button>
                                            </td>
                                            <td>
                                                <x-link-as-secondary-button href="{{ Route('userStocks.edit', $userStocks->id) }}" class="ms-3">
                                                    {{ __("Alterar") }}
                                                </x-link-as-secondary-button>
                                            </td>
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
    </div>
</x-app-layout>
