@if (isset($stocksEdit))
    <div>
        <label for="id">{{ __('Código') }}: </label>
        <input 
            type="text" 
            name="id" 
            id="id" 
            @disabled(true) 
            value="{{ $stocksEdit->id }}"
        >
    </div>
@endif
<div>
    <label for="ticker">{{ __('Código de negociação') }}: </label>
    <input 
        type="text" 
        name="ticker" 
        id="ticker" 
        value="{{ isset($stocksEdit) ? (old('ticker') ?? $stocksEdit->ticker) : old('ticker') }}"
    >

    <label for="name">{{ __('Nome da empresa') }}: </label>
    <input 
        type="text" 
        name="name" 
        id="name" 
        value="{{ isset($stocksEdit) ? (old('name') ?? $stocksEdit->name) : old('name') }}"
    >
</div>