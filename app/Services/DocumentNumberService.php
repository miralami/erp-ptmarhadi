<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DocumentNumberService
{
    public function generate(string $prefix, string $table = null): string
    {
        $dateYmd = now()->format('ymd');

        return DB::transaction(function () use ($prefix, $dateYmd) {
            $counter = DB::table('document_counters')
                ->where('prefix', $prefix)
                ->where('date_ymd', $dateYmd)
                ->lockForUpdate()
                ->first();

            if ($counter) {
                $nextNumber = $counter->last_number + 1;
                DB::table('document_counters')
                    ->where('id', $counter->id)
                    ->update(['last_number' => $nextNumber]);
            } else {
                $nextNumber = 1;
                DB::table('document_counters')->insert([
                    'prefix' => $prefix,
                    'date_ymd' => $dateYmd,
                    'last_number' => $nextNumber,
                ]);
            }

            return $prefix . '-' . $dateYmd . '-' . str_pad((string)$nextNumber, 4, '0', STR_PAD_LEFT);
        });
    }
}
