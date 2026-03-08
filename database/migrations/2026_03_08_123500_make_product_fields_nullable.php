<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
            $table->string('short_description', 500)->nullable()->change();
            $table->decimal('price', 10, 2)->nullable()->change();
            $table->string('brand')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
            $table->string('short_description', 500)->nullable(false)->change();
            $table->decimal('price', 10, 2)->nullable(false)->change();
            $table->string('brand')->nullable(false)->change();
        });
    }
};
