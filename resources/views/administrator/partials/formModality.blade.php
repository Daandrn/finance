@if (isset($modalityEdit))
    <div>
        <label for="id">{{ __('Código') }}: </label>
        <input 
            type="text" 
            name="id" 
            id="id" 
            @disabled(true) 
            value="{{ $modalityEdit->id }}"
        >
    </div>
@endif
<div>
    <label for="description">{{ __('Descrição') }}: </label>
    <input 
        type="text" 
        name="description" 
        id="description" 
        value="{{ isset($modalityEdit) ? (old('description') ?? $modalityEdit->description) : old('description') }}"
    >
</div>