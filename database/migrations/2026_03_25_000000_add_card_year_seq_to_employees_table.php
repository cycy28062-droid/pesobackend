<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedSmallInteger('card_year')->nullable()->after('office');
            $table->unsignedSmallInteger('card_seq')->nullable()->after('card_year');
        });

        $rows = DB::table('employees')->orderBy('id')->get();
        $perYearCounter = [];
        foreach ($rows as $e) {
            $y = (int) Carbon::parse($e->created_at)->format('Y');
            $perYearCounter[$y] = ($perYearCounter[$y] ?? 0) + 1;
            $seq = $perYearCounter[$y];
            $yy = sprintf('%02d', $y % 100);
            $idDisplay = $yy.'-'.str_pad((string) $seq, 3, '0', STR_PAD_LEFT);

            DB::table('employees')->where('id', $e->id)->update([
                'card_year' => $y,
                'card_seq' => $seq,
                'id_display' => $idDisplay,
            ]);
        }

        Schema::table('employees', function (Blueprint $table) {
            $table->unique(['card_year', 'card_seq']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropUnique(['card_year', 'card_seq']);
            $table->dropColumn(['card_year', 'card_seq']);
        });
    }
};
