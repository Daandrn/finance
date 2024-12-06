<div class="flex flex-wrap gap-4">
    @if (isset($stocksEdit))
        <div>
            <label for="id">{{ __('Código') }}: </label>
            <input
                class="flex flex-col rounded-md"
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
            class="flex flex-col rounded-md"
            type="text" 
            name="ticker" 
            id="ticker" 
            value="{{ isset($stocksEdit) ? (old('ticker') ?? $stocksEdit->ticker) : old('ticker') }}"
        >
    </div>
    
    <div class="min-w-80">
        <label for="name">{{ __('Nome da empresa') }}: </label>
        <input
            class="flex flex-col rounded-md min-w-full"
            type="text" 
            name="name" 
            id="name" 
            value="{{ isset($stocksEdit) ? (old('name') ?? $stocksEdit->name) : old('name') }}"
        >
    </div>

    <div class="min-w-28">
        <label for="stocks_types_id">{{ __('Tipo') }}: </label>
        <select
            class="flex flex-col rounded-md min-w-full"
            name="stocks_types_id" 
            id="stocks_types_id"
        >
            @if (isset($stocksTypes))
                @foreach ($stocksTypes as $type)
                    <option value="{{ $type->id }}" @selected($type->id == (isset($stocksEdit) ? (old('stocks_types_id') ?? $stocksEdit->stocks_types_id) : old('stocks_types_id')) ? true : false)>{{ $type->description }}</option>
                @endforeach
            @endif
        </select>
    </div>

    <div class="min-w-28">
        <label for="status">{{ __('Ativo') }}: </label>
        <select
            class="flex flex-col rounded-md min-w-full"
            name="status" 
            id="status"
        >
            <option value="t" @selected(true == (isset($stocksEdit) ? (old('status') ?? $stocksEdit->status) : old('status')))>{{ __('Sim') }}</option>
            <option value="f" @selected(false == (isset($stocksEdit) ? (old('status') ?? $stocksEdit->status) : old('status')))>{{ __('Não') }}</option>
        </select>
    </div>
</div>
