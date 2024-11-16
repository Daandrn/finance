<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\split\SplitCreateUpdateDTO;
use App\Http\Requests\SplitRequest;
use App\Models\Split;
use App\Services\SplitService;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class SplitController extends Controller
{
    public function __construct(
        protected SplitService $service,
    ) {
        //
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function get(int $stocks_id)
    {
        $response = $this->service->get($stocks_id);
        
        return response([
            'message' => 'ok',
            'body' => $response->toJson()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SplitRequest $request): RedirectResponse
    {
        $response = $this->service->create(
            SplitCreateUpdateDTO::make($request)
        );

        $message = [
            'message' => $response['message']
        ];

        $redirectRoute = redirect()->route('stocks.edit', $request->stocks_id);
        
        return $response['error']
            ? $redirectRoute->withErrors($message)
            : $redirectRoute->with($message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Split $split)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Split $split)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SplitRequest $request)
    {
        dd($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        dd($request);
    }
}
