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
        Schema::create('activity_update_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_activity_entry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('updated_by')->constrained('users');
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->text('remark')->nullable();
            $table->decimal('actual_value', 12, 2)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_update_logs');
    }
};
