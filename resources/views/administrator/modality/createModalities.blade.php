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
                        action="{{ Route('modality.store') }}" 
                        method="POST"
                    >
                        @csrf()
                        @method('POST')

                        @include('administrator.partials.formModality')

                        <button type="submit">{{ __('Criar') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-alert-error/>

</x-app-layout>
