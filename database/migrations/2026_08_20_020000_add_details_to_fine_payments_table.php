<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToFinePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fine_payments', function (Blueprint $table) {
            $table->string('reference_number')->nullable()->after('payment_date');
            $table->string('proof_path')->nullable()->after('reference_number');
            $table->text('notes')->nullable()->after('proof_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fine_payments', function (Blueprint $table) {
            $table->dropColumn(['reference_number', 'proof_path', 'notes']);
        });
    }
}
