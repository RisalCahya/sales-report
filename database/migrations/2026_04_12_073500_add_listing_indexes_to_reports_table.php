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
        Schema::table('reports', function (Blueprint $table) {
            // Optimize admin listing query sorted by tanggal then created_at.
            $table->index(['tanggal', 'created_at'], 'reports_tanggal_created_at_idx');

            // Optimize sales listing query filtered by user_id and sorted by tanggal then created_at.
            $table->index(['user_id', 'tanggal', 'created_at'], 'reports_user_tanggal_created_at_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropIndex('reports_tanggal_created_at_idx');
            $table->dropIndex('reports_user_tanggal_created_at_idx');
        });
    }
};
