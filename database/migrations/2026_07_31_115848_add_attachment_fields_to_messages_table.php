<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->string('message_type')->default('text')->after('message');
            $table->string('original_file_name')->nullable()->after('file_type');
            $table->unsignedBigInteger('file_size')->nullable()->after('original_file_name');
            $table->string('mime_type')->nullable()->after('file_size');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['message_type', 'original_file_name', 'file_size', 'mime_type']);
        });
    }
};
