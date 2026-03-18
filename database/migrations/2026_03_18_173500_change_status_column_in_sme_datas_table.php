<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sme_datas', function (Blueprint $table) {
            $table->string('status')->default('active')->change();
        });

        // Update existing boolean records to strings if necessary
        DB::table('sme_datas')->where('status', '1')->update(['status' => 'active']);
        DB::table('sme_datas')->where('status', '0')->update(['status' => 'inactive']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sme_datas', function (Blueprint $table) {
            $table->boolean('status')->default(true)->change();
        });

        DB::table('sme_datas')->where('status', 'active')->update(['status' => '1']);
        DB::table('sme_datas')->where('status', 'inactive')->update(['status' => '0']);
    }
};
