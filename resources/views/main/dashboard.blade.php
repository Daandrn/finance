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

                        </thead>
                        <tbody>
                            <tr>
                                @if ($titles)
                                    @foreach ($titles as $title)
                                    <tr>
                                        <td>{{ $title->title }}</td>
                                        <td>{{ $title->title_type->description }}</td>
                                        <td>{{ $title->tax."%" }}</td>
                                        <td>{{ $title->modality->description }}</td>
                                        <td>{{ @carbonDate($title->date_buy) }}</td>
                                        <td>{{ @carbonDate($title->date_liquidity) }}</td>
                                        <td>{{ @carbonDate($title->date_due) }}<td>
                                        <td>R${{ @valueFormat($title->value_buy) }}</td>
                                        <td>R${{ @valueFormat($title->value_current) }}</td>
                                        <td>R${{ @valueFormat($title->gain) }}</td>
                                        <td>{{ $title->gain_percent }}%</td>
                                        <td><a href="{{ Route('titles.show', $title->id) }}">{{ __("Ver") }}</a></td>
                                        <td><a href="{{ Route('titles.edit', $title->id) }}">{{ __("Alterar") }}</a></td>
                                    </tr>
                                    @endforeach
                                @else
                                <div>{{ __('Nenhum título encontrado!') }}</div>
                                @endif
                            </tr>
                        </tbody>
                        <tfoot>
                            
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
