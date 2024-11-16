<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administrador') }}
        </h2>
    </x-slot>

    <x-alert-error/>
    
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
                        <div class="flex flex-wrap gap-4">
                            <div>
                                <label for="id">Código: </label>
                                <input 
                                    class="flex flex-col rounded-md"
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
                                    class="flex flex-col rounded-md" 
                                    type="text" 
                                    name="name" 
                                    id="" 
                                    value="{{ $users->name }}"
                                >
                            </div>
                            <div>
                                <label for="email">E-mail: </label>
                                <input
                                    class="flex flex-col rounded-md" 
                                    type="text" 
                                    name="email" 
                                    id="" 
                                    value="{{ $users->email }}"
                                >
                            </div>
                            <div>
                                <label for="adm">Administrador: </label>
                                <div>
                                    <select
                                        class="rounded-md" 
                                        name="adm" 
                                        id=""
                                    >
                                        <option value="true" {{ $users->adm ? 'selected' : '' }}>Sim</option>
                                        <option value="false" {{ !$users->adm ? 'selected' : '' }}>Não</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label for="status">Status: </label>
                                <div>
                                    <select
                                        class="rounded-md" 
                                        name="status" 
                                        id=""
                                    >
                                        <option value="true" {{ $users->status ? 'selected' : '' }}>Ativo</option>
                                        <option value="false" {{ !$users->status ? 'selected' : '' }}>Inativo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <x-primary-button>
                            {{ __('Alterar') }}
                        </x-primary-button>

                        <x-link-as-secondary-button href="{{ Route('users') }}" class="ms-3">
                            {{ __('Voltar') }}
                        </x-link-as-secondary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>
