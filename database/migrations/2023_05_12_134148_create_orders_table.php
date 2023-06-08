<?php

use App\Models\Order;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) { // datos contacot usuario (equivalente perfil)
            $table->id();
            // $table->string('contact');
            // $table->string('phone', 15);
            $table->enum('status', [
                Order::PENDIENTE,
                Order::RECIBIDO,
                Order::ENVIADO,
                Order::ENTREGADO,
                Order::CANCELADO
            ])->default(Order::PENDIENTE);

            $table->enum('dispatch_type', [
                Order::DOMICILIO,
                Order::SUCURSAL
            ]);

            $table->text('idPayment');
            $table->text('dispatch_address')->nullable();
            $table->float('shipping_cost');
            $table->float('total');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
