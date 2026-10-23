<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\FunGame;
use App\Models\Lapangan;
use App\Models\PembayaranBooking;
use App\Models\SesiMain;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OpenSoloController extends Controller
{
    /**
     * @var list<string>
     */
    private const JAM_OPTIONS = [
        '06.00', '07.00', '08.00', '09.00', '10.00', '11.00', '12.00',
        '13.00', '14.00', '15.00', '16.00', '17.00', '18.00', '19.00',
        '20.00', '21.00', '22.00', '23.00', '24.00',
    ];

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        return view('admin.karir.open-solo', [
            'game' => FunGame::query()
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(function ($gameQuery) use ($search) {
                        $gameQuery->where('kode_main', 'like', '%'.$search.'%')
                            ->orWhere('tgl_main', 'like', '%'.$search.'%')
                            ->orWhere('jam_main', 'like', '%'.$search.'%')
                            ->orWhere('lapangan', 'like', '%'.$search.'%');
                    });
                })
                ->orderByDesc('id')
                ->get(),
            'search' => $search,
            'lapangan' => Lapangan::orderBy('id')->get(),
            'jamOptions' => self::JAM_OPTIONS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tgl_main' => ['required', 'date'],
            'jam_mulai' => ['required', 'string'],
            'jam_selesai' => ['required', 'string'],
            'lapangan' => ['required', 'exists:tbl_lapangan,id'],
            'jml_pemain' => ['required', 'integer', 'min:1'],
            'open_sesi' => ['nullable', 'in:0,1,2'],
            'harga' => ['required', 'numeric', 'min:0'],
        ]);

        $bentrok = Booking::whereNotIn('status_pembayaran', ['400'])
            ->where('jam_mulai', $validated['jam_mulai'])
            ->where('jam_selesai', $validated['jam_selesai'])
            ->where('tgl', $validated['tgl_main'])
            ->where('id_lapangan', $validated['lapangan'])
            ->exists();

        if ($bentrok) {
            return back()
                ->withInput()
                ->with('error', 'Jadwal booking lapangan sudah digunakan');
        }

        DB::transaction(function () use ($validated, $request) {
            $kodeMain = 'kodemain-'.random_int(0, 100000);
            $lapangan = Lapangan::findOrFail($validated['lapangan']);
            $jamTengah = $this->nextHourLabel($validated['jam_mulai']);

            FunGame::create([
                'kode_main' => $kodeMain,
                'tgl_main' => $validated['tgl_main'],
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
                'jam_main' => $validated['jam_mulai'].'-'.$validated['jam_selesai'],
                'jml_team' => $validated['jml_pemain'],
                'jml_pemain' => $validated['jml_pemain'],
                'id_lapangan' => $lapangan->id,
                'lapangan' => $lapangan->lapangan,
                'harga' => $validated['harga'],
                'harga_gk' => $validated['harga'],
                'status' => 1,
            ]);

            SesiMain::create([
                'kode_main' => $kodeMain,
                'tgl_main' => $validated['tgl_main'],
                'sesi' => 1,
                'team' => 'Hitam',
                'lawan' => 'Putih',
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $jamTengah,
            ]);

            SesiMain::create([
                'kode_main' => $kodeMain,
                'tgl_main' => $validated['tgl_main'],
                'sesi' => 2,
                'team' => 'Kuning',
                'lawan' => 'Merah',
                'jam_mulai' => $jamTengah,
                'jam_selesai' => $validated['jam_selesai'],
            ]);

            $openSesi = $request->input('open_sesi');
            if ($openSesi == 1) {
                SesiMain::where('kode_main', $kodeMain)->where('sesi', 2)->update(['status' => 1]);
            } elseif ($openSesi == 2) {
                SesiMain::where('kode_main', $kodeMain)->where('sesi', 1)->update(['status' => 1]);
            }

            Booking::create([
                'kode_booking' => $kodeMain,
                'iduser' => 'Member karir',
                'user' => 'Member karir',
                'jam_booking' => $validated['jam_mulai'].'-'.$validated['jam_selesai'],
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
                'tgl' => $validated['tgl_main'],
                'team' => 'Member karir',
                'lapangan' => $lapangan->lapangan,
                'id_lapangan' => $lapangan->id,
                'status' => 'setujui',
                'sistem_pembayaran' => 'DP',
                'status_pembayaran' => '200',
                'status_hapus' => 0,
                'status_pelunasan' => 0,
                'booking' => '',
                'status_jam' => '',
                'parents' => '',
                'tgl_booking' => '',
                'voucher' => 0,
                'status_jam_booking' => 0,
                'status_update' => 0,
                'kode_book2' => '',
                'jmljam' => '',
                'rankteam' => 0,
                'status_foto' => 0,
            ]);

            PembayaranBooking::create([
                'kode_booking' => $kodeMain,
                'norek' => 'Admin',
                'atasnama' => 'Admin',
                'harga' => 0,
                'wasit' => '',
                'bola' => '',
                'rompi' => '',
                'poto' => '',
                'status_poto_duajam' => 0,
                'video' => 0,
                'status_pembayaran' => '',
                'pembayaran' => '',
                'total_harga' => '',
                'bukti' => '',
                'status' => 200,
                'wa' => '',
            ]);
        });

        return redirect()
            ->route('admin.karir.open-solo')
            ->with('success', 'Fungame berhasil dibuka');
    }

    public function update(Request $request, FunGame $game): RedirectResponse
    {
        $validated = $request->validate([
            'tgl_main' => ['required', 'date'],
            'jam_mulai' => ['required', 'string'],
            'jam_selesai' => ['required', 'string'],
            'lapangan' => ['required', 'exists:tbl_lapangan,id'],
            'jml_pemain' => ['required', 'integer', 'min:1'],
            'harga' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($validated, $game) {
            $lapangan = Lapangan::findOrFail($validated['lapangan']);
            $jamTengah = $this->nextHourLabel($validated['jam_mulai']);

            $game->update([
                'tgl_main' => $validated['tgl_main'],
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
                'jam_main' => $validated['jam_mulai'].'-'.$validated['jam_selesai'],
                'jml_team' => $validated['jml_pemain'],
                'jml_pemain' => $validated['jml_pemain'],
                'id_lapangan' => $lapangan->id,
                'lapangan' => $lapangan->lapangan,
                'harga' => $validated['harga'],
                'harga_gk' => $validated['harga'],
                'status' => 1,
            ]);

            Booking::where('kode_booking', $game->kode_main)->update([
                'iduser' => 'Member karir',
                'user' => 'Member karir',
                'jam_booking' => $validated['jam_mulai'].'-'.$validated['jam_selesai'],
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
                'tgl' => $validated['tgl_main'],
                'team' => 'Member karir',
                'lapangan' => $lapangan->lapangan,
                'id_lapangan' => $lapangan->id,
                'status' => 'setujui',
                'sistem_pembayaran' => 'DP',
                'status_pembayaran' => '200',
            ]);

            SesiMain::where('kode_main', $game->kode_main)->delete();

            SesiMain::create([
                'kode_main' => $game->kode_main,
                'tgl_main' => $validated['tgl_main'],
                'sesi' => 1,
                'team' => 'Hitam',
                'lawan' => 'Putih',
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $jamTengah,
            ]);

            SesiMain::create([
                'kode_main' => $game->kode_main,
                'tgl_main' => $validated['tgl_main'],
                'sesi' => 2,
                'team' => 'Kuning',
                'lawan' => 'Merah',
                'jam_mulai' => $jamTengah,
                'jam_selesai' => $validated['jam_selesai'],
            ]);
        });

        return redirect()
            ->route('admin.karir.open-solo')
            ->with('success', 'Fungame berhasil diubah');
    }

    public function destroy(FunGame $game): RedirectResponse
    {
        DB::transaction(function () use ($game) {
            $game->delete();
            SesiMain::where('kode_main', $game->kode_main)->delete();
            Booking::where('kode_booking', $game->kode_main)->delete();
        });

        return redirect()
            ->route('admin.karir.open-solo')
            ->with('success', 'Fungame berhasil dihapus');
    }

    public function toggle(FunGame $game): RedirectResponse
    {
        $game->update([
            'status' => $game->isAktif() ? 0 : 1,
        ]);

        return redirect()
            ->route('admin.karir.open-solo')
            ->with('success', 'Fungame berhasil ubah');
    }

    private function nextHourLabel(string $jamMulai): string
    {
        $hour = (int) $jamMulai + 1;
        $label = str_pad((string) $hour, 2, '0', STR_PAD_LEFT);

        return $label.'.00';
    }
}
