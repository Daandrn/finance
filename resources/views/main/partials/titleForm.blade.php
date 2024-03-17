<div>
    <label for="title">{{ __('Título') }}: </label>
    <input type="text" name="title" id="title" value="{{ isset($title->title) ? (old('title') ?? $title->title) : old('title') }}">
</div>
<div>
    <label for="title_type_id">{{ __('Tipo') }}: </label>
    <select name="title_type_id" id="title_type_id">
        <option value=""></option>
        @if ($title_types)
            @foreach ($title_types as $title_type)
            <option value="{{ $title_type->id }}" @selected($title_type->id == (isset($title) ? (old('title_type_id') ?? $title->title_type_id) : old('title_type_id')) ? true : false)>{{ $title_type->description }}</option>
            @endforeach
        @endif
    </select>
</div>
<div>
    <label for="tax">{{ __('Taxa') }}: </label>
    <input type="text" name="tax" id="tax" placeholder="00.00" value="{{ isset($title) ? (old('tax') ?? $title->tax) : old('tax') }}">
</div>
<div>
    <label for="modality_id">{{ __('Modalidade') }}: </label>
    <select name="modality_id" id="modality_id">
        <option value=""></option>
        @if ($modalities)
        @foreach ($modalities as $modality)
        <option value="{{ $modality->id }}" @selected($modality->id == (isset($title) ? (old('modality_id') ?? $title->modality_id) : old('modality_id')) ? true : false)>{{ $modality->description }}</option>
        @endforeach
        @endif
    </select>
</div>
<div>
    <label for="date_buy">{{ __('Data de compra') }}: </label>
    <input type="text" name="date_buy" id="date_buy" placeholder="__/__/___" value="{{ isset($title) ? (old('date_buy') ?? @carbonDate($title->date_buy)) : old('date_buy') }}">
</div>
<div>
    <label for="date_liquidity">{{ __('Liquidez') }}: </label>
    <input type="text" name="date_liquidity" id="date_liquidity" placeholder="__/__/___" value="{{ isset($title) ? (old('date_liquidity') ?? @carbonDate($title->date_liquidity)) : old('date_liquidity') }}">
</div>
<div>
    <label for="date_due">{{ __('Vencimento') }}: </label>
    <input type="text" name="date_due" id="date_due" placeholder="__/__/___" value="{{ isset($title) ? (old('date_due') ?? @carbonDate($title->date_due)) : old('date_due') }}">
</div>
<div>
    <label for="value_buy">{{ __('Valor na compra') }}: </label>
    <input type="text" name="value_buy" id="value_buy" placeholder="0.000,00" value="{{ isset($title) ? (old('value_buy') ?? @valueFormat($title->value_buy)) : old('value_buy') }}">
</div>
<div>
    <label for="value_current">{{ __('Valor atual') }}: </label>
    <input type="text" name="value_current" id="value_current" placeholder="0.000,00" value="{{ isset($title) ? (old('value_current') ?? @valueFormat($title->value_current)) : old('value_current') }}">
</div>