<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('meetings', function (Blueprint $table) {
            // Add admin_id and created_by_type
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('cascade');
            $table->enum('created_by_type', ['user', 'admin'])->default('user');
            
            // Make user_id nullable since admins can now create meetings
            $table->foreignId('user_id')->nullable()->change();
        });

        Schema::table('meeting_participants', function (Blueprint $table) {
            // Add admin_id for participants
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('cascade');
            
            // Make user_id nullable since admins can participate
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('meeting_participants', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
            $table->foreignId('user_id')->nullable(false)->change();
        });

        Schema::table('meetings', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn(['admin_id', 'created_by_type']);
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};