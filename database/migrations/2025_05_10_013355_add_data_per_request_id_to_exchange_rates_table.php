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
        Schema::table('exchange_rates', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('data_per_request_id')->after('date');
            $table->foreign('data_per_request_id')->references('id')->on('data_per_requests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exchange_rates', function (Blueprint $table) {
            //
            $table->dropForeign('exchange_rates_data_per_request_id_foreign');
            $table->dropColumn('data_per_request_id');

        });
    }
};
