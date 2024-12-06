<?php

namespace App\Imports;

use App\DTO\stocks\{UserStocksCreateUpdateDTO, UserStocksMovementCreateUpdateDTO};
use App\Http\Requests\{UserStocksMovementRequest, UserStocksRequest};
use App\Models\{Stocks, StocksMovementType};
use App\Services\{UserStocksMovementService, UserStocksService};
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\{
    OnEachRow, 
    WithValidation, 
    SkipsEmptyRows, 
    WithChunkReading, 
    WithHeadingRow
};
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
        $data['tipo_de_movimentacao'] = strtolower($data['tipo_de_movimentacao']);
        $data['codigo_de_negociacao'] = trim(rtrim($data['codigo_de_negociacao'], 'fF'));

        $data['data_do_negocio'] = is_numeric($data['data_do_negocio'])
            ? Carbon::create(1899, 12, 30)->addDays($data['data_do_negocio'])
            : Carbon::createFromFormat('d/m/Y', $data['data_do_negocio']);

        return $data;
    }

    public function rules(): array
    {
        return [
            'tipo_de_movimentacao' => [
                'required',
                'string',
            ],
            'codigo_de_negociacao' => [
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
                'decimal:0,2',
            ],
            'data_do_negocio' => [
                'required',
                'date'
            ],
        ];
    }

    public function chunkSize(): int
    {
        return 50;
    }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = $row->toArray(endColumn: 'I');

        $user_id = Auth::user()->id;

        $stocks = $this->stocks->firstOrCreate([
            'ticker' => $row['codigo_de_negociacao']
        ],[
            'name' => null,
            'stocks_types_id' => 1, //trocar o 1 para inserir conforme nova coluna na planilha de acordo com o tipo
            'status' => true
        ]);

        $userStocksDto = UserStocksCreateUpdateDTO::make(new UserStocksRequest([
            'user_id'       => $user_id,
            'stocks_id'     => $stocks->id,
            'quantity'      => '0.00',
            'average_value' => '0.00',
        ]));

        $movement_type_id = match ($row['tipo_de_movimentacao']) {
            'compra' => $this->stocksMovementType::BUY,
            'venda'  => $this->stocksMovementType::SALE,
            default  => throw new Exception("Tipo de movimento inválido! Tipo informado: {$row['tipo_de_movimentacao']}")
        };

        $userStocks = $this->userStocksService->forUpdateOrCreate($user_id, $stocks->id, $userStocksDto);

        if (
            $movement_type_id === $this->stocksMovementType::SALE
            && $userStocks->quantity < 1
        ) {
            return throw new Exception("Linha $rowIndex: Não é possível realizar venda para {$row['codigo_de_negociacao']}. Não há quantidades disponiveis em " . Carbon::parse($row['data_do_negocio'])->format('d/m/Y') . ". Quantidade na data: {$userStocks->quantity}. Quantidade da venda: {$row['quantidade']}.");
        }
        
        $this->userStocksMovementService->insertByImport(UserStocksMovementCreateUpdateDTO::make(
            new UserStocksMovementRequest([
                'user_id'          => $user_id,
                'stocks_id'        => $stocks->id,
                'user_stocks_id'   => $userStocks->id,
                'movement_type_id' => $movement_type_id,
                'quantity'         => $row['quantidade'],
                'value'            => $row['preco'],
                'date'             => $row['data_do_negocio'],
            ])
        ));
    }
}
