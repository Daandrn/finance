<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administrador') }}
        </h2>
    </x-slot>

    <x-alert-error/>
    <x-input-sucess/>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div 
                        id="newModalityModal" 
                        @disabled(true) 
                        @readonly(true) 
                        @style('display:none;')
                    >
                        <span 
                            @style('display: inline-block; background-color: rgba(255, 255, 255, 1); border-radius: 6px; box-shadow: 0 0 10px black; padding: 1%; max-height: 170px; margin-top: 250px;')
                        >
                            <div>
                                <x-danger-button id="fecharNewModality">
                                    {{ __('X') }}
                                </x-danger-button>
                            </div>
                            <form 
                                action="{{ Route('modality.store') }}" 
                                method="POST"
                            >
                                @csrf()
                                @method('POST')
                    
                                @include('administrator.partials.formModality')
                    
                                <x-create-button class="mt-2">
                                    {{ __('Criar') }}
                                </x-create-button>
                            </form>
                        </span>
                    </div>
                    <div>
                        <x-secondary-button id="newModality">
                            {{ __('Nova modalidade') }}
                        </x-secondary-button>
                        <x-link-as-secondary-button href="{{ Route('administrator') }}" class="ms-3">
                            {{ __('Voltar') }}
                        </x-link-as-secondary-button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('Código') }}</th>
                                <th>{{ __('Descrição') }}</th>
                                <th colspan="2">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($modalities)
                                @foreach ($modalities as $modality)
                                <tr>
                                    <td>{{ $modality->id }}</td>
                                    <td>{{ $modality->description }}</td>
                                    <td>
                                        <x-link-as-secondary-button href="{{ Route('modality.edit', $modality->id) }}" class="ms-3">
                                            {{ __('Alterar') }}
                                        </x-link-as-secondary-button>
                                    </td>
                                    <td>
                                        <form 
                                            id="deleteModality" 
                                            action="{{ Route('modality.destroy', $modality->id) }}" 
                                            method="POST"
                                        >
                                            @csrf()
                                            @method('DELETE')
                                            
                                            <input type="hidden" id="modalityDelete" value="{{ $modality->description }}">

                                            <x-danger-button class="ms-3">
                                                {{ __('Excluir') }}
                                            </x-danger-button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">{{ __('Não há modalidades cadastradas!') }}</td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4">{{ $modalities->links() }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    @vite('resources/js/modalities.js')
    
</x-app-layout>
