<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class Addbookingbaru2 extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeMultiple(Request $request)
    {
        // Validasi img
        if ($request->input('img') === null || $request->input('img') === '') {
            return response()->json([
                'message' => 'false'
            ], 500);
        }

        // Validasi item
        $request->validate([
            'item' => ['required', 'array', 'min:1'],
            'item.*.lapangan' => ['required'],
            'item.*.jam_mulai' => ['required'],
            'item.*.jam_berakhir' => ['required'],
            'item.*.tanggal' => ['required', 'date'],
            'item.*.harga_diskon' => ['required', 'numeric'],
        ]);

        try {
            // Cek voucher
            if ($request->input('voucher') === null) {
                $voucher = DB::table('tbl_voucher')
                    ->where('status', 0)
                    ->where('kode_voucher', $request->input('team'))
                    ->exists() ? 1 : 0;
            } else {
                $voucher = 1;
            }

            $items = $request->input('item');
            $kodebook = 'book-' . random_int(0, 10000)
                . random_int(0, 1000000000);

            $foto = '';
            $bookings = [];

            DB::transaction(function () use (
                $request,
                $items,
                $voucher,
                $kodebook,
                &$foto,
                &$bookings
            ) {
                foreach ($items as $jam2) {
                    // Cari lapangan
                    $lapangan = DB::table('tbl_lapangan')
                        ->where('id', $jam2['lapangan'])
                        ->first();

                    if (!$lapangan) {
                        throw new \RuntimeException(
                            'Lapangan tidak ditemukan: ' . $jam2['lapangan']
                        );
                    }

                    $kode = 'kode-' . random_int(0, 10000)
                        . random_int(0, 1000000000);

                    // Data booking
                    $data = [
                        'kode_booking' => $kode,
                        'iduser' => $request->input('iduser'),
                        'user' => $request->input('nama'),
                        'jam_booking' => $jam2['jam_mulai']
                            . '-' . $jam2['jam_berakhir'],
                        'jam_mulai' => $jam2['jam_mulai'],
                        'jam_selesai' => $jam2['jam_berakhir'],
                        'tgl' => $jam2['tanggal'],
                        'team' => $request->input('team'),
                        'lapangan' => $lapangan->lapangan,
                        'id_lapangan' => $jam2['lapangan'],
                        'status' => 'menunggu',
                        'sistem_pembayaran' => $request->input(
                            'sistempembayaran'
                        ),
                        'tgl_booking' => now()->toDateString(),
                        'voucher' => $voucher,
                        'status_pembayaran' => 201,
                        'kode_book2' => $kodebook,
                        'jmljam' => 1,
                    ];

                    // Hitung pembayaran
                    $totalHarga = (float) $request->input('totalharga');

                    if ($request->input('sistempembayaran') === 'Dp') {
                        $pembayaran = $totalHarga / 2;
                    } else {
                        $pembayaran = $totalHarga;
                    }

                    // Pertahankan logika foto dari kode asli
                    $statusFoto = ($foto == '8') ? 1 : 0;

                    // Data pembayaran
                    $data2 = [
                        'kode_booking' => $kode,
                        'atasnama' => $request->input('atasnama'),
                        'norek' => $request->input('norek'),
                        'harga' => $jam2['harga_diskon'],
                        'status' => 201,
                        'bukti' => $request->input('bukti'),
                        'status_pembayaran' => $request->input(
                            'sistempembayaran'
                        ),
                        'total_harga' => $totalHarga,
                        'pembayaran' => $pembayaran,
                        'wa' => $request->input('wa'),
                        'wasit' => $jam2['idwasit'] ?? null,
                        'video' => '',
                        'poto' => $jam2['idfoto'] ?? null,
                        'status_poto_duajam' => $statusFoto,
                        'rompi' => $jam2['idrompi'] ?? null,
                        'bola' => $jam2['idbola'] ?? null,
                    ];

                    // Simpan pembayaran dan booking
                    DB::table('tbl_pembayaranbooking')->insert($data2);
                    DB::table('tbl_booking')->insert($data);

                    $bookings[] = $data;

                    // Pertahankan urutan logika kode asli
                    $foto = $jam2['idfoto'] ?? null;

                    /*
                     * Kode CI3 memanggil:
                     * $this->index_get(
                     *     $jam2['id'],
                     *     $jam2['lapangan'],
                     *     $jam2['tanggal']
                     * );
                     *
                     * Pindahkan logika index_get() ke method Laravel
                     * tersendiri dan panggil di sini jika masih diperlukan.
                     */
                }
            });

            return response()->json([
                'message' => true,
                'kode_book2' => $kodebook,
                'data' => $bookings,
            ], 201);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'false',
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
