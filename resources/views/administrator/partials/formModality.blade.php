<div class="flex flex-wrap gap-4">
    @if (isset($modalityEdit))
    <div>
        <label for="id">{{ __('Código') }}: </label>
        <input 
            class="flex flex-col rounded-md" 
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
        class="flex flex-col rounded-md" 
        type="text" 
        name="description" 
        id="description" 
        value="{{ isset($modalityEdit) ? (old('description') ?? $modalityEdit->description) : old('description') }}"
    >
</div>
</div>
