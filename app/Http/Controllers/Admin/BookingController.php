<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\FunGameRankTeam;
use App\Models\PelunasanBooking;
use App\Models\PembayaranBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function approve(Booking $booking): RedirectResponse
    {
        DB::transaction(function () use ($booking): void {
            $booking->update([
                'status_pembayaran' => 200,
                'status_pelunasan' => 0,
            ]);

            PembayaranBooking::where('kode_booking', $booking->kode_booking)
                ->update(['status' => 200]);

            $this->updateVoucher($booking);
        });

        return back()->with('success', 'Booking berhasil disetujui.');
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('cari'));

        $bookings = Booking::query()
            ->with(['pembayaran', 'pelunasan'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($bookingQuery) use ($search) {
                    $bookingQuery->where('team', 'like', '%'.$search.'%')
                        ->orWhere('tgl', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $bookingUsers = DB::table('tbl_user')
            ->whereIn('id_auth', $bookings->getCollection()->pluck('iduser')->filter()->unique())
            ->pluck('nama', 'id_auth');

        return view('admin.booking.index', [
            'bookings' => $bookings,
            'bookingUsers' => $bookingUsers,
            'search' => $search,
        ]);
    }

    public function complete(Booking $booking): RedirectResponse
    {
        DB::transaction(function () use ($booking): void {
            $booking->update([
                'status_pembayaran' => 200,
                'status_pelunasan' => 1,
            ]);

            PembayaranBooking::where('kode_booking', $booking->kode_booking)
                ->update(['status' => 200]);

            PelunasanBooking::where('kode_booking', $booking->kode_booking)
                ->update(['status' => 200]);

            $this->updateVoucher($booking);
        });

        return back()->with('success', 'Booking ditandai sudah lunas.');
    }

    public function reject(Booking $booking): RedirectResponse
    {
        DB::transaction(function () use ($booking): void {
            $booking->update([
                'status_pembayaran' => 400,
                'status_pelunasan' => 0,
            ]);

            PembayaranBooking::where('kode_booking', $booking->kode_booking)
                ->update(['status' => 400]);

            PelunasanBooking::where('kode_booking', $booking->kode_booking)
                ->update(['status' => 400]);

            $this->restoreRankTeamBookings($booking);
        });

        return back()->with('success', 'Booking berhasil ditolak.');
    }

    private function restoreRankTeamBookings(Booking $booking): void
    {
        $teamBooking = Booking::query()
            ->where('iduser', 'Memberkarir team')
            ->where('tgl', $booking->tgl)
            ->where('jam_booking', $booking->jam_booking)
            ->where('id_lapangan', $booking->id_lapangan)
            ->first();

        if ($teamBooking !== null) {
            Booking::query()
                ->where('iduser', 'Memberkarir team')
                ->where('tgl', $booking->tgl)
                ->where('jam_booking', $booking->jam_booking)
                ->where('id_lapangan', $booking->id_lapangan)
                ->update(['status_pembayaran' => 200]);

            FunGameRankTeam::where('kode_main', $teamBooking->kode_booking)
                ->update(['status' => 1]);
        }

        $jamBooking = match ($booking->jam_booking) {
            '23.00-24.00', '22.00-23.00' => '22.00-24.00',
            default => $booking->jam_booking,
        };

        $soloBooking = Booking::query()
            ->where('jam_booking', $jamBooking)
            ->where('id_lapangan', $booking->id_lapangan)
            ->where('tgl', $booking->tgl)
            ->where('user', 'Memberkarir team')
            ->first();

        if ($soloBooking !== null) {
            Booking::query()
                ->where('jam_booking', $jamBooking)
                ->where('id_lapangan', $booking->id_lapangan)
                ->where('tgl', $booking->tgl)
                ->where('user', 'Memberkarir team')
                ->update(['status_pembayaran' => 200]);

            FunGameRankTeam::where('kode_main', $soloBooking->kode_booking)
                ->update(['status' => 1]);
        }
    }

    private function updateVoucher(Booking $booking): void
    {
        if ((int) $booking->voucher !== 1) {
            return;
        }

        DB::table('tbl_voucher')
            ->where('kode_voucher', $booking->team)
            ->update([
                'status' => 1,
                'tgl_diambil' => now()->toDateString(),
            ]);
    }
}
