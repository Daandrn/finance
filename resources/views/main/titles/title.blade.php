<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inicio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table>
                        <thead>
                            <th>Título</th>
                            <th>Taxa</th>
                            <th>Modalidade</th>
                            <th>Data de compra</th>
                            <th>Liquidez</th>
                            <th>Vencimento</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $title->title }}</td>
                                <td>{{ $title->tax }}</td>
                                <td>{{ $title->modality->description }}</td>
                                <td>{{ @carbonDate($title->date_buy) }}</td>
                                <td>{{ @carbonDate($title->date_liquidity) }}</td>
                                <td>{{ @carbonDate($title->date_due) }}</td>
                                <td>
                                    <form action="{{ Route('titles.destroy', $title->id) }}" method="POST">
                                        @csrf()
                                        @method('DELETE')
                                        <button type="submit">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-input-sucess/>
</x-app-layout>