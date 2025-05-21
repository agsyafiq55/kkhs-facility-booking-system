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
        Schema::table('bookings', function (Blueprint $table) {
            // Drop the existing foreign key constraint (which incorrectly points to facilities table)
            $table->dropForeign(['sub_facility_id']);
            
            // Add the correct foreign key constraint pointing to sub_facilities table
            $table->foreign('sub_facility_id')
                  ->references('id')
                  ->on('sub_facilities')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop the corrected foreign key
            $table->dropForeign(['sub_facility_id']);
            
            // Restore the original foreign key pointing to facilities table
            $table->foreign('sub_facility_id')
                  ->references('id')
                  ->on('facilities')
                  ->onDelete('cascade');
        });
    }
};
