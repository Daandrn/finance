<div class="flex flex-wrap gap-4">
        <input 
            type="hidden" 
            name="user_id" 
            id="user_id" 
            value="{{ isset($userStocksMovement) ? (old('user_id') ?? $userStocksMovement->user_id) : (old('user_id') ?? Auth::user()->id) }}"
        > 
    <div>
        <label for="ticker">{{ __('Ticker') }}: </label>
        <div class="flex flex-row gap-x-4">
            <input
                class="flex flex-col rounded-md" 
                type="text" 
                name="ticker" 
                id="ticker" 
                value="{{ $selectedStocks->ticker ?? (isset($userStocksMovement->ticker) ? (old('ticker') ?? $userStocksMovement->ticker) : old('ticker')) }}"
            >
            <select 
                class="rounded-md"
                name="stocks_id" 
                id="stocks_id"
            >
                @if (!isset($userStocksMovement->ticker))
                    <option value="">Selecione</option>   
                @endif
        
                @isset($stocks)
                    @foreach ($stocks as $stocksElement)
                        <option
                            value="{{ $stocksElement->id }}" 
                            data-ticker="{{ $stocksElement->ticker }}"
                            @selected(($selectedStocks->id ?? (isset($userStocksMovement->stocks_id) ? $userStocksMovement->stocks_id : old('stocks_id'))) == $stocksElement->id)
                        >{{ $stocksElement->ticker }}</option>
                    @endforeach
                @endisset
            </select>
        </div>
    </div>
    <div>
        <label for="movement_type_id">{{ __('Tipo') }}: </label>
        <select 
            class="flex flex-col rounded-md"
            name="movement_type_id" 
            id="movement_type_id"
        >
            <option value="">Selecione</option>
            
            @isset($userMovementType)
            @foreach ($userMovementType as $movementType)
                    <option 
                        value="{{ $movementType->id }}" 
                        @selected((isset($userStocksMovement->movement_type_id) ? $userStocksMovement->movement_type_id : old('movement_type_id')) == $movementType->id)
                    >{{ $movementType->description }}</option>
                @endforeach
            @endisset
        </select>
    </div>
    <div>
        <label for="quantity">{{ __('Quantidade') }}: </label>
        <input
            class="flex flex-col rounded-md" 
            type="text" 
            name="quantity" 
            id="quantity" 
            value="{{ isset($userStocksMovement) ? (old('quantity') ?? @valueFormat($userStocksMovement->quantity)) : (old('quantity') ?? '') }}"
        >
    </div>
    <div>
        <label for="value">{{ __('Valor') }}: </label>
        <input
            class="flex flex-col rounded-md" 
            type="text"
            name="value"
            id="value"
            placeholder="0000,00"
            value="{{ isset($userStocksMovement) ? (old('value') ?? @valueFormat($userStocksMovement->value)) : (old('value') ?? '') }}"
        >
    </div>
    <div>
        <label for="date">{{ __('Data') }}: </label>
        <input
            class="flex flex-col rounded-md" 
            type="date" 
            name="date" 
            id="date" 
            value="{{ isset($title) ? (old('date') ?? $title->date) : (old('date') ?? '') }}">
    </div>
</div>

@vite('resources/js/userStocksMovement.js')
