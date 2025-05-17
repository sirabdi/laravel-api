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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('shipping_method_id')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->unsignedBigInteger('user_address_id')->nullable();
            $table->string('email');
            $table->double('shipping_price')->nullable()->default(0.0);
            $table->double('tax')->nullable()->default(0.0);
            $table->double('grand_total')->default(0.0);
            $table->integer('qty')->default(1);
            $table->string('order_status')->default('pending');
            $table->string('payment_status')->default('pending');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('shipping_method_id')
                ->references('id')
                ->on('shipping_methods')
                ->onDelete('cascade');
            $table->foreign('payment_method_id')
                ->references('id')
                ->on('payment_methods')
                ->onDelete('cascade');
            $table->foreign('user_address_id')
                ->references('id')
                ->on('user_addresses')
                ->onDelete('cascade');
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
