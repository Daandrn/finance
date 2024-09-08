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
                    <table>
                        <thead>
                            <th>{{ __('Título') }}</th>
                            <th>{{ __('Tipo de título') }}</th>
                            <th>{{ __('Taxa') }}</th>
                            <th>{{ __('Isento') }}</th>
                            <th>{{ __('Data de compra') }}</th>
                            <th>{{ __('Liquidez') }}</th>
                            <th>{{ __('Vencimento') }}</th>
                            <th>{{ __('Valor na compra') }}</th>
                            <th>{{ __('Valor atual') }}</th>
                            <th>{{ __('Valorização') }}</th>
                            <th>{{ __('Percentual') }}</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $title->title }}</td>
                                <td>{{ $title->title_type->description }}</td>
                                @switch($title->modality->id)
                                    @case(1)
                                        <td>PRÉ {{ @valueFormat($title->tax) ."%" }}</td>
                                        @break
                                    @case(2)
                                        <td>{{ @valueFormat($title->tax) ."% ".$title->modality->description }}</td>
                                        @break
                                    @case(3)
                                    @case(5)
                                    @case(7)
                                        <td>{{ $title->modality->description." ".@valueFormat($title->tax) ."%" }}</td>
                                        @break
                                    @default
                                        <td>{{ $title->modality->description }}</td>
                                 @endswitch
                                <td>{{ $title->title_type->has_irpf ? "Não" : "Sim" }}</td>
                                <td>{{ @carbonDate($title->date_buy) }}</td>
                                <td>{{ @carbonDate($title->date_liquidity) }}</td>
                                <td>{{ @carbonDate($title->date_due) }}</td>
                                <td>R${{ @valueRealFormat($title->value_buy) }}</td>
                                <td>R${{ @valueRealFormat($title->value_current) }}</td>
                                <td>R${{ @valueRealFormat($title->gain) }}</td>
                                <td>{{ @valueFormat($title->gain_percent) }}%</td>
                                <td>
                                    <form action="{{ Route('titles.destroy', $title->id) }}" method="POST">
                                        @csrf()
                                        @method('DELETE')
                                        <button type="submit">{{ __('Excluir') }}</button>
                                        <button type="button"><a href="{{ Route('dashboard') }}">{{ __('Voltar') }}</a></button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>