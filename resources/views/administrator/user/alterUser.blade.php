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
                    <form 
                        method="POST" 
                        action="{{ Route('user.update', $users->id) }}"
                    >
                        @csrf
                        @method("PUT")
                        <div>
                            <label for="id">Código: </label>
                            <input 
                                type="text" 
                                name="id" 
                                id="" 
                                @readonly(true) 
                                value="{{ $users->id }}"
                            >
                        </div>
                        <div>
                            <label for="name">Nome: </label>
                            <input 
                                type="text" 
                                name="name" 
                                id="" 
                                value="{{ $users->name }}"
                            >
                        </div>
                        <div>
                            <label for="email">E-mail: </label>
                            <input 
                                type="text" 
                                name="email" 
                                id="" 
                                value="{{ $users->email }}"
                            >
                        </div>
                        <label for="adm">Administrador: </label>
                        <div>
                            <select 
                                name="adm" 
                                id=""
                            >
                                <option value="true" {{ $users->adm ? 'selected' : '' }}>Sim</option>
                                <option value="false" {{ !$users->adm ? 'selected' : '' }}>Não</option>
                            </select>
                        </div>
                        <label for="status">Status: </label>
                        <div>
                            <select 
                                name="status" 
                                id=""
                            >
                                <option value="true" {{ $users->status ? 'selected' : '' }}>Ativo</option>
                                <option value="false" {{ !$users->status ? 'selected' : '' }}>Inativo</option>
                            </select>
                        </div>
                        <button type="submit">Alterar</button>
                        <button type="button"><a href="{{ Route('users') }}">{{ __('Voltar') }}</a></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-alert-error/>
</x-app-layout>
