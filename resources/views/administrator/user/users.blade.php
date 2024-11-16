<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administrador') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <x-link-as-secondary-button href="{{ Route('administrator') }}">
                            {{ __('Voltar') }}
                        </x-link-as-secondary-button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('Código') }}</th>
                                <th>{{ __('Nome') }}</th>
                                <th>{{ __('E-mail') }}</th>
                                <th>{{ __('Administrador') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Data de criação') }}</th>
                                <th colspan="6">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        @if ($users->count() > 0)
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td> {{ $user->id }} </td>
                                <td> {{ __($user->name) }} </td>
                                <td> {{ __($user->email) }} </td>
                                <td> {{ __($user->adm ? 'Sim' : 'Não') }} </td>
                                <td> {{ __($user->status ? 'Ativo' : 'Inativo') }} </td>
                                <td> {{ $user->created_at->format('d/m/Y') }} </td>
                                <td>
                                    <x-link-as-secondary-button href="{{ Route('user.edit', $user->id) }}">
                                        {{ __('Alterar') }}
                                    </x-link-as-secondary-button>
                                </td>
                                <td>
                                    <form
                                        id="userDelete"
                                        action="{{ Route('user.destroy', $user->id) }}" 
                                        method="post"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <input type="hidden" id="delete{{ $user->id }}" value="{{ $user->name }}"></input>
                                        <x-danger-button class="ms-3">
                                            {{ __('Excluir') }}
                                        </x-danger-button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @else
                        <tbody>
                            <tr>
                                <td colspan="8">{{ __('Nenhum usuário encontrado!') }}</td>
                            </tr>
                        </tbody>
                        @endif
                        <tfoot>
                            <tr>
                                <td colspan="8"> {{ $users->links() }} </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @vite('resources/js/users.js')
</x-app-layout>
