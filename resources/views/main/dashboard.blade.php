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
                <div class="flex flex-col items-center p-6 text-gray-900 gap-8">
                    <div class="w-full">
                        <h1 class="text-center uppercase underline-offset-4 tracking-normal">
                            <p>{{ __('Títulos de renda fixa') }}</p>
                        </h1>
                        
                        <div>
                            <x-link-as-secondary-button class="hover:bg-gray-800 hover:text-gray-50" href="{{ Route('titles.create') }}">
                                {{ __('Novo Título') }}
                            </x-link-as-secondary-button>
                        </div>
                        <table class="w-full mt-5">
                            <thead>
                                <tr>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Descrição') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Tipo') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Indexador') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Vencimento') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Valor na compra') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Valor atual') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Valorização') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Percentual') }}</th>
                                    <th>{{ __('Ações') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @if ($titles->isNotEmpty())
                                        @foreach ($titles as $title)
                                        <tr class="bg-gray-50 dark:bg-gray-700 odd:bg-gray-50 even:bg-gray-200 dark:odd:bg-gray-400 dark:even:bg-gray-300 text-center">
                                            <td class="p-3 text-sm text-gray-700 overflow-hidden whitespace-nowrap text-ellipsis max-w-60 text-start cursor-pointer" title="{{ $title->title }}">{{ $title->title }}</td>
                                            <td class="p-3 text-sm text-gray-700">{{ $title->title_type->description }}</td>
                                            <td class="p-3 text-sm text-gray-700">
                                                @switch($title->modality->id)
                                                    @case(1)
                                                        PRÉ {{ @valueFormat($title->tax) }}% a.a
                                                        @break
                                                    @case(2)
                                                        {{ @valueFormat($title->tax)."% ".$title->modality->description }} a.a
                                                        @break
                                                    @case(3)
                                                    @case(5)
                                                    @case(7)
                                                        {{ $title->modality->description." ".@valueFormat($title->tax)}}% a.a
                                                        @break
                                                    @default
                                                        {{ @valueFormat($title->tax) }}% a.a
                                                @endswitch
                                            </td>
                                            <td id="dateDue" class="p-3 text-sm text-gray-700">{{ @carbonDate($title->date_due) }}</td>
                                            <td id="valueBuy" class="p-3 text-sm text-gray-700">R${{ @valueRealFormat($title->value_buy) }}</td>
                                            <td id="valueCurrent" class="p-3 text-sm text-gray-700">R${{ @valueRealFormat($title->value_current) }}</td>
                                            <td id="valuegain" class="p-3 text-sm text-gray-700">R${{ @valueRealFormat($title->gain) }}</td>
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
                                    <tr class="bg-gray-50 dark:bg-gray-700 odd:bg-gray-50 even:bg-gray-200 dark:odd:bg-gray-400 dark:even:bg-gray-300 text-center">
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
                                <tr>
                                    <td colspan="9">{{ $titles->links() }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="w-full">
                        <h1 class="text-center uppercase underline-offset-4 tracking-normal">
                            <p>{{ __('Ativos de renda variável') }}</p>
                        </h1>
                        
                        <div>
                            <x-link-as-secondary-button class="hover:bg-gray-800 hover:text-gray-50" href="{{ Route('userStocksMovement.create') }}">
                                {{ __('Nova movimentação') }}
                            </x-link-as-secondary-button>

                            <x-link-as-secondary-button class="hover:bg-gray-800 hover:text-gray-50" href="{{ Route('userStocks.create') }}">
                                {{ __('Nova ação') }}
                            </x-link-as-secondary-button>
                        </div>
                        <table class="w-full mt-5">
                            <thead>
                                <tr>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Id') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Ticker') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Quantidade') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Valor médio') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Valor atual') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Valor total') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Valorização') }}</th>
                                    <th class="p-2 text-sm font-semibold tracking-wide text-center">{{ __('Percentual') }}</th>
                                    <th colspan="2">{{ __('Ações') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @if ($userAllStocks->isNotEmpty())
                                        @foreach ($userAllStocks as $userStocks)
                                        <tr class="bg-gray-50 dark:bg-gray-700 odd:bg-gray-50 even:bg-gray-200 dark:odd:bg-gray-400 dark:even:bg-gray-300 text-center">
                                            <td class="p-3 text-sm text-gray-700">{{ $userStocks->id }}</td>
                                            <td class="p-3 text-sm text-gray-700">{{ $userStocks->stocks->ticker }}</td>
                                            <td class="p-3 text-sm text-gray-700">{{ $userStocks->quantity }}</td>
                                            <td id="valueBuy" class="p-3 text-sm text-gray-700">R${{ @valueRealFormat($userStocks->average_value) }}</td>
                                            <td id="valueCurrent" class="p-3 text-sm text-gray-700">R${{ @valueRealFormat($userStocks->value_current) }}</td>
                                            <td id="valueTotal" class="p-3 text-sm text-gray-700">R${{ @valueRealFormat($userStocks->value_total_current) }}</td>
                                            <td id="valuegain" class="p-3 text-sm text-gray-700">R${{ @valueRealFormat($userStocks->gain_total) }}</td>
                                            <td class="p-3 text-sm text-gray-700">{{ @valueFormat($userStocks->gain_percent) }}%</td>
                                            <td>
                                                <x-link-as-secondary-button href="{{ Route('userStocks.show', $userStocks->id) }}" class="ms-1 hover:bg-gray-800 hover:text-gray-50">
                                                    {{ __("Ver") }}
                                                </x-link-as-secondary-button>

                                                <x-link-as-secondary-button href="{{ Route('userStocks.edit', $userStocks->id) }}" class="ms-1 btn hover:bg-gray-800 hover:text-gray-50">
                                                    {{ __("Alterar") }}
                                                </x-link-as-secondary-button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr class="bg-gray-50 dark:bg-gray-700 odd:bg-gray-50 even:bg-gray-200 dark:odd:bg-gray-400 dark:even:bg-gray-300 text-center">
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
