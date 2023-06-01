<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CartCollection;

class CartController extends Controller
{
    private function getUser()
    {
        return auth()->user();
    }

    public function addToCart(Request $request)
    {
        try {
            $user = $this->getUser();

            $foundProduct = false;

            foreach ($user->carts as $product) {
                if ($product->product_id == $request->product_id) {
                    $product->quantity = $product->quantity + $request->input('quantity', 1);
                    $product->save();
                    $foundProduct = true;
                    break;
                }
            }

            if (!$foundProduct) {
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $request->input('product_id'),
                    'quantity' =>  $request->input('quantity', 1)
                ]);
            }

            return response()->json([
                'message' => 'Producto añadido al carrito correctamente'
            ]);
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }

    public function viewCart()
    {
        try {
            $user = $this->getUser();

            $cartItems = Cart::where('user_id', $user->id)->get();

            return new CartCollection($cartItems);
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }

    public function updateCartItem(Request $request)
    {
        try {
            $user = $this->getUser();
            $cartItem = Cart::where('user_id', $user->id)
                ->where('product_id', $request->input('product_id'))
                ->first();

            $newQuantity = $request->input('quantity');
 
            // Actualizar la cantidad del producto en el carrito
            $cartItem->quantity = $newQuantity;
            $cartItem->save();

            // Calcular el precio total del producto en función de la cantidad
            $product = $cartItem->products;
            $unitPrice = $product->price;
            $totalPriceProduct = $unitPrice * $newQuantity;

            // Calcular el precio total de todos los productos en el carrito del usuario
            $totalCartPrice = Cart::where('user_id', $user->id)
                ->join('products', 'carts.product_id', '=', 'products.id')
                ->selectRaw('SUM(carts.quantity * products.price) as total_price')
                ->value('total_price');

            return response()->json([
                'total_product_price' => $totalPriceProduct,
                'total_cart_price' => $totalCartPrice,
            ], 200);
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }

    public function removeCartItem($id)
    {
        $user = $this->getUser();

        $cartItem = Cart::where('user_id', $user->id)
                ->where('product_id', $id)
                ->first();

        $cartItem->delete();

        return response()->json([
            'message' => 'Producto eliminado correctamente del carrito'
        ]);
    }

    public function pay()
    {
     
    }
}
