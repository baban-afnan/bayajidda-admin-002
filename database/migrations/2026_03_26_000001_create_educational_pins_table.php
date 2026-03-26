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
        Schema::create('educational_pins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('transaction_ref')->unique();
            $table->string('exam_name');
            $table->integer('quantity');
            $table->longText('pins')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['enabled', 'disabled'])->default('enabled');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('transaction_ref');
            $table->index('exam_name');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educational_pins');
    }
};
