<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Change status to VARCHAR temporarily
        DB::statement("ALTER TABLE conversations MODIFY status VARCHAR(20) DEFAULT 'open'");
        
        // Step 2: Update 'active' to 'open'
        DB::statement("UPDATE conversations SET status = 'open' WHERE status = 'active'");
        
        // Step 3: Change back to ENUM with new values
        DB::statement("ALTER TABLE conversations MODIFY status ENUM('open', 'closed', 'resolved') DEFAULT 'open'");
        
        // Step 4: Add resolved_at timestamp
        Schema::table('conversations', function (Blueprint $table) {
            $table->timestamp('resolved_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn('resolved_at');
            $table->enum('status', ['open', 'closed'])->default('open')->change();
        });
    }
};
