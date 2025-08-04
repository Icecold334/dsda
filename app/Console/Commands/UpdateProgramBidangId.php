<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateProgramBidangId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'program:update-bidang-id {--from=51 : Source bidang_id} {--to=50 : Target bidang_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update program bidang_id from 51 to 50';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fromBidangId = $this->option('from');
        $toBidangId = $this->option('to');

        $this->info("Updating programs with bidang_id {$fromBidangId} to {$toBidangId}...");

        // Check if there are any programs with the source bidang_id
        $programsCount = DB::table('programs')
            ->where('bidang_id', $fromBidangId)
            ->count();

        if ($programsCount === 0) {
            $this->warn("No programs found with bidang_id {$fromBidangId}");
            return 0;
        }

        $this->info("Found {$programsCount} program(s) to update");

        // Confirm the action
        if (!$this->confirm("Are you sure you want to update {$programsCount} program(s) from bidang_id {$fromBidangId} to {$toBidangId}?")) {
            $this->info('Operation cancelled');
            return 0;
        }

        try {
            // Update the programs
            $updatedRows = DB::table('programs')
                ->where('bidang_id', $fromBidangId)
                ->update([
                    'bidang_id' => $toBidangId,
                    'updated_at' => now()
                ]);

            $this->info("Successfully updated {$updatedRows} program(s)");

            // Show the updated programs
            $this->info("Updated programs:");
            $updatedPrograms = DB::table('programs')
                ->where('bidang_id', $toBidangId)
                ->select('id', 'kode', 'program', 'bidang_id')
                ->get();

            $this->table(
                ['ID', 'Kode', 'Program', 'Bidang ID'],
                $updatedPrograms->map(function ($program) {
                    return [
                        $program->id,
                        $program->kode ?? '-',
                        $program->program,
                        $program->bidang_id
                    ];
                })->toArray()
            );

        } catch (\Exception $e) {
            $this->error("Error updating programs: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
