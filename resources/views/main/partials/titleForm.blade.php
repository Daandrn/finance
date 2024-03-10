<div>
    <label for="title">Título</label>
    <input type="text" name="title" id="title" value="{{ isset($title->title) ? (old('title') ?? $title->title) : old('title') }}">
</div>
<div>
    <label for="modality_id">Modalidade</label>
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
    <label for="tax">Taxa</label>
    <input type="text" name="tax" id="tax" placeholder="00.00" value="{{ isset($title) ? (old('tax') ?? $title->tax) : old('tax') }}">
</div>
<div>
    <label for="date_buy">Data de compra</label>
    <input type="text" name="date_buy" id="date_buy" placeholder="__/__/___" value="{{ isset($title) ? (old('date_buy') ?? @carbonDate($title->date_buy)) : old('date_buy') }}">
</div>
<div>
    <label for="date_liquidity">Liquidez</label>
    <input type="text" name="date_liquidity" id="date_liquidity" placeholder="__/__/___" value="{{ isset($title) ? (old('date_liquidity') ?? @carbonDate($title->date_liquidity)) : old('date_liquidity') }}">
</div>
<div>
    <label for="date_due">Vencimento</label>
    <input type="text" name="date_due" id="date_due" placeholder="__/__/___" value="{{ isset($title) ? (old('date_due') ?? @carbonDate($title->date_due)) : old('date_due') }}">
</div>
<button type="submit">Salvar</button>