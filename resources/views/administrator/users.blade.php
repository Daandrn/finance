<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administrador') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex justify-center">
                    <table>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Administrador</th>
                                <th>Status</th>
                                <th>Data de criação</th>
                                <th></th>
                            </tr>
                        </thead>
                        @if ($users->count() > 0)
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td> {{ $user->id }} </td>
                                <td> {{ $user->name }} </td>
                                <td> {{ $user->email }} </td>
                                <td> {{ $user->adm ? 'Sim' : 'Não' }} </td>
                                <td> {{ $user->status ? 'Ativo' : 'Inativo' }} </td>
                                <td> {{ $user->created_at->format('d/m/Y') }} </td>
                                <td><a href="{{ Route('user.edit', $user->id) }}">Alterar</a></td>
                                <td>
                                    <form action="{{ Route('user.destroy', $user->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @else
                        <tbody>
                            <tr>
                                <td colspan="7">Nenhum usuário encontrado!</td>
                            </tr>
                        </tbody>
                        @endif
                        <tfoot>
                            <tr>
                                <td colspan="7"> {{ $users->links() }} </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
