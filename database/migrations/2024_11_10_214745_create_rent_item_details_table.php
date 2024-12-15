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
        Schema::create('rent_item_details', function (Blueprint $table) {
            $table->id();
            $table->string('customer');
            $table->string('order_id');
            $table->string('item');
            $table->string('image');
            $table->string('receive_image');

            
            $table->integer('qty');
            $table->float('cost');
            $table->string('status')->default(1);
            $table->string('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_item_details');
    }
};
