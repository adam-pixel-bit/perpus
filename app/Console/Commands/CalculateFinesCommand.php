<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\Borrowing;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateFinesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'perpus:calculate-fines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kalkulasi denda keterlambatan buku harian dan kirimkan notifikasi pengingat otomatis';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today = Carbon::today();
        $this->info("Menjalankan kalkulasi denda perpustakaan per tanggal {$today->toDateString()}...");

        // 1. Hitung Denda Keterlambatan (Status: borrowed, lewat jatuh tempo)
        $overdueBorrowings = Borrowing::with(['user', 'book'])
            ->where('status', 'borrowed')
            ->where('return_due_date', '<', $today)
            ->get();

        $updatedCount = 0;
        $totalFinesCalculated = 0;

        foreach ($overdueBorrowings as $borrowing) {
            $daysLate = (int) $borrowing->return_due_date->diffInDays($today);
            $finePerDay = 1000; // Rp 1.000 / hari
            $calculatedFine = $daysLate * $finePerDay;

            if ($borrowing->fines != $calculatedFine) {
                $borrowing->fines = $calculatedFine;
                $borrowing->save();
                $updatedCount++;
                $totalFinesCalculated += $calculatedFine;

                // Cek apakah sudah pernah kirim notifikasi denda hari ini
                $alreadyNotifiedToday = Notification::where('user_id', $borrowing->user_id)
                    ->where('type', 'warning')
                    ->whereDate('created_at', $today)
                    ->where('message', 'like', "%{$borrowing->borrowing_code}%")
                    ->exists();

                if (! $alreadyNotifiedToday) {
                    Notification::create([
                        'user_id' => $borrowing->user_id,
                        'title' => 'Peringatan Denda Keterlambatan',
                        'message' => "Peminjaman [{$borrowing->borrowing_code}] buku '{$borrowing->book->title}' telah melewati batas tempo {$daysLate} hari. Akumulasi denda saat ini: Rp ".number_format($calculatedFine, 0, ',', '.').'. Harap segera kembalikan ke perpustakaan.',
                        'type' => 'warning',
                        'link' => route('user.borrowings.index'),
                    ]);
                }
            }
        }

        // 2. Kirim Notifikasi Pengingat H-1 Jatuh Tempo
        $tomorrow = Carbon::today()->addDay();
        $dueTomorrowBorrowings = Borrowing::with(['user', 'book'])
            ->where('status', 'borrowed')
            ->whereDate('return_due_date', $tomorrow)
            ->get();

        $remindersSent = 0;
        foreach ($dueTomorrowBorrowings as $borrowing) {
            $alreadyReminded = Notification::where('user_id', $borrowing->user_id)
                ->where('type', 'info')
                ->whereDate('created_at', $today)
                ->where('message', 'like', '%jatuh tempo besok%')
                ->exists();

            if (! $alreadyReminded) {
                Notification::create([
                    'user_id' => $borrowing->user_id,
                    'title' => 'Pengingat Batas Pengembalian Buku',
                    'message' => "Batas pengembalian buku '{$borrowing->book->title}' [{$borrowing->borrowing_code}] akan jatuh tempo besok ({$tomorrow->format('d M Y')}).",
                    'type' => 'info',
                    'link' => route('user.borrowings.index'),
                ]);
                $remindersSent++;
            }
        }

        AuditLog::create([
            'action' => 'cron_calculate_fines',
            'description' => "Otomasi cron: Denda diperbarui untuk {$updatedCount} peminjaman (Total Rp ".number_format($totalFinesCalculated, 0, ',', '.').") & {$remindersSent} pengingat H-1 terkirim.",
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Console / Cron Job',
            'created_at' => now(),
        ]);

        $this->info("Kalkulasi selesai: {$updatedCount} peminjaman diperbarui. {$remindersSent} pengingat terkirim.");

        return Command::SUCCESS;
    }
}
