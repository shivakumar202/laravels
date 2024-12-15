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
        Schema::create('rent_details', function (Blueprint $table) {
            $table->id();
            $table->string('customer');
            $table->string('order_id')->nullable();
            $table->string('contact');
            $table->string('items');
            $table->string('qtys');
            $table->string('for');
            $table->integer('duration');
            $table->date('rent_date');
            $table->date('end_date');
            $table->float('totalCost');
            $table->float('paid_amt')->default('0');
            $table->string('payment_status')->default(0);
            $table->string('status')->default(1);
            $table->string('approved_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_details');
    }
};
