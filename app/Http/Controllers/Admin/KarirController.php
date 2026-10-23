<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberKarirTeam;
use App\Models\PemainRankTeam;
use App\Models\TeamRankTeam;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KarirController extends Controller
{
    public function teamRank(Request $request): View
    {
        $status = $request->query('status');
        $search = trim((string) $request->query('q'));

        $teams = TeamRankTeam::query()
            ->when($status === 'aktif', fn ($query) => $query->where('status', 1))
            ->when($status === 'nonaktif', fn ($query) => $query->where('status', 0))
            ->when($search !== '', fn ($query) => $query->where('nama_team', 'like', '%'.$search.'%'))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.karir.team-rank', [
            'teams' => $teams,
            'status' => $status,
            'search' => $search,
            'totalAktif' => TeamRankTeam::where('status', 1)->count(),
            'totalNonaktif' => TeamRankTeam::where('status', 0)->count(),
        ]);
    }

    public function teamRankDetail(TeamRankTeam $team): View
    {
        $pemain = PemainRankTeam::where('kode_team', $team->kode)
            ->orderBy('nama')
            ->get();

        return view('admin.karir.team-rank-detail', [
            'team' => $team,
            'pemainList' => $pemain,
        ]);
    }

    public function member(Request $request): View
    {
        $status = $request->query('status');
        $search = trim((string) $request->query('q'));

        $members = MemberKarirTeam::query()
            ->when($status === 'menunggu', fn ($query) => $query->where('status_pembayaran', 201))
            ->when($status === 'disetujui', fn ($query) => $query->where('status_pembayaran', 200))
            ->when($status === 'ditolak', fn ($query) => $query->where('status_pembayaran', 400))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('team', 'like', '%'.$search.'%')
                        ->orWhere('kode_main', 'like', '%'.$search.'%')
                        ->orWhere('kode_team', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.karir.member', [
            'members' => $members,
            'status' => $status,
            'search' => $search,
            'totalMenunggu' => MemberKarirTeam::where('status_pembayaran', 201)->count(),
            'totalDisetujui' => MemberKarirTeam::where('status_pembayaran', 200)->count(),
            'totalDitolak' => MemberKarirTeam::where('status_pembayaran', 400)->count(),
        ]);
    }

    public function memberSetujui(Request $request): RedirectResponse
    {
        $id = $request->input('id');
        $mm = MemberKarirTeam::findOrFail($id);

        $lawan = MemberKarirTeam::where('kode_main', $mm->kode_main)
            ->where('sesi', $mm->sesi)
            ->where('status_pembayaran', 200)
            ->where('id', '!=', $mm->id)
            ->first();

        if ($lawan === null) {
            $lw = '';
        } else {
            $lw = $lawan->team;

            $lawan->update([
                'status_pembayaran' => 200,
                'lawan' => $mm->team,
            ]);
        }

        $mm->update([
            'status_pembayaran' => 200,
            'lawan' => $lw,
        ]);

        return redirect()
            ->route('admin.karir.member')
            ->with('success', 'Date berhasil disetujui');
    }

    public function memberTolak(Request $request): RedirectResponse
    {
        $id = $request->input('id');
        $main = MemberKarirTeam::findOrFail($id);
        $kodeMain = $main->kode_main;
        $sesi = $main->sesi;

        $lawan = MemberKarirTeam::where('kode_main', $kodeMain)
            ->where('sesi', $sesi)
            ->whereNotIn('id', [$id])
            ->first();

        if ($lawan !== null) {
            $teamLawan = $lawan->team;

            MemberKarirTeam::where('kode_main', $kodeMain)
                ->where('team', $teamLawan)
                ->where('id', $lawan->id)
                ->where('sesi', $sesi)
                ->update(['lawan' => '']);
        }

        $main->update([
            'status_pembayaran' => 400,
            'lawan' => '',
        ]);

        return redirect()
            ->route('admin.karir.member')
            ->with('success', 'Date berhasil ditolak');
    }

    public function memberHapus(Request $request): RedirectResponse
    {
        $id = $request->input('id');
        MemberKarirTeam::findOrFail($id)->delete();

        return redirect()
            ->route('admin.karir.member')
            ->with('success', 'Date berhasil dihapus');
    }
}
