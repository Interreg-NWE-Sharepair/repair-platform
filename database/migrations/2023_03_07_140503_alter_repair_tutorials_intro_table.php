<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void
    {
        if (!Schema::hasColumn('repair_tutorials', 'has_disclaimer')) {
            Schema::table('repair_tutorials', function (Blueprint $table) {

                $table->boolean('has_disclaimer')->default(0);
            });
        }
    }

    public function down(): void
    {
        Schema::table('repair_tutorials', function (Blueprint $table) {
            $table->dropColumn('has_disclaimer');
        });
    }
};
