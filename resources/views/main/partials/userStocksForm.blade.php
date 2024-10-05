<div>
    <input 
        type="hidden" 
        name="user_id" 
        id="user_id" 
        value="{{ isset($userStocks->user_id) ? (old('user_id') ?? $userStocks->user_id) : old('user_id') }}"
    >
</div>
<div>
    <label for="ticker">{{ __('Ticker') }}: </label>
    <input 
        type="text" 
        name="ticker" 
        id="ticker" 
        value="{{ isset($userStocks->stocks->ticker) ? (old('ticker') ?? $userStocks->stocks->ticker) : old('ticker') }}"
        @disabled($disabledSelect)
    >
    
    <select name="stocks_id" id="stocks_id" @disabled($disabledSelect)>
        @if (!isset($userStocks->stocks->ticker))
            <option value="">Selecione</option>
        @endif
        @isset($stocks)
            @foreach ($stocks as $stocksElement)
                <option 
                    value="{{ $stocksElement->id }}" 
                    data-ticker="{{ $stocksElement->ticker }}"
                    @selected((isset($userStocks->stocks->ticker) ? $userStocks->stocks->ticker : old('ticker')) == $stocksElement->ticker)
                >{{ $stocksElement->ticker }}</option>
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
        @readonly(true)
        value="{{ isset($userStocks) ? @valueFormat($userStocks->quantity) : '0,00' }}"
    > 
</div>
<div>
    <label for="average_value">{{ __('Valor médio') }}: </label>
    <input 
        type="text"
        name="average_value"
        id="average_value"
        placeholder="0000,00"
        value="{{ isset($userStocks) ? (old('average_value') ?? @valueFormat($userStocks->average_value)) : (old('average_value') ?? '') }}"
    >
</div>


@vite('resources/js/userStocks.js')
