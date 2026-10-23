<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LapanganController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => ['sometimes', 'integer', 'min:1'],
            'slug' => ['sometimes', 'string', 'max:50'],
            'q' => ['sometimes', 'string', 'max:100'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        if (isset($validated['id']) || isset($validated['slug'])) {
            $query = Lapangan::query();

            if (isset($validated['id'])) {
                $query->whereKey($validated['id']);
            } else {
                $query->where('slug', $validated['slug']);
            }

            return response()->json(['data' => $query->firstOrFail()]);
        }

        $fields = Lapangan::query()
            ->select(['id', 'lapangan', 'slug', 'pasilitas', 'harga_perjam', 'status_booking', 'gambar', 'gambar1', 'gambar2'])
            ->when(isset($validated['q']), function ($query) use ($validated): void {
                $query->where(function ($lapanganQuery) use ($validated): void {
                    $lapanganQuery->where('lapangan', 'like', '%'.$validated['q'].'%')
                        ->orWhere('slug', 'like', '%'.$validated['q'].'%');
                });
            })
            ->orderBy('id')
            ->paginate($validated['per_page'] ?? 20);

        return response()->json([
            'data' => $fields->items(),
            'meta' => [
                'current_page' => $fields->currentPage(),
                'last_page' => $fields->lastPage(),
                'per_page' => $fields->perPage(),
                'total' => $fields->total(),
            ],
        ]);
    }

    public function show(int $lapangan): JsonResponse
    {
        $field = Lapangan::query()
            ->select(['id', 'lapangan', 'slug', 'pasilitas', 'harga_perjam', 'status_booking', 'gambar', 'gambar1', 'gambar2'])
            ->findOrFail($lapangan);

        return response()->json(['data' => $field]);
    }

    public function fromBookings(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['sometimes', 'string', 'max:100'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        $fields = $this->bookingLapanganQuery($validated['q'] ?? '')
            ->paginate($validated['per_page'] ?? 20);

        return response()->json([
            'data' => $fields->items(),
            'meta' => [
                'current_page' => $fields->currentPage(),
                'last_page' => $fields->lastPage(),
                'per_page' => $fields->perPage(),
                'total' => $fields->total(),
            ],
        ]);
    }

    private function bookingLapanganQuery(string $search = ''): Builder
    {
        return DB::table('tbl_booking')
            ->select('id_lapangan', 'lapangan')
            ->selectRaw('COUNT(*) as jumlah_booking')
            ->whereNotNull('lapangan')
            ->where('lapangan', '<>', '')
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $lapanganQuery) use ($search): void {
                    $lapanganQuery->where('lapangan', 'like', '%'.$search.'%')
                        ->orWhere('id_lapangan', 'like', '%'.$search.'%');
                });
            })
            ->groupBy('id_lapangan', 'lapangan')
            ->orderBy('lapangan');
    }
}
