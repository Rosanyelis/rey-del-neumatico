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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('customer_name');
            $table->decimal('total', 10, 2);
            $table->decimal('product_discount', 10, 2)->nullable();
            $table->string('order_discount_id')->nullable();
            $table->decimal('order_discount', 10, 2)->nullable();
            $table->decimal('total_discount', 10, 2)->nullable();
            $table->decimal('product_tax', 10, 2)->nullable();
            $table->string('order_tax_id')->nullable();
            $table->decimal('order_tax', 10, 2)->nullable();
            $table->decimal('total_tax', 10, 2)->nullable();
            $table->decimal('grand_total', 10, 2);
            $table->integer('total_items')->nullable();
            $table->integer('total_quantity')->nullable();
            $table->decimal('paid', 10, 2);
            $table->enum('payment_status', ['paid', 'due', 'partial']);
            $table->text('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
