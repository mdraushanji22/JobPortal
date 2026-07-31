<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->foreignId('employer_id')->constrained()->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_listing_id')->constrained()->onDelete('cascade');
            $table->enum('letter_type', ['offer', 'joining'])->default('offer');
            $table->date('offer_date')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('salary_ctc')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->string('work_location')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('reporting_manager')->nullable();
            $table->string('hr_name')->nullable();
            $table->string('hr_email')->nullable();
            $table->string('hr_phone')->nullable();
            $table->longText('terms')->nullable();
            $table->string('signature_name')->nullable();
            $table->longText('content')->nullable();
            $table->string('pdf_path')->nullable();
            $table->enum('status', ['draft', 'sent', 'archived'])->default('draft');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index('letter_type');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
