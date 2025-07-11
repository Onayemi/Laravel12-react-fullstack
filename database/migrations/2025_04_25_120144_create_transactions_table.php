<?php

use App\Models\User;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('prod_name');
            $table->string('reference')->unique();
            $table->decimal('amount', 10, 2);
            // $table->enum('status', ['pending', 'success', 'failed']);
            $table->string('status');
            $table->string('gateway_response');
            $table->string('ip_address');
            $table->string('country_name');
            $table->string('country_code');
            $table->string('timezone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
