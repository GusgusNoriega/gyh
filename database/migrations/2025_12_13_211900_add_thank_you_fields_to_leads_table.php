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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('thank_you_token', 80)->nullable()->unique()->after('user_agent');
            $table->timestamp('thank_you_viewed_at')->nullable()->index()->after('thank_you_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['thank_you_token', 'thank_you_viewed_at']);
        });
    }
};

