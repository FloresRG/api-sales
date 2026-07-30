<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;

class SaleService
{
    /**
     * @param array $data
     * @return Sale
     * @throws Exception
     */
    public function createSale(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            $total = 0;
            $saleDetails = [];

            foreach ($data['items'] as $item) {
                // Bloqueo pesimista para evitar condiciones de carrera al descontar stock
                $product = Product::lockForUpdate()->find($item['product_id']);

                if (!$product) {
                    throw new Exception("El producto con ID {$item['product_id']} no existe.");
                }

                if ($product->stock < $item['quantity']) {
                    throw new Exception("Stock insuficiente para el producto: {$product->name}. Disponible: {$product->stock}");
                }

                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                $saleDetails[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];

                // Descontamos el stock
                $product->decrement('stock', $item['quantity']);
            }

            // Creamos la venta
            $sale = Sale::create([
                'user_id' => $data['user_id'],
                'total' => $total,
            ]);

            // Creamos los detalles usando la relación
            $sale->details()->createMany($saleDetails);

            return $sale;
        });
    }

    public function deleteSale(Sale $sale): void
    {
        DB::transaction(function () use ($sale) {
            // Restauramos el stock de cada producto vendido
            foreach ($sale->details as $detail) {
                $detail->product->increment('stock', $detail->quantity);
            }

            // Eliminamos la venta
            $sale->delete();
        });
    }
}
