<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        // ✅ SQLite (CI) không hỗ trợ MODIFY / ENUM
        if ($driver === 'sqlite') {
            // Chỉ đảm bảo có cột resolved_at để app/test không lỗi
            Schema::table('conversations', function (Blueprint $table) {
                if (!Schema::hasColumn('conversations', 'resolved_at')) {
                    $table->timestamp('resolved_at')->nullable();
                }
            });

            // Không làm gì thêm với status trên SQLite
            return;
        }

        // ✅ MySQL / MariaDB
        // Step 1: Change status to VARCHAR temporarily
        DB::statement("ALTER TABLE conversations MODIFY status VARCHAR(20) DEFAULT 'open'");

        // Step 2: Update 'active' to 'open'
        DB::statement("UPDATE conversations SET status = 'open' WHERE status = 'active'");

        // Step 3: Change back to ENUM with new values
        DB::statement("ALTER TABLE conversations MODIFY status ENUM('open', 'closed', 'resolved') DEFAULT 'open'");

        // Step 4: Add resolved_at timestamp (check tồn tại để tránh chạy lại bị lỗi)
        Schema::table('conversations', function (Blueprint $table) {
            if (!Schema::hasColumn('conversations', 'resolved_at')) {
                $table->timestamp('resolved_at')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        // SQLite: chỉ cần drop resolved_at nếu có
        if ($driver === 'sqlite') {
            Schema::table('conversations', function (Blueprint $table) {
                if (Schema::hasColumn('conversations', 'resolved_at')) {
                    $table->dropColumn('resolved_at');
                }
            });
            return;
        }

        // MySQL / MariaDB
        Schema::table('conversations', function (Blueprint $table) {
            if (Schema::hasColumn('conversations', 'resolved_at')) {
                $table->dropColumn('resolved_at');
            }
        });

        // Trả status về enum cũ
        DB::statement("ALTER TABLE conversations MODIFY status ENUM('open', 'closed') DEFAULT 'open'");
    }
};
