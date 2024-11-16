<div class="flex flex-wrap gap-4">
    <div>
        <label for="title">{{ __('Título') }}: </label>
        <input
            class="flex flex-col rounded-md" 
            type="text" 
            name="title" 
            id="title" 
            value="{{ isset($title->title) ? (old('title') ?? $title->title) : old('title') }}"
        >
    </div>
    <div>
        <label for="title_type_id">{{ __('Tipo') }}: </label>
        <select
            class="flex flex-rol rounded-md" 
            name="title_type_id" 
            id="title_type_id"
        >
            <option value="">{{ __('Selecione') }}</option>
            @if ($title_types)
                <optgroup label="Isento">
                    @foreach ($title_types as $title_type)
                        @if (!$title_type->has_irpf)
                            <option 
                                value="{{ $title_type->id }}" 
                                @selected($title_type->id == (isset($title) ? (old('title_type_id') ?? $title->title_type_id) : old('title_type_id')) ? true : false)
                            >
                                {{ $title_type->description }}
                            </option>
                        @endif
                    @endforeach
                </optgroup>
                <optgroup label="Não isento">
                    @foreach ($title_types as $title_type)
                        @if ($title_type->has_irpf)
                            <option 
                                value="{{ $title_type->id }}" 
                                @selected($title_type->id == (isset($title) ? (old('title_type_id') ?? $title->title_type_id) : old('title_type_id')) ? true : false)
                            >
                                {{ $title_type->description }}
                            </option>
                        @endif
                    @endforeach
                </optgroup>
            @endif
        </select>
    </div>
    <div>
        <label for="modality_id">{{ __('Modalidade') }}: </label>
        <select
            class="flex flex-rol rounded-md" 
            name="modality_id" 
            id="modality_id"
        >
            <option value="">{{ __('Selecione') }}</option>
            @if ($modalities)
                @foreach ($modalities as $modality)
                    <option 
                        value="{{ $modality->id }}" 
                        @selected($modality->id == (isset($title) ? (old('modality_id') ?? $title->modality_id) : old('modality_id')) ? true : false)
                    >
                        {{ $modality->description }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
    <div 
        id="taxDiv" 
        @style((in_array((isset($title) ? (old('modality_id') ?? $title->modality_id) : (old('modality_id') ?? 0)), [4, 6])) ? "display: none;" : "display:;")
    >
        <label for="tax">{{ __('Taxa') }}: </label>
        <input
            class="flex flex-col rounded-md" 
            type="text" 
            name="tax" 
            id="tax" 
            placeholder="00,00" 
            @disabled((in_array(((isset($title) ? (old('modality_id') ?? $title->modality_id) : (old('modality_id') ?? 0))), [4, 6])))
            value="{{ isset($title) ? (old('tax') ?? @valueFormat($title->tax)) : (old('tax') ?? '') }}"
        >
    </div>
    <div>
        <label for="date_buy">{{ __('Data de compra') }}: </label>
        <input
            class="flex flex-col rounded-md" 
            type="date" 
            name="date_buy" 
            id="date_buy" 
            value="{{ isset($title) ? (old('date_buy') ?? $title->date_buy) : (old('date_buy') ?? '') }}">
    </div>
    <div>
        <label for="date_liquidity">{{ __('Liquidez') }}: </label>
        <input
            class="flex flex-col rounded-md" 
            type="date" 
            name="date_liquidity" 
            id="date_liquidity" 
            value="{{ isset($title) ? (old('date_liquidity') ?? $title->date_liquidity) : (old('date_liquidity') ?? '') }}"
        >
    </div>
    <div>
        <label for="date_due">{{ __('Vencimento') }}: </label>
        <input
            class="flex flex-col rounded-md" 
            type="date" 
            name="date_due" 
            id="date_due" 
            value="{{ isset($title) ? (old('date_due') ?? $title->date_due) : old('date_due') }}"
        >
    </div>
    <div>
        <label for="value_buy">{{ __('Valor na compra') }}: </label>
        <input
            class="flex flex-col rounded-md" 
            type="text" 
            name="value_buy" 
            id="value_buy" 
            placeholder="0000,00" 
            value="{{ isset($title) ? (old('value_buy') ?? @valueFormat($title->value_buy)) : (old('value_buy') ?? '') }}"
        >
    </div>
    
    <div>
        <label for="value_current">{{ __('Valor atual') }}: </label>
        <input
            class="flex flex-col rounded-md" 
            type="text" 
            name="value_current" 
            id="value_current" 
            placeholder="0000,00" 
            value="{{ isset($title) ? (old('value_current') ?? @valueFormat($title->value_current)) : (old('value_current') ?? '') }}"
        >
    </div>
    
</div>

@vite('resources/js/titles.js')
