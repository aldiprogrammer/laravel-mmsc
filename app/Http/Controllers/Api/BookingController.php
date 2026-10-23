<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\DataUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        /** @var DataUser $user */
        $user = $request->attributes->get('api_user');
        $validated = $request->validate([
            'tgl' => ['sometimes', 'date_format:Y-m-d'],
            'status_pembayaran' => ['sometimes', 'in:201,200,400'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);
        $ownerIds = [(string) $user->id_auth, (string) $user->id];

        $bookings = Booking::query()
            ->whereIn('iduser', $ownerIds)
            ->when(isset($validated['tgl']), fn ($query) => $query->where('tgl', $validated['tgl']))
            ->when(isset($validated['status_pembayaran']), fn ($query) => $query->where('status_pembayaran', $validated['status_pembayaran']))
            ->select([
                'id', 'kode_booking', 'jam_booking', 'jam_mulai', 'jam_selesai', 'tgl', 'team',
                'lapangan', 'id_lapangan', 'status', 'status_pembayaran', 'status_pelunasan',
            ])
            ->orderByDesc('id')
            ->paginate($validated['per_page'] ?? 20);

        return response()->json([
            'data' => $bookings->items(),
            'meta' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
            ],
        ]);
    }

    public function show(Request $request, string $kodeBooking): JsonResponse
    {
        /** @var DataUser $user */
        $user = $request->attributes->get('api_user');

        $booking = Booking::query()
            ->where('kode_booking', $kodeBooking)
            ->whereIn('iduser', [(string) $user->id_auth, (string) $user->id])
            ->firstOrFail([
                'id', 'kode_booking', 'jam_booking', 'jam_mulai', 'jam_selesai', 'tgl', 'team',
                'lapangan', 'id_lapangan', 'status', 'status_pembayaran', 'status_pelunasan',
            ]);

        return response()->json(['data' => $booking]);
    }

    public function lapangan(Request $request, int $lapangan): JsonResponse
    {
        $validated = $request->validate([
            'tgl' => ['sometimes', 'date_format:Y-m-d'],
        ]);

        $bookings = Booking::query()
            ->where('id_lapangan', $lapangan)
            ->where('tgl', $validated['tgl'] ?? now()->timezone('Asia/Jakarta')->toDateString())
            ->whereNotIn('status_pembayaran', ['400'])
            ->select(['jam_mulai', 'jam_selesai', 'jam_booking', 'tgl', 'status'])
            ->orderBy('jam_mulai')
            ->get();

        return response()->json(['data' => $bookings]);
    }
}
