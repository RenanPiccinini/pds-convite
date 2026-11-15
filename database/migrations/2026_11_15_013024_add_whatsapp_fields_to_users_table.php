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
        Schema::table('users', function (Blueprint $table) {
            $table->string('whatsapp_instance')->nullable()->after('remember_token');
            $table->string('whatsapp_status')->default('disconnected')->after('whatsapp_instance'); // disconnected, connecting, connected
            $table->string('whatsapp_phone')->nullable()->after('whatsapp_status');
            $table->timestamp('whatsapp_connected_at')->nullable()->after('whatsapp_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'whatsapp_instance',
                'whatsapp_status',
                'whatsapp_phone',
                'whatsapp_connected_at',
            ]);
        });
    }
};
