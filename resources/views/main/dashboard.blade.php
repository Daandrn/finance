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
                    <a href="{{ Route('titles.create') }}">Novo Título</a>
                    <table>
                        <thead>
                            
                        </thead>
                        <tbody>
                            <tr>
                                @if ($titles)
                                    @foreach ($titles as $title)
                                    <tr>
                                        <td>{{ __($title->title) }}</td>
                                        <td>{{ __($title->tax)."%" }}</td>
                                        <td>{{ __($title->modality->description) }}</td>
                                        <td>{{ @carbonDate($title->date_buy) }}</td>
                                        <td>{{ @carbonDate($title->date_liquidity) }}</td>
                                        <td>{{ @carbonDate($title->date_due) }}<td>
                                        <td><a href="{{ Route('titles.show', $title->id) }}">{{ __("Ver") }}</td></a>
                                        <td><a href="{{ Route('titles.edit', $title->id) }}">{{ __("Alterar") }}</td></a>
                                    </tr>
                                    @endforeach
                                @else
                                <div>{{ __('Nenhum titulo encontrado!') }}</div>
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
