<div class="flex flex-wrap gap-4">
    <input 
        type="hidden" 
        name="stocks_id"
        id="stocks_id"
        value="{{ isset($stocksEdit->id) ? (old('stocks_id') ?? $stocksEdit->id) : old('stocks_id') }}"
    >
    
    <div>
        <label for="grouping">{{ __('Agrupamento') }}: </label>
        <input
            class="flex flex-col rounded-md" 
            type="text" 
            name="grouping" 
            id="grouping" 
            value="{{ isset($splitsEdit) ? (old('grouping') ?? $splits->grouping) : old('grouping') }}"
        >
    </div>

    <div>
        <label for="split">{{ __('Desmembramento') }}: </label>
        <input
            class="flex flex-col rounded-md" 
            type="text" 
            name="split" 
            id="split" 
            value="{{ isset($splitsEdit) ? (old('split') ?? $splits->split) : old('split') }}"
        >
    </div>

    <div>
        <label for="date">{{ __('Data') }}: </label>
        <input
            class="flex flex-col rounded-md" 
            type="date" 
            name="date" 
            id="date" 
            value="{{ isset($splitsEdit) ? (old('date') ?? $splits->date) : old('date') }}"
        >
    </div>
</div>
