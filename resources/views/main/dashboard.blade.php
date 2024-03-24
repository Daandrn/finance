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
                                @if ($titles)
                                    @foreach ($titles as $title)
                                    <tr>
                                        <td>{{ $title->title }}</td>
                                        <td>{{ $title->title_type->description }}</td>
                                        @switch($title->modality->id)
                                            @case(1)
                                                <td>PRÉ {{ $title->tax."%" }}</td>
                                                @break
                                            @case(2)
                                                <td>{{ $title->tax."% ".$title->modality->description }}</td>
                                                @break
                                            @case(3)
                                            @case(5)
                                            @case(7)
                                                <td>{{ $title->modality->description." ".$title->tax."%" }}</td>
                                                @break
                                            @default
                                                <td>{{ $title->modality->description }}</td>
                                        @endswitch
                                        <td id="dateDue">{{ @carbonDate($title->date_due) }}<td>
                                        <td id="valueBuy">R${{ @valueFormat($title->value_buy) }}</td>
                                        <td id="valueCurrent">R${{ @valueFormat($title->value_current) }}</td>
                                        <td id="valuegain">R${{ @valueFormat($title->gain) }}</td>
                                        <td>{{ $title->gain_percent }}%</td>
                                        <td><a href="{{ Route('titles.show', $title->id) }}">{{ __("Ver") }}</a></td>
                                        <td><a href="{{ Route('titles.edit', $title->id) }}">{{ __("Alterar") }}</a></td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="10">{{ __('Nenhum título encontrado!') }}</td>
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
                                <td>R${{ @valueFormat($lastTitle->buy_cumulative ?? "00.00") }}</td>
                                <td>R${{ @valueFormat($lastTitle->patrimony ?? "00.00") }}</td>
                                <td>R${{ @valueFormat($lastTitle->gain_cumulative ?? "00.00") }}</td>
                                <td>{{ $lastTitle->gain_percent_cumulative ?? "00.00" }}%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
