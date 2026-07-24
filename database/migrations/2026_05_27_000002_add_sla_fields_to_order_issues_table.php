<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_issues', function (Blueprint $table) {
            $table->timestamp('reported_at')->nullable()->after('status');
            $table->timestamp('response_due_at')->nullable()->after('reported_at');
            $table->timestamp('resolution_due_at')->nullable()->after('response_due_at');
            $table->timestamp('first_responded_at')->nullable()->after('resolution_due_at');
            $table->timestamp('resolved_at')->nullable()->after('first_responded_at');
            $table->string('sla_status')->default('on_track')->after('resolved_at');
        });

        DB::table('order_issues')->orderBy('id')->chunkById(100, function ($issues) {
            foreach ($issues as $issue) {
                $reportedAt = \Carbon\Carbon::parse($issue->created_at ?? now());
                DB::table('order_issues')->where('id', $issue->id)->update([
                    'reported_at' => $reportedAt,
                    'response_due_at' => $reportedAt->copy()->addHours(config('service.complaint_sla.response_hours', 4)),
                    'resolution_due_at' => $reportedAt->copy()->addHours(config('service.complaint_sla.resolution_hours', 48)),
                    'sla_status' => 'on_track',
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_issues', function (Blueprint $table) {
            $table->dropColumn([
                'reported_at',
                'response_due_at',
                'resolution_due_at',
                'first_responded_at',
                'resolved_at',
                'sla_status',
            ]);
        });
    }
};
