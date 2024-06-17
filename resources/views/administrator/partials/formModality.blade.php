@if (isset($modality))
    <div>
        <label for="id">{{ __('Código') }}: </label>
        <input 
            type="text" 
            name="id" 
            id="id" 
            @disabled(true) 
            value="{{ $modality->id }}"
        >
    </div>
@endif
<div>
    <label for="description">{{ __('Descrição') }}: </label>
    <input 
        type="text" 
        name="description" 
        id="description" 
        value="{{ isset($modality) ? (old('description') ?? $modality->description) : old('description') }}"
    >
</div>