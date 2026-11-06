<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Phase 17C: Drop legacy course_id and lecture_id columns from shoppingcarts table.
     */
    public function up(): void
    {
        Schema::table('shoppingcarts', function (Blueprint $table) {
            if (Schema::hasColumn('shoppingcarts', 'course_id')) {
                $table->dropColumn('course_id');
            }
            if (Schema::hasColumn('shoppingcarts', 'lecture_id')) {
                $table->dropColumn('lecture_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shoppingcarts', function (Blueprint $table) {
            if (!Schema::hasColumn('shoppingcarts', 'course_id')) {
                $table->unsignedBigInteger('course_id')->nullable()->after('product_id');
                $table->index('course_id', 'shoppingcarts_course_id_foreign');
            }
            if (!Schema::hasColumn('shoppingcarts', 'lecture_id')) {
                $table->unsignedBigInteger('lecture_id')->nullable()->after('course_id');
                $table->index('lecture_id', 'shoppingcarts_lecture_id_foreign');
            }
        });
    }
};
