<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\FunGameRankTeam;
use App\Models\Lapangan;
use App\Models\MemberKarirTeam;
use App\Models\PemainRankTeam;
use App\Models\PembayaranBooking;
use App\Models\SesiMainRankTeam;
use App\Models\TeamRankTeam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OpenKarirTeamController extends Controller
{
    /**
     * @var list<string>
     */
    private const JAM_OPTIONS = [
        '06.00',
        '07.00',
        '08.00',
        '09.00',
        '10.00',
        '11.00',
        '12.00',
        '13.00',
        '14.00',
        '15.00',
        '16.00',
        '17.00',
        '18.00',
        '19.00',
        '20.00',
        '21.00',
        '22.00',
        '23.00',
        '24.00',
    ];

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        return view('admin.karir.open', [
            'game' => FunGameRankTeam::query()
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(function ($gameQuery) use ($search) {
                        $gameQuery->where('kode_main', 'like', '%'.$search.'%')
                            ->orWhere('tgl_main', 'like', '%'.$search.'%')
                            ->orWhere('jam_main', 'like', '%'.$search.'%')
                            ->orWhere('lapangan', 'like', '%'.$search.'%');
                    });
                })
                ->orderByDesc('id')
                ->limit(50)
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
            'jml_team' => ['required', 'integer', 'min:1'],
            'open_sesi' => ['required', 'in:All,Sesi 1,Sesi 2'],
            'harga' => ['required', 'numeric', 'min:0'],
            'harga2' => ['required', 'numeric', 'min:0'],
        ]);

        [$sesi1, $sesi2] = $this->openSesiFlags($validated['open_sesi']);

        $bentrok = $this->bookingBentrok(
            $validated['tgl_main'],
            $validated['jam_mulai'],
            $validated['jam_selesai'],
            (int) $validated['lapangan'],
        );

        if ($bentrok) {
            return back()
                ->withInput()
                ->with('error', 'Jadwal booking lapangan sudah digunakan');
        }

        DB::transaction(function () use ($validated, $sesi1, $sesi2) {
            $kodeMain = 'kodemain-'.random_int(0, 10000).random_int(0, 100000);
            $lapangan = Lapangan::findOrFail($validated['lapangan']);

            FunGameRankTeam::create([
                'kode_main' => $kodeMain,
                'jam_main' => $validated['jam_mulai'].'-'.$validated['jam_selesai'],
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
                'harga' => $validated['harga'],
                'harga2' => $validated['harga2'],
                'tgl_main' => $validated['tgl_main'],
                'jml_team' => $validated['jml_team'],
                'id_lapangan' => $lapangan->id,
                'lapangan' => $lapangan->lapangan,
                'open_sesi' => $validated['open_sesi'],
                'status' => 1,
            ]);

            $jamTengah = $this->nextHourLabel($validated['jam_mulai']);

            SesiMainRankTeam::create([
                'kode_main' => $kodeMain,
                'sesi' => 1,
                'jam_main' => $validated['jam_mulai'].' - '.$jamTengah,
                'lapangan' => $lapangan->lapangan,
                'harga' => $validated['harga'],
                'status' => $sesi1,
            ]);

            SesiMainRankTeam::create([
                'kode_main' => $kodeMain,
                'sesi' => 2,
                'jam_main' => $jamTengah.' - '.$validated['jam_selesai'],
                'lapangan' => $lapangan->lapangan,
                'harga' => $validated['harga2'],
                'status' => $sesi2,
            ]);

            Booking::create([
                'kode_booking' => $kodeMain,
                'iduser' => 'Memberkarir team',
                'user' => 'Memberkarir team',
                'jam_booking' => $validated['jam_mulai'].'-'.$validated['jam_selesai'],
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
                'tgl' => $validated['tgl_main'],
                'team' => 'Memberkarir team',
                'id_lapangan' => $lapangan->id,
                'lapangan' => $lapangan->lapangan,
                'status' => 'Menunggu',
                'sistem_pembayaran' => 'Dp',
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
            ->route('admin.karir.open.index')
            ->with('success', 'Game berhasil tambah');
    }

    public function update(Request $request, FunGameRankTeam $game): RedirectResponse
    {
        $validated = $request->validate([
            'tgl_main' => ['required', 'date'],
            'jam_mulai' => ['required', 'string'],
            'jam_selesai' => ['required', 'string'],
            'lapangan' => ['required', 'exists:tbl_lapangan,id'],
            'jml_team' => ['required', 'integer', 'min:1'],
            'harga' => ['required', 'numeric', 'min:0'],
            'harga2' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($validated, $game) {
            $lapangan = Lapangan::findOrFail($validated['lapangan']);

            $game->update([
                'jam_main' => $validated['jam_mulai'].'-'.$validated['jam_selesai'],
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
                'harga' => $validated['harga'],
                'harga2' => $validated['harga2'],
                'tgl_main' => $validated['tgl_main'],
                'jml_team' => $validated['jml_team'],
                'id_lapangan' => $lapangan->id,
                'lapangan' => $lapangan->lapangan,
            ]);

            SesiMainRankTeam::where('kode_main', $game->kode_main)->delete();

            $jamTengah = $this->nextHourLabel($validated['jam_mulai']);

            SesiMainRankTeam::create([
                'kode_main' => $game->kode_main,
                'sesi' => 1,
                'jam_main' => $validated['jam_mulai'].' - '.$jamTengah,
                'lapangan' => $lapangan->lapangan,
                'harga' => $validated['harga'],
                'status' => 0,
            ]);

            SesiMainRankTeam::create([
                'kode_main' => $game->kode_main,
                'sesi' => 2,
                'jam_main' => $jamTengah.' - '.$validated['jam_selesai'],
                'lapangan' => $lapangan->lapangan,
                'harga' => $validated['harga'],
                'status' => 0,
            ]);
        });

        return redirect()
            ->route('admin.karir.open.index')
            ->with('success', 'Game berhasil ubah');
    }

    public function destroy(FunGameRankTeam $game): RedirectResponse
    {
        $sudahTerisi = MemberKarirTeam::where('kode_main', $game->kode_main)
            ->whereNotIn('status_pembayaran', [400])
            ->exists();

        if ($sudahTerisi) {
            return redirect()
                ->route('admin.karir.open.index')
                ->with('error', 'Rank team sudah terisi, hapus gagal');
        }

        DB::transaction(function () use ($game) {
            $game->delete();
            SesiMainRankTeam::where('kode_main', $game->kode_main)->delete();
            Booking::where('kode_booking', $game->kode_main)->delete();
            PembayaranBooking::where('kode_booking', $game->kode_main)->delete();
        });

        return redirect()
            ->route('admin.karir.open.index')
            ->with('success', 'Game berhasil di hapus');
    }

    public function toggle(FunGameRankTeam $game): RedirectResponse
    {
        $game->update([
            'status' => $game->isAktif() ? 0 : 1,
        ]);

        return redirect()
            ->route('admin.karir.open.index')
            ->with('success', $game->isAktif() ? 'Slot member karir dibuka' : 'Slot member karir ditutup');
    }

    public function checkBooking(Request $request): JsonResponse
    {
        $jamMulai = (string) $request->query('jam_mulai');
        $jamSelesai = (string) $request->query('jam_selesai');
        $tglMain = (string) $request->query('tgl_main');
        $lapangan = $request->query('lapangan');

        [$jamMulai, $jamSelesai] = $this->normalizeJamRange($jamMulai, $jamSelesai);

        if ($jamMulai === '' || $jamSelesai === '' || $tglMain === '' || ! $lapangan) {
            return response()->json(['status' => 'empty', 'message' => '']);
        }

        $bentrok = $this->bookingBentrok($tglMain, $jamMulai, $jamSelesai, (int) $lapangan);

        return response()->json([
            'status' => $bentrok ? 'booked' : 'available',
            'message' => $bentrok
                ? 'Mohon Maaf!! Jadwal booking yang anda pilih sudah terisi'
                : 'Jadwal booking yang anda pilih masih kosong',
        ]);
    }

    public function detail(Request $request): View
    {
        $kodeMain = (string) $request->query('kode');

        return view('admin.karir.open-detail', [
            'kodeMain' => $kodeMain,
            'game' => FunGameRankTeam::where('kode_main', $kodeMain)->first(),
            'team' => MemberKarirTeam::where('kode_main', $kodeMain)
                ->where('sesi', 1)
                ->where('status_pembayaran', 200)
                ->get(),
            'lawan' => MemberKarirTeam::where('kode_main', $kodeMain)
                ->where('sesi', 2)
                ->where('status_pembayaran', 200)
                ->get(),
        ]);
    }

    public function point(Request $request): View
    {
        $kodeMain = (string) $request->query('kode_main');
        $game = FunGameRankTeam::where('kode_main', $kodeMain)->firstOrFail();
        $teams = MemberKarirTeam::query()
            ->where('kode_main', $kodeMain)
            ->where('status_pembayaran', 200)
            ->select(['kode_team', 'team'])
            ->distinct()
            ->orderBy('team')
            ->get();
        $selectedTeamA = (string) $request->query('teamA', '');
        $selectedTeamB = (string) $request->query('teamB', '');
        $kode = (string) $request->query('kode', 'kode-'.random_int(0, 10000));
        $isMatchSelected = $request->query('slide') === '2'
            && $teams->contains('kode_team', $selectedTeamA)
            && $teams->contains('kode_team', $selectedTeamB)
            && $selectedTeamA !== $selectedTeamB;
        $teamHome = $teams->firstWhere('kode_team', $selectedTeamA);
        $teamAway = $teams->firstWhere('kode_team', $selectedTeamB);
        $playersHome = collect();
        $playersAway = collect();
        $goalsHome = collect();
        $goalsAway = collect();
        $resultSaved = false;
        $savedResult = null;

        if ($isMatchSelected) {
            $playersHome = PemainRankTeam::query()
                ->where('kode_team', $selectedTeamA)
                ->orderBy('nama')
                ->get(['id', 'nama']);
            $playersAway = PemainRankTeam::query()
                ->where('kode_team', $selectedTeamB)
                ->orderBy('nama')
                ->get(['id', 'nama']);
            $goalsHome = DB::table('tbl_datagoalrankteam')
                ->where('kode', $kode)
                ->where('kode_team', $selectedTeamA)
                ->orderBy('id')
                ->get(['id', 'goal', 'assist', 'menit']);
            $goalsAway = DB::table('tbl_datagoalrankteam')
                ->where('kode', $kode)
                ->where('kode_team', $selectedTeamB)
                ->orderBy('id')
                ->get(['id', 'goal', 'assist', 'menit']);
            $savedResult = DB::table('tbl_pertandingan_rankteam')
                ->where('kode_main', $kodeMain)
                ->where('kode', $kode)
                ->first();
            $resultSaved = $savedResult !== null;
        }

        return view('admin.karir.point-rank-team', [
            'game' => $game,
            'teams' => $teams,
            'kodeMain' => $kodeMain,
            'selectedTeamA' => $selectedTeamA,
            'selectedTeamB' => $selectedTeamB,
            'teamHome' => $teamHome,
            'teamAway' => $teamAway,
            'kode' => $kode,
            'isMatchSelected' => $isMatchSelected,
            'tglMain' => (string) $request->query('tglmain', $game->tgl_main),
            'playersHome' => $playersHome,
            'playersAway' => $playersAway,
            'goalsHome' => $goalsHome,
            'goalsAway' => $goalsAway,
            'resultSaved' => $resultSaved,
            'savedResult' => $savedResult,
        ]);
    }

    public function savePointRankTeam(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_main' => ['required', 'string', 'exists:tbl_fungame_rankteam,kode_main'],
            'kode' => ['required', 'string', 'max:30'],
            'teamA' => ['required', 'string', 'max:30'],
            'teamB' => ['required', 'string', 'max:30', 'different:teamA'],
            'tglmain' => ['required', 'date'],
            'scoreteam' => ['required', 'integer', 'min:0', 'max:99'],
            'scorelawan' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        if (! $this->areConfirmedTeams($validated['kode_main'], $validated['teamA'], $validated['teamB'])) {
            return back()->withErrors(['teamA' => 'Team tidak terdaftar pada jadwal ini.'])->withInput();
        }

        $teamHome = TeamRankTeam::query()->where('kode', $validated['teamA'])->first();
        $teamAway = TeamRankTeam::query()->where('kode', $validated['teamB'])->first();
        $activeSession = DB::table('tbl_session')->where('status', 1)->first();

        if ($teamHome === null || $teamAway === null) {
            return $this->pointRedirect($validated)->with('error', 'Data team tidak ditemukan.');
        }

        if ($activeSession === null) {
            return $this->pointRedirect($validated)->with('error', 'Tidak ada session rank team yang aktif.');
        }

        $alreadySaved = DB::table('tbl_pertandingan_rankteam')
            ->where('kode_main', $validated['kode_main'])
            ->where('kode', $validated['kode'])
            ->exists();

        if ($alreadySaved) {
            return $this->pointRedirect($validated)->with('error', 'Point untuk pertandingan ini sudah ditambahkan.');
        }

        $scoreHome = (int) $validated['scoreteam'];
        $scoreAway = (int) $validated['scorelawan'];
        $session = (int) $activeSession->session;

        DB::transaction(function () use ($validated, $teamHome, $teamAway, $scoreHome, $scoreAway, $session): void {
            $matchEvents = DB::table('tbl_datagoalrankteam')
                ->where('kode', $validated['kode'])
                ->whereIn('kode_team', [$validated['teamA'], $validated['teamB']])
                ->get(['kode_team', 'goal', 'assist', 'menit']);

            $homeWin = $scoreHome > $scoreAway ? 1 : 0;
            $homeDraw = $scoreHome === $scoreAway ? 1 : 0;
            $homeLost = $scoreHome < $scoreAway ? 1 : 0;
            $awayWin = $homeLost;
            $awayDraw = $homeDraw;
            $awayLost = $homeWin;

            DB::table('tbl_main_rankteam')->insert([
                [
                    'kode' => '',
                    'kode_main' => $validated['kode_main'],
                    'kode_team' => $validated['teamA'],
                    'team' => $teamHome->nama_team,
                    'team_lawan' => $teamAway->nama_team,
                    'score_team' => $scoreHome,
                    'score_lawan' => $scoreAway,
                    'win' => $homeWin,
                    'draw' => $homeDraw,
                    'lost' => $homeLost,
                    'link_video' => '',
                    'session' => $session,
                    'pool' => $teamHome->pool,
                ],
                [
                    'kode' => '',
                    'kode_main' => $validated['kode_main'],
                    'kode_team' => $validated['teamB'],
                    'team' => $teamAway->nama_team,
                    'team_lawan' => $teamHome->nama_team,
                    'score_team' => $scoreAway,
                    'score_lawan' => $scoreHome,
                    'win' => $awayWin,
                    'draw' => $awayDraw,
                    'lost' => $awayLost,
                    'link_video' => '',
                    'session' => $session,
                    'pool' => $teamAway->pool,
                ],
            ]);

            DB::table('tbl_pertandingan_rankteam')->insert([
                'kode' => $validated['kode'],
                'kode_main' => $validated['kode_main'],
                'team' => $teamHome->nama_team,
                'lawan' => $teamAway->nama_team,
                'score_team' => $scoreHome,
                'score_lawan' => $scoreAway,
                'session' => $session,
                'video' => '',
            ]);

            $players = PemainRankTeam::query()
                ->whereIn('kode_team', [$validated['teamA'], $validated['teamB']])
                ->get(['id', 'kode_team', 'nama', 'nik'])
                ->keyBy('id');
            $goalPlayerIds = [];
            $assistPlayerIds = [];

            foreach ($matchEvents as $event) {
                $teamIsHome = $event->kode_team === $validated['teamA'];
                $scoringTeam = $teamIsHome ? $teamHome : $teamAway;
                $opponentTeam = $teamIsHome ? $teamAway : $teamHome;
                $teamScore = $teamIsHome ? $scoreHome : $scoreAway;
                $opponentScore = $teamIsHome ? $scoreAway : $scoreHome;
                $player = $players->get((int) $event->goal);

                if ((int) $event->goal !== 0 && $player !== null) {
                    DB::table('tbl_goal_rankteam')->insert([
                        'kode' => $validated['kode'],
                        'kode_main' => $validated['kode_main'],
                        'kode_team' => $event->kode_team,
                        'id_pemain' => (string) $player->id,
                        'nik' => $player->nik ?? '',
                        'nama' => $player->nama,
                        'jml' => 1,
                        'menit' => $event->menit,
                        'team' => $scoringTeam->nama_team,
                        'lawan' => $opponentTeam->nama_team,
                        'score_team' => (string) $teamScore,
                        'score_lawan' => (string) $opponentScore,
                        'session' => $session,
                    ]);
                    $goalPlayerIds[] = (int) $player->id;
                }

                $assistPlayer = $players->get((int) $event->assist);

                if ($event->assist !== '' && $assistPlayer !== null) {
                    DB::table('tbl_assist_rankteam')->insert([
                        'kode' => $validated['kode'],
                        'kode_main' => $validated['kode_main'],
                        'kode_team' => $event->kode_team,
                        'id_pemain' => (string) $assistPlayer->id,
                        'nama' => $assistPlayer->nama,
                        'jml' => 1,
                        'menit' => (int) $event->menit,
                        'team' => $scoringTeam->nama_team,
                        'lawan' => $opponentTeam->nama_team,
                        'score_team' => $teamScore,
                        'score_lawan' => $opponentScore,
                        'session' => (string) $session,
                    ]);
                    $assistPlayerIds[] = (int) $assistPlayer->id;
                }
            }

            foreach (array_unique($goalPlayerIds) as $playerId) {
                $this->refreshPlayerGoalCount($playerId, $session);
            }

            foreach (array_unique($assistPlayerIds) as $playerId) {
                $this->refreshPlayerAssistCount($playerId, $session);
            }

            $this->refreshRankTeamPoints($validated['teamA'], $session);
            $this->refreshRankTeamPoints($validated['teamB'], $session);
        });

        return $this->pointRedirect($validated)->with('success', 'Point rank team berhasil ditambahkan.');
    }

    public function addGoal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_main' => ['required', 'string', 'exists:tbl_fungame_rankteam,kode_main'],
            'kode' => ['required', 'string', 'max:50'],
            'teamA' => ['required', 'string', 'max:30'],
            'teamB' => ['required', 'string', 'max:30', 'different:teamA'],
            'tglmain' => ['required', 'date'],
            'goalteam' => ['required', 'in:A,B'],
            'goal' => ['required', 'integer', 'min:0'],
            'assist' => ['nullable', 'integer', 'min:1'],
            'menit' => ['nullable', 'integer', 'min:0', 'max:150'],
        ]);

        if (! $this->areConfirmedTeams($validated['kode_main'], $validated['teamA'], $validated['teamB'])) {
            return back()->withErrors(['teamA' => 'Team tidak terdaftar pada jadwal ini.'])->withInput();
        }

        if ($this->matchResultExists($validated['kode_main'], $validated['kode'])) {
            return $this->pointRedirect($validated)->with('error', 'Data gol tidak dapat diubah setelah point rank team ditambahkan.');
        }

        $teamCode = $validated['goalteam'] === 'A' ? $validated['teamA'] : $validated['teamB'];
        $goal = (int) $validated['goal'];
        $assist = $validated['assist'] !== null ? (int) $validated['assist'] : null;

        if ($goal !== 0 && ! $this->isTeamPlayer($teamCode, $goal)) {
            return back()->withErrors(['goal' => 'Pemain pencetak gol tidak terdaftar pada team yang dipilih.'])->withInput();
        }

        if ($assist !== null && ! $this->isTeamPlayer($teamCode, $assist)) {
            return back()->withErrors(['assist' => 'Pemain assist tidak terdaftar pada team yang dipilih.'])->withInput();
        }

        if ($assist !== null && $goal === $assist) {
            return back()->withErrors(['assist' => 'Pemain pencetak gol dan assist tidak boleh sama.'])->withInput();
        }

        DB::table('tbl_datagoalrankteam')->insert([
            'kode' => $validated['kode'],
            'kode_team' => $teamCode,
            'goal' => (string) $goal,
            'assist' => $assist !== null ? (string) $assist : '',
            'menit' => $validated['menit'] !== null ? (string) $validated['menit'] : '',
        ]);

        return $this->pointRedirect($validated)->with('success', 'Data gol berhasil ditambahkan.');
    }

    public function updateGoal(Request $request, int $goalId): RedirectResponse
    {
        $validated = $request->validate([
            'kode_main' => ['required', 'string', 'exists:tbl_fungame_rankteam,kode_main'],
            'kode' => ['required', 'string', 'max:50'],
            'teamA' => ['required', 'string', 'max:30'],
            'teamB' => ['required', 'string', 'max:30', 'different:teamA'],
            'tglmain' => ['required', 'date'],
            'goalteam' => ['required', 'in:A,B'],
            'goal' => ['required', 'integer', 'min:0'],
            'assist' => ['nullable', 'integer', 'min:1'],
            'menit' => ['nullable', 'integer', 'min:0', 'max:150'],
        ]);

        if (! $this->areConfirmedTeams($validated['kode_main'], $validated['teamA'], $validated['teamB'])) {
            return back()->withErrors(['teamA' => 'Team tidak terdaftar pada jadwal ini.'])->withInput();
        }

        if ($this->matchResultExists($validated['kode_main'], $validated['kode'])) {
            return $this->pointRedirect($validated)->with('error', 'Data gol tidak dapat diubah setelah point rank team ditambahkan.');
        }

        $teamCode = $validated['goalteam'] === 'A' ? $validated['teamA'] : $validated['teamB'];
        $goalRecord = DB::table('tbl_datagoalrankteam')
            ->where('id', $goalId)
            ->where('kode', $validated['kode'])
            ->where('kode_team', $teamCode)
            ->first();

        if ($goalRecord === null) {
            return $this->pointRedirect($validated)->with('error', 'Data gol tidak ditemukan pada pertandingan dan team ini.');
        }

        $goal = (int) $validated['goal'];
        $assist = $validated['assist'] !== null ? (int) $validated['assist'] : null;

        if ($goal !== 0 && ! $this->isTeamPlayer($teamCode, $goal)) {
            return back()->withErrors(['goal' => 'Pemain pencetak gol tidak terdaftar pada team yang dipilih.'])->withInput();
        }

        if ($assist !== null && ! $this->isTeamPlayer($teamCode, $assist)) {
            return back()->withErrors(['assist' => 'Pemain assist tidak terdaftar pada team yang dipilih.'])->withInput();
        }

        if ($assist !== null && $goal === $assist) {
            return back()->withErrors(['assist' => 'Pemain pencetak gol dan assist tidak boleh sama.'])->withInput();
        }

        DB::table('tbl_datagoalrankteam')->where('id', $goalId)->update([
            'goal' => (string) $goal,
            'assist' => $assist !== null ? (string) $assist : '',
            'menit' => $validated['menit'] !== null ? (string) $validated['menit'] : '',
        ]);

        return $this->pointRedirect($validated)->with('success', 'Data gol berhasil diperbarui.');
    }

    public function destroyGoal(Request $request, int $goalId): RedirectResponse
    {
        $validated = $request->validate([
            'kode_main' => ['required', 'string', 'exists:tbl_fungame_rankteam,kode_main'],
            'kode' => ['required', 'string', 'max:50'],
            'teamA' => ['required', 'string', 'max:30'],
            'teamB' => ['required', 'string', 'max:30', 'different:teamA'],
            'tglmain' => ['required', 'date'],
            'goalteam' => ['required', 'in:A,B'],
        ]);

        if (! $this->areConfirmedTeams($validated['kode_main'], $validated['teamA'], $validated['teamB'])) {
            return back()->withErrors(['teamA' => 'Team tidak terdaftar pada jadwal ini.'])->withInput();
        }

        if ($this->matchResultExists($validated['kode_main'], $validated['kode'])) {
            return $this->pointRedirect($validated)->with('error', 'Data gol tidak dapat diubah setelah point rank team ditambahkan.');
        }

        $teamCode = $validated['goalteam'] === 'A' ? $validated['teamA'] : $validated['teamB'];
        $deleted = DB::table('tbl_datagoalrankteam')
            ->where('id', $goalId)
            ->where('kode', $validated['kode'])
            ->where('kode_team', $teamCode)
            ->delete();

        if ($deleted === 0) {
            return $this->pointRedirect($validated)->with('error', 'Data gol tidak ditemukan pada pertandingan dan team ini.');
        }

        return $this->pointRedirect($validated)->with('success', 'Data gol berhasil dihapus.');
    }

    /**
     * @param  array{kode_main: string, teamA: string, teamB: string, tglmain: string, kode: string}  $validated
     */
    private function pointRedirect(array $validated): RedirectResponse
    {
        return redirect()->route('admin.karir.open.point', [
            'kode_main' => $validated['kode_main'],
            'slide' => 2,
            'teamA' => $validated['teamA'],
            'teamB' => $validated['teamB'],
            'tglmain' => $validated['tglmain'],
            'kode' => $validated['kode'],
        ]);
    }

    private function areConfirmedTeams(string $kodeMain, string $teamA, string $teamB): bool
    {
        return MemberKarirTeam::query()
            ->where('kode_main', $kodeMain)
            ->where('status_pembayaran', 200)
            ->whereIn('kode_team', [$teamA, $teamB])
            ->distinct()
            ->count('kode_team') === 2;
    }

    private function matchResultExists(string $kodeMain, string $kode): bool
    {
        return DB::table('tbl_pertandingan_rankteam')
            ->where('kode_main', $kodeMain)
            ->where('kode', $kode)
            ->exists();
    }

    private function refreshRankTeamPoints(string $teamCode, int $session): void
    {
        $team = TeamRankTeam::query()->where('kode', $teamCode)->firstOrFail();
        $matches = DB::table('tbl_main_rankteam')
            ->where('kode_team', $teamCode)
            ->where('session', $session);
        $played = (clone $matches)->count();
        $wins = (clone $matches)->where('win', 1)->count();
        $draws = (clone $matches)->where('draw', 1)->count();
        $losses = (clone $matches)->where('lost', 1)->count();
        $goalsFor = (int) (clone $matches)->sum('score_team');
        $goalsAgainst = (int) (clone $matches)->sum('score_lawan');
        $winRate = $wins + $losses > 0 ? (int) round($wins / ($wins + $losses) * 100) : 0;
        $pointRow = DB::table('tbl_point_rankteam')
            ->where('kode_team', $teamCode)
            ->where('session', $session)
            ->first();
        $data = [
            'team' => $team->nama_team,
            'win' => $wins,
            'draw' => $draws,
            'lost' => $losses,
            'play' => $played,
            'winrate' => $winRate,
            'point' => ($wins * 3) + $draws,
            'kebobolan' => $goalsAgainst,
            'goal' => $goalsFor,
            'selisih_goal' => $goalsFor - $goalsAgainst,
            'pool' => $team->pool,
        ];

        if ($pointRow === null) {
            DB::table('tbl_point_rankteam')->insert($data + [
                'kode_team' => $teamCode,
                'session' => $session,
            ]);

            return;
        }

        DB::table('tbl_point_rankteam')->where('id', $pointRow->id)->update($data);
    }

    private function refreshPlayerGoalCount(int $playerId, int $session): void
    {
        $player = PemainRankTeam::query()->where('id', $playerId)->firstOrFail();
        $team = TeamRankTeam::query()->where('kode', $player->kode_team)->firstOrFail();
        $count = DB::table('tbl_goal_rankteam')
            ->where('id_pemain', (string) $playerId)
            ->where('session', $session)
            ->count();
        $row = DB::table('tbl_jml_goal_rankteam')
            ->where('id_pemain', (string) $playerId)
            ->where('session', $session)
            ->first();
        $data = [
            'nik' => $player->nik ?? '',
            'nama' => $player->nama,
            'jml' => $count,
            'pool' => $team->pool,
        ];

        if ($row === null) {
            DB::table('tbl_jml_goal_rankteam')->insert($data + [
                'id_pemain' => (string) $playerId,
                'session' => $session,
                'status' => 0,
            ]);

            return;
        }

        DB::table('tbl_jml_goal_rankteam')->where('id', $row->id)->update($data);
    }

    private function refreshPlayerAssistCount(int $playerId, int $session): void
    {
        $player = PemainRankTeam::query()->where('id', $playerId)->firstOrFail();
        $count = DB::table('tbl_assist_rankteam')
            ->where('id_pemain', (string) $playerId)
            ->where('session', (string) $session)
            ->count();
        $row = DB::table('tbl_jml_assist_rankteam')
            ->where('id_pemain', (string) $playerId)
            ->where('session', $session)
            ->first();
        $data = [
            'nama' => $player->nama,
            'jml' => $count,
        ];

        if ($row === null) {
            DB::table('tbl_jml_assist_rankteam')->insert($data + [
                'id_pemain' => (string) $playerId,
                'session' => $session,
            ]);

            return;
        }

        DB::table('tbl_jml_assist_rankteam')->where('id', $row->id)->update($data);
    }

    private function isTeamPlayer(string $kodeTeam, int $playerId): bool
    {
        return PemainRankTeam::query()
            ->where('kode_team', $kodeTeam)
            ->where('id', $playerId)
            ->exists();
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function openSesiFlags(string $openSesi): array
    {
        return match ($openSesi) {
            'Sesi 1' => [0, 1],
            'Sesi 2' => [1, 0],
            default => [0, 0],
        };
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function normalizeJamRange(string $jamMulai, string $jamSelesai): array
    {
        if ($jamMulai === '22.00' && $jamSelesai === '23.000') {
            return ['22.00', '24.00'];
        }

        if ($jamMulai === '23.00' && $jamSelesai === '24.000') {
            return ['22.00', '24.00'];
        }

        return [$jamMulai, $jamSelesai];
    }

    private function bookingBentrok(string $tglMain, string $jamMulai, string $jamSelesai, int $lapanganId): bool
    {
        return Booking::whereNotIn('status_pembayaran', ['400'])
            ->where('jam_mulai', $jamMulai)
            ->where('jam_selesai', $jamSelesai)
            ->where('tgl', $tglMain)
            ->where('id_lapangan', $lapanganId)
            ->exists();
    }

    private function nextHourLabel(string $jamMulai): string
    {
        $hour = (int) $jamMulai + 1;
        $label = str_pad((string) $hour, 2, '0', STR_PAD_LEFT);

        return $label.'.00';
    }
}
