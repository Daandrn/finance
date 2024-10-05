<?php

namespace App\Imports;

use App\DTO\stocks\UserStocksCreateUpdateDTO;
use App\DTO\stocks\UserStocksMovementCreateUpdateDTO;
use App\Http\Requests\UserStocksMovementRequest;
use App\Http\Requests\UserStocksRequest;
use App\Models\Stocks;
use App\Models\StocksMovementType;
use App\Services\UserStocksMovementService;
use App\Services\UserStocksService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;

class UserStocksMovementsImport implements OnEachRow, WithValidation, WithHeadingRow, SkipsEmptyRows, WithChunkReading 
{
    public function __construct(
        protected Stocks $stocks,
        protected UserStocksService $userStocksService,
        protected StocksMovementType $stocksMovementType,
        protected UserStocksMovementService $userStocksMovementService,
    ) {
        //
    }

    public function prepareForValidation($data, $index)
    {
        $data['tipo_movimentacao'] = strtolower($data['tipo_movimentacao']);
        $data['codigo_negociacao'] = trim(rtrim($data['codigo_negociacao'], 'fF'));

        $data['data'] = is_numeric($data['data'])
                ? Carbon::create(1899, 12, 30)->addDays($data['data'])
                : Carbon::createFromFormat('d/m/Y', $data['data']);

        return $data;
    }

    public function rules(): array
    {
        return [
            'tipo_movimentacao' => [
                'required',
                'string',
            ],
            'codigo_negociacao' => [
                'required',
                'string',
                'max:6',
            ],
            'quantidade' => [
                'required',
                'integer',
            ],
            'preco' => [
                'required',
                'string',
            ],
            'data' => [
                'required',
            ],
            'desmembramento' => [
                'required',
                'integer',
            ],
            'agrupamento' => [
                'required',
                'integer',
            ],
        ];
    }

    public function chunkSize(): int
    {
        return 5;
    }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = $row->toArray(endColumn: 'G');

        $user_id = Auth::user()->id;

        $stocks = $this->stocks->firstOrCreate([
            'ticker' => $row['codigo_negociacao']
        ],[
            'name' => null,
            'stocks_types_id' => 1, //trocar o 1 para inserir conforme nova coluna na planilha de acordo com o tipo
        ]);

        $userStocksDto = UserStocksCreateUpdateDTO::make(new UserStocksRequest([
            'user_id'       => $user_id,
            'stocks_id'     => $stocks->id,
            'quantity'      => '0.00',
            'average_value' => '0.00',
        ]));

        $movement_type_id = match ($row['tipo_movimentacao']) {
            'compra' => stocksMovementType::BUY,
            'venda'  => stocksMovementType::SALE,
        };

        $userStocks = $this->userStocksService->forUpdateOrCreate($user_id, $stocks->id, $userStocksDto);

        $this->userStocksMovementService->insert(UserStocksMovementCreateUpdateDTO::make(
            new UserStocksMovementRequest([
                'user_id'          => $user_id,
                'stocks_id'        => $stocks->id,
                'user_stocks_id'   => $userStocks->id,
                'movement_type_id' => $movement_type_id,
                'quantity'         => $row['quantidade'],
                'value'            => $row['preco'],
                'date'             => $row['data'],
            ])
        ));

        return true;
    }
}
