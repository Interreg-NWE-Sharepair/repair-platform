<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void
    {
        Schema::table('common_device_type_issues', function (Blueprint $table) {
            $table->json('description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('common_device_type_issues', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
