<div>
    <input 
        type="hidden" 
        name="user_id" 
        id="user_id" 
        value="{{ isset($userStocksMovement) ? (old('user_id') ?? $userStocksMovement->user_id) : (old('user_id') ?? Auth::user()->id) }}"
    > 
</div>
<div>
    <label for="ticker">{{ __('Ticker') }}: </label>
    <input 
        type="text" 
        name="ticker" 
        id="ticker" 
        value="{{ isset($userStocksMovement->ticker) ? (old('ticker') ?? $userStocksMovement->ticker) : old('ticker') }}"
    >
    <select name="stocks_id" id="stocks_id">
        @if (!isset($userStocksMovement->ticker))
            <option value="">Selecione</option>   
        @endif

        @isset($stocks)
            @foreach ($stocks as $stocksElement)
                <option
                    value="{{ $stocksElement->id }}" 
                    data-ticker="{{ $stocksElement->ticker }}"
                    @selected((isset($userStocksMovement->stocks_id) ? $userStocksMovement->stocks_id : old('stocks_id')) == $stocksElement->id)
                >{{ $stocksElement->ticker }}</option>
            @endforeach
        @endisset
    </select>
</div>
<div>
    <label for="quantity">{{ __('Tipo') }}: </label>
    <select name="movement_type_id" id="movement_type_id">
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
        type="text" 
        name="quantity" 
        id="quantity" 
        value="{{ isset($userStocksMovement) ? (old('quantity') ?? @valueFormat($userStocksMovement->quantity)) : (old('quantity') ?? '') }}"
    >
</div>
<div>
    <label for="value">{{ __('Valor médio') }}: </label>
    <input 
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
        type="date" 
        name="date" 
        id="date" 
        value="{{ isset($title) ? (old('date') ?? $title->date) : (old('date') ?? '') }}">
</div>


@vite('resources/js/userStocksMovement.js')
