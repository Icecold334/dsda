<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Persetujuan;
use App\Models\User;
use Carbon\Carbon;

class TransferApprovalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'approval:transfer 
                            {--from-user= : ID user asal (default: 245 - Citrin)} 
                            {--to-user= : ID user tujuan (default: 252 - Yusuf)} 
                            {--start-date= : Tanggal mulai transfer (default: 2025-08-12)} 
                            {--end-date= : Tanggal akhir transfer (default: 2025-08-19)} 
                            {--dry-run : Preview tanpa mengubah data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer approval dari user Citrin ke Yusuf selama periode cuti (12-19 Agustus 2025)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fromUserId = $this->option('from-user') ?? 245; // Citrin
        $toUserId = $this->option('to-user') ?? 252; // Yusuf
        $startDate = $this->option('start-date') ?? '2025-08-12';
        $endDate = $this->option('end-date') ?? '2025-08-19';
        $dryRun = $this->option('dry-run');

        // Validasi user
        $fromUser = User::find($fromUserId);
        $toUser = User::find($toUserId);

        if (!$fromUser) {
            $this->error("User dengan ID {$fromUserId} tidak ditemukan!");
            return 1;
        }

        if (!$toUser) {
            $this->error("User dengan ID {$toUserId} tidak ditemukan!");
            return 1;
        }

        $this->info("=== TRANSFER APPROVAL ===");
        $this->info("Dari: {$fromUser->name} (ID: {$fromUserId})");
        $this->info("Ke: {$toUser->name} (ID: {$toUserId})");
        $this->info("Periode: {$startDate} s/d {$endDate}");
        $this->info("Mode: " . ($dryRun ? 'DRY RUN (Preview)' : 'LIVE UPDATE'));
        $this->line('');

        // Query approval yang akan ditransfer
        $startDateTime = Carbon::parse($startDate)->startOfDay();
        $endDateTime = Carbon::parse($endDate)->endOfDay();

        $approvals = Persetujuan::where('user_id', $fromUserId)
            ->whereBetween('created_at', [$startDateTime, $endDateTime])
            ->with([
                'approvable' => function ($query) {
                    $query->with('user.unitKerja');
                }
            ])
            ->get();

        if ($approvals->isEmpty()) {
            $this->info("Tidak ada approval yang ditemukan untuk periode tersebut.");
            return 0;
        }

        // Filter hanya approval dari unit kerja pusat (ID 44 atau parent_id 44)
        $pusatApprovals = $approvals->filter(function ($approval) {
            if ($approval->approvable && $approval->approvable->user && $approval->approvable->user->unitKerja) {
                $unitKerja = $approval->approvable->user->unitKerja;
                return $unitKerja->id == 44 || $unitKerja->parent_id == 44;
            }
            return false;
        });

        $this->info("Total approval ditemukan: " . $approvals->count());
        $this->info("Approval dari unit kerja pusat: " . $pusatApprovals->count());
        $this->line('');

        if ($pusatApprovals->isEmpty()) {
            $this->info("Tidak ada approval dari unit kerja pusat yang perlu ditransfer.");
            return 0;
        }

        // Tampilkan detail approval yang akan ditransfer
        $this->info("Detail approval yang akan ditransfer:");
        $this->table(
            ['ID Approval', 'Tanggal', 'Permintaan ID', 'Pemohon', 'Unit Kerja'],
            $pusatApprovals->map(function ($approval) {
                return [
                    $approval->id,
                    $approval->created_at->format('Y-m-d H:i:s'),
                    $approval->approvable_id,
                    $approval->approvable->user->name ?? 'N/A',
                    $approval->approvable->user->unitKerja->nama ?? 'N/A'
                ];
            })
        );

        if ($dryRun) {
            $this->info("\n=== DRY RUN COMPLETED ===");
            $this->info("Jumlah approval yang akan ditransfer: " . $pusatApprovals->count());
            $this->info("Tidak ada perubahan dilakukan pada database.");
            return 0;
        }

        // Konfirmasi sebelum melakukan transfer
        if (!$this->confirm('Apakah Anda yakin ingin melakukan transfer approval ini?', false)) {
            $this->info('Transfer dibatalkan.');
            return 0;
        }

        // Lakukan transfer
        $this->info("\nMemulai transfer approval...");
        $bar = $this->output->createProgressBar($pusatApprovals->count());
        $bar->start();

        $transferredCount = 0;

        foreach ($pusatApprovals as $approval) {
            try {
                $approval->update(['user_id' => $toUserId]);
                $transferredCount++;
            } catch (\Exception $e) {
                $this->error("\nGagal mentransfer approval ID {$approval->id}: " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->line("\n");

        $this->info("=== TRANSFER COMPLETED ===");
        $this->info("Berhasil mentransfer {$transferredCount} approval");
        $this->info("Dari: {$fromUser->name} ke {$toUser->name}");
        $this->info("Periode: {$startDate} s/d {$endDate}");

        return 0;
    }
}
