<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use GuzzleHttp\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private $client;
    private $clientId;
    private $secret;

    public function __construct()
    {
        $this->client = new Client([
            // 'base_uri' => 'https://api-m.paypal.com'
            'base_uri' => 'https://api-m.sandbox.paypal.com'
        ]);

        $this->clientId = 'AZW69n2rOXAD_5m2TB_tFf5Vp2WXdmJ4LFQGgiFYYreeWRYSBF1zHwOYEQXu9rnVBwa85S9McUpriEJi';
        $this->secret = 'EKRlb-AchJNlKj9ugZkS2eN17LsNFdB-1kFEG-C8Le_V_96m_GSmDQ3wz4o2eT4mjj0vQ9WNXP1u9eTA';
    }

    private function getAccessToken()
    {
        $response = $this->client->request(
            'POST',
            '/v1/oauth2/token',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'body' => 'grant_type=client_credentials',
                'auth' => [
                    $this->clientId, $this->secret, 'basic'
                ]
            ]
        );

        $data = json_decode($response->getBody(), true);
        return $data['access_token'];
    }

    private function responseFalse()
    {
        return response()->json(['success' => false]);
    }



    public function process($orderId): JsonResponse
    {
        $user = auth()->user();
        $accessToken = $this->getAccessToken();
        $requestUrl = "/v2/checkout/orders/$orderId/capture";

        // Obtener los productos en el carrito del usuario
        $cartItems = Cart::where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            $this->responseFalse();
        }

        $response = $this->client->request('POST', $requestUrl, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer $accessToken"
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        if ($data['status'] === 'COMPLETED') {
            $payPalPaymentId = $data['purchase_units'][0]['payments']['captures'][0]['id'];
            $amount = $data['purchase_units'][0]['payments']['captures'][0]['amount']['value'];


            // Obtener los IDs de los productos en el carrito
            $cartItemIds = $cartItems->pluck('product_id');

            // Transacciones
            try {
                DB::beginTransaction();
                // Crear una nueva orden
                $order = Order::create([
                    'status' => Order::PENDIENTE,
                    'dispatch_type' => Order::DOMICILIO,
                    'shipping_cost' => 10, // tengo que ver como envio esto al front
                    'idPayment' => $payPalPaymentId,
                    'total' => $amount,
                    'user_id' => $user->id,
                ]);

                // Comprobar si la orden se creó correctamente
                if (!$order) {
                    $this->responseFalse();
                }

                // con esto nos ahorramos una conculta a la db
                $productsArray = [];

                // Validar la disponibilidad de los productos seleccionados
                foreach ($cartItemIds as $cartItemId) {
                    $product = Product::find($cartItemId);

                    // Comprobar que el producto exista y tenga disponibilidad
                    if (!$product || $product->stock == '0') {
                        $this->responseFalse();
                    }

                    $productsArray[] = $product;
                }

                // Actualizar la disponibilidad de los productos y guardar en una transacción
                foreach ($productsArray as $product) {
                    $product->stock -= 1;
                    $product->save();
                    $order->products()->attach($product->id);
                }

                // Eliminar los productos del carrito
                $user->carts()->delete();

                return response()->json([
                    'success' => true,
                    'amount' => $amount
                ]);
            } catch (\Exception $e) {

                DB::rollback();

                $this->responseFalse();
            }
        }

        // Dar una respuesta de error si el estado no es COMPLETED
        $this->responseFalse();
    }

    public function removeCartProduct()
    {
        $user = auth()->user();

        // Eliminar los productos del carrito
        $user->carts()->delete();
    }
}
