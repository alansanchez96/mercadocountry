<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) { // datos contacot usuario (equivalente perfil)
            $table->id();
            $table->string('contact');
            $table->string('phone', 15);
            $table->enum('status', ['PENDIENTE', 'RECIBIDO', 'ENVIADO', 'ENTREGADO', 'CANCELADO']);
            $table->enum('dispatch_type', ['DOMICILIO', 'RETIRO DEPOSITO', 'DEPOSITO SUCURSAL']);
            $table->json('dispatch_address');
            $table->json('details_product');
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