<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Pemain;
use App\Models\Pertandingan;
use App\Models\SesiMain;
use App\Models\Team;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * @var list<string>
     */
    private const DONUT_PALETTE = ['#1d4ed8', '#3b82f6', '#60a5fa', '#0ea5e9', '#38bdf8', '#6366f1'];

    public function index(): View
    {
        $totalPemain = Pemain::count();
        $pemainBaruBulanIni = Pemain::where('date', '>=', now()->startOfMonth())->count();

        $totalTeam = Team::count();
        $kodeTeamUnik = Team::distinct()->count('kode_team');

        $totalLapangan = Lapangan::count();
        $lapanganSiapBooking = Lapangan::where('status_booking', '0')->count();

        $bookingBulanIni = $this->bookingCountBetween(now()->startOfMonth(), now()->endOfMonth());
        $bookingBulanLalu = $this->bookingCountBetween(
            now()->subMonthNoOverflow()->startOfMonth(),
            now()->subMonthNoOverflow()->endOfMonth(),
        );

        return view('admin.dashboard', [
            'stats' => [
                [
                    'label' => 'Total Pemain',
                    'value' => $totalPemain,
                    'hint' => '+'.$pemainBaruBulanIni.' pemain baru bulan ini',
                    'icon' => 'user-group',
                    'tone' => 'blue',
                ],
                [
                    'label' => 'Tim Terdaftar',
                    'value' => $totalTeam,
                    'hint' => $kodeTeamUnik.' kode tim unik',
                    'icon' => 'shield-check',
                    'tone' => 'indigo',
                ],
                [
                    'label' => 'Lapangan',
                    'value' => $totalLapangan,
                    'hint' => $lapanganSiapBooking.' lapangan siap untuk booking',
                    'icon' => 'map-pin',
                    'tone' => 'sky',
                ],
                [
                    'label' => 'Booking Bulan Ini',
                    'value' => $bookingBulanIni,
                    'hint' => $this->growthLabel($bookingBulanIni, $bookingBulanLalu),
                    'icon' => 'calendar-days',
                    'tone' => 'cyan',
                ],
            ],
            'chart7Hari' => $this->bookingChart(7),
            'chart30Hari' => $this->bookingChart(30),
            'lapanganPopuler' => $this->bookingPerLapangan(),
            'pertandinganTerakhir' => Pertandingan::orderByDesc('id')->limit(6)->get(),
            'bookingTerbaru' => Booking::where('status_hapus', 0)->orderByDesc('id')->limit(6)->get(),
            'pemainTerbaru' => Pemain::orderByDesc('id')->limit(6)->get(),
            'sesiMendatang' => SesiMain::where('tgl_main', '>=', now()->toDateString())
                ->orderBy('tgl_main')
                ->orderBy('sesi')
                ->limit(5)
                ->get(),
        ]);
    }

    private function bookingCountBetween(Carbon $from, Carbon $to): int
    {
        return Booking::where('status_hapus', 0)
            ->whereBetween('tgl', [$from->toDateString(), $to->toDateString()])
            ->count();
    }

    private function growthLabel(int $current, int $previous): string
    {
        if ($previous === 0) {
            return $current > 0 ? 'Booking baru bulan ini' : 'Belum ada booking bulan ini';
        }

        $growth = round((($current - $previous) / $previous) * 100, 1);
        $sign = $growth >= 0 ? '+' : '';

        return $sign.$growth.'% dibanding bulan lalu';
    }

    /**
     * @return list<array{label: string, date: string, value: int}>
     */
    private function bookingChart(int $days): array
    {
        $start = now()->subDays($days - 1)->startOfDay();

        $counts = Booking::where('status_hapus', 0)
            ->where('tgl', '>=', $start->toDateString())
            ->get(['tgl'])
            ->groupBy('tgl')
            ->map(fn ($rows) => $rows->count());

        $data = [];

        for ($offset = $days - 1; $offset >= 0; $offset--) {
            $date = now()->subDays($offset);

            $data[] = [
                'label' => $date->translatedFormat('D'),
                'date' => $date->format('d M'),
                'value' => (int) $counts->get($date->toDateString(), 0),
            ];
        }

        return $data;
    }

    /**
     * @return array{segments: list<array{label: string, value: int, percent: float, color: string}>, gradient: string, total: int}
     */
    private function bookingPerLapangan(): array
    {
        $rows = Booking::where('status_hapus', 0)
            ->whereNotNull('lapangan')
            ->where('lapangan', '!=', '')
            ->select('lapangan', DB::raw('count(*) as total'))
            ->groupBy('lapangan')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $total = (int) $rows->sum('total');
        $offset = 0.0;
        $segments = [];

        foreach ($rows as $index => $row) {
            $percent = $total > 0 ? round(((int) $row->total / $total) * 100, 2) : 0.0;
            $color = self::DONUT_PALETTE[$index % count(self::DONUT_PALETTE)];

            $segments[] = [
                'label' => $row->lapangan,
                'value' => (int) $row->total,
                'percent' => $percent,
                'color' => $color,
                'start' => $offset,
                'end' => $offset + $percent,
            ];

            $offset += $percent;
        }

        $gradient = 'conic-gradient('.implode(', ', array_map(
            fn (array $segment) => $segment['color'].' '.$segment['start'].'% '.$segment['end'].'%',
            $segments,
        )).')';

        return [
            'segments' => $segments,
            'gradient' => $gradient,
            'total' => $total,
        ];
    }
}
