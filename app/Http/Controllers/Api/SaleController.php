<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\StoreSaleRequest;
use App\Http\Resources\SaleResource;
use App\Models\Sale;
use App\Services\SaleService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SaleController extends Controller
{
    public function __construct(
        private SaleService $saleService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        $sales = Sale::with('details.product')->paginate(15);
        return SaleResource::collection($sales);
    }

    public function store(StoreSaleRequest $request): JsonResponse
    {
        try {
            $sale = $this->saleService->createSale($request->validated());
            
            return response()->json([
                'message' => 'Venta creada correctamente',
                'sale_id' => $sale->id,
                'total' => (float) $sale->total
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al crear la venta',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy(Sale $sale): JsonResponse
    {
        try {
            $this->saleService->deleteSale($sale);

            return response()->json([
                'message' => 'Venta eliminada correctamente'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la venta'
            ], 500);
        }
    }
}
