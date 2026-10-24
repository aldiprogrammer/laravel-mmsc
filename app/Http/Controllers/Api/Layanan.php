<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class Layanan extends Controller
{
    public function index(Request $request)
    {
        $kode = $request->query('kode');

        $data = DB::table('tbl_pembayaran_layanan')
            ->where('kode_booking', $kode)
            ->first();

        $data2 = DB::table('tbl_pembayaranbooking')
            ->where('kode_booking', $kode)
            ->first();

        if (!$data && !$data2) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $status = 0;

        if ($data2) {
            if ($data2->status == 200) {
                $status = 1;
            } elseif ($data2->status == 400) {
                $status = 400;
            }
        }

        if ($data && $data2) {
            return response()->json([
                'foto' => $data->foto,
                'foto2' => $data2->poto,
                'wasit' => $data->wasit,
                'wasit2' => $data2->wasit,
                'bola' => $data->bola,
                'bola2' => $data2->bola,
                'rompi' => $data->rompi,
                'rompi2' => $data2->rompi,
                'status' => $data->status,
            ], 200);
        }

        if (!$data && $data2) {
            return response()->json([
                'foto' => 0,
                'foto2' => $data2->poto,
                'wasit' => 0,
                'wasit2' => $data2->wasit,
                'bola' => 0,
                'bola2' => $data2->bola,
                'rompi' => 0,
                'rompi2' => $data2->rompi,
                'status' => $status,
            ], 200);
        }

        return response()->json([
            'foto' => $data->foto,
            'foto2' => 0,
            'wasit' => $data->wasit,
            'wasit2' => 0,
            'bola' => $data->bola,
            'bola2' => 0,
            'rompi' => $data->rompi,
            'rompi2' => 0,
            'status' => $data->status,
        ], 200);
    }

    /**
     * POST: Tambah pembayaran layanan atau update layanan yang sudah ada.
     */
    public function store(Request $request)
    {
        $kode = $request->input('kode_book1');

        $foto = $request->input('foto') ?? 0;
        $wasit = $request->input('wasit') ?? 0;
        $rompi = $request->input('rompi') ?? 0;
        $bola = $request->input('bola') ?? 0;

        try {
            $result = DB::transaction(function () use (
                $request,
                $kode,
                $foto,
                $wasit,
                $rompi,
                $bola
            ) {
                $cek = DB::table('tbl_pembayaran_layanan')
                    ->where('kode_booking', $kode)
                    ->first();

                if ($cek) {
                    // Update hanya field yang dikirim dan tidak null.
                    $update = [];

                    foreach (
                        [
                            'foto' => $foto,
                            'wasit' => $wasit,
                            'rompi' => $rompi,
                            'bola' => $bola,
                        ] as $field => $value
                    ) {
                        if ($request->input($field) !== null) {
                            $update[$field] = $value;
                        }
                    }

                    if (!empty($update)) {
                        DB::table('tbl_pembayaran_layanan')
                            ->where('kode_booking', $kode)
                            ->update($update);
                    }

                    // Simpan bukti pembayaran.
                    DB::table('tbl_bukti_pembayaran_layanan')
                        ->insert([
                            'kode_booking' => $kode,
                            'bukti' => $request->input('bukti'),
                        ]);

                    // Hitung ulang total harga.
                    $totalHarga = $this->hitungTotalHarga($kode);

                    DB::table('tbl_pembayaran_layanan')
                        ->where('kode_booking', $kode)
                        ->update([
                            'total_harga' => $totalHarga,
                        ]);

                    return true;
                }

                // Buat pembayaran layanan baru.
                $data = [
                    'username' => $request->input('username'),
                    'id_user' => $request->input('iduser'),
                    'kode_booking' => $kode,
                    'kode_booking2' => $request->input('kode_book2'),
                    'foto' => $foto,
                    'wasit' => $wasit,
                    'rompi' => $rompi,
                    'bola' => $bola,
                    'nama_team' => $request->input('team'),
                    'bank' => $request->input('bank'),
                    'atas_nama' => $request->input('atasnama'),
                    'tanggal' => now()->toDateString(),
                    'total_harga' => $request->input('totalharga'),
                ];

                DB::table('tbl_pembayaran_layanan')->insert($data);

                DB::table('tbl_bukti_pembayaran_layanan')
                    ->insert([
                        'kode_booking' => $kode,
                        'bukti' => $request->input('bukti'),
                    ]);

                return true;
            });

            return response()->json([
                'message' => 'true',
            ], 201);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'error',
            ], 502);
        }
    }

    /**
     * PUT: Menghapus pilihan layanan dan menghitung ulang harga.
     */
    public function updateService(
        string $kode,
        string $idfoto,
        string $layanan
    ) {
        $kolomLayanan = [
            'foto' => 'foto',
            'wasit' => 'wasit',
            'bola' => 'bola',
            'rompi' => 'rompi',
        ];

        if (!isset($kolomLayanan[$layanan])) {
            return response()->json([
                'message' => 'Layanan tidak valid',
            ], 400);
        }

        try {
            $result = DB::transaction(function () use (
                $kode,
                $kolomLayanan,
                $layanan
            ) {
                $booking = DB::table('tbl_pembayaran_layanan')
                    ->where('kode_booking', $kode)
                    ->first();

                if (!$booking) {
                    return false;
                }

                DB::table('tbl_pembayaran_layanan')
                    ->where('kode_booking', $kode)
                    ->update([
                        $kolomLayanan[$layanan] => 0,
                    ]);

                $totalHarga = $this->hitungTotalHarga($kode);

                if ($totalHarga == 0) {
                    DB::table('tbl_pembayaran_layanan')
                        ->where('kode_booking', $kode)
                        ->delete();

                    DB::table('tbl_bukti_pembayaran_layanan')
                        ->where('kode_booking', $kode)
                        ->delete();
                } else {
                    DB::table('tbl_pembayaran_layanan')
                        ->where('kode_booking', $kode)
                        ->update([
                            'total_harga' => $totalHarga,
                        ]);
                }

                return true;
            });

            if (!$result) {
                return response()->json([
                    'message' => 'Data tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'message' => 'true',
            ], 201);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'error',
            ], 500);
        }
    }

    /**
     * Hitung total harga semua layanan.
     */
    private function hitungTotalHarga(string $kode): float
    {
        $data = DB::table('tbl_pembayaran_layanan')
            ->where('kode_booking', $kode)
            ->first();

        if (!$data) {
            return 0;
        }

        $hargaFoto = DB::table('tbl_potografer')
            ->where('id', $data->foto)
            ->value('harga') ?? 0;

        $hargaWasit = DB::table('tbl_wasit')
            ->where('id', $data->wasit)
            ->value('harga') ?? 0;

        $hargaBola = DB::table('tbl_bola')
            ->where('id', $data->bola)
            ->value('harga') ?? 0;

        $hargaRompi = DB::table('tbl_rompi')
            ->where('id', $data->rompi)
            ->value('harga') ?? 0;

        return (float) (
            $hargaFoto +
            $hargaWasit +
            $hargaBola +
            $hargaRompi
        );
    }
}
