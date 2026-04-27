<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Report::query();

        // Filter by date if provided
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        // If user is sales, only show their reports
        if ($user->role === 'sales') {
            $query->where('user_id', $user->id);
        }

        // If admin, allow filtering by user
        if ($user->role === 'admin' && $request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $reports = $query->with([
            'user',
            'details:id,report_id,outlet',
        ])
            ->withCount('details')
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->appends($request->query());

        $salesUsers = collect();
        if ($user->role === 'admin') {
            $salesUsers = User::query()
                ->where('role', 'sales')
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        return view('reports.index', compact('reports', 'salesUsers'));
    }

    /**
     * Export reports and detail rows to an Excel-compatible file.
     */
    public function export(Request $request): StreamedResponse
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }
        $query = Report::query()->with(['user', 'details']);

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        if ($user->role === 'sales') {
            $query->where('user_id', $user->id);
        } else {
            $selectedSalesId = $request->input('export_user_id', $request->input('user_id'));
            if (!empty($selectedSalesId)) {
                $query->where('user_id', $selectedSalesId);
            }
        }

        $reports = $query
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->get();

        $fileName = 'laporan_kunjungan_' . now()->format('Ymd_His') . '.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ];

        return response()->stream(function () use ($reports) {
            echo "\xEF\xBB\xBF";
            echo '<html><head><meta charset="UTF-8"></head><body>';
            echo '<table border="1">';
            $tdDate = 'style=\'mso-number-format:"\\@"\'';
            echo '<thead><tr>';
            echo '<th>ID Laporan</th>';
            echo '<th>Tanggal Laporan</th>';
            echo '<th>Sales</th>';
            echo '<th>Dibuat Pada</th>';
            echo '<th>No. Kunjungan</th>';
            echo '<th>Outlet</th>';
            echo '<th>Alamat</th>';
            echo '<th>PIC</th>';
            echo '<th>Keterangan</th>';
            echo '<th>Waktu Ambil Foto</th>';
            echo '<th>Latitude</th>';
            echo '<th>Longitude</th>';
            echo '</tr></thead><tbody>';

            $reportNo = 1;
            foreach ($reports as $report) {
                $details = $report->details;

                if ($details->isEmpty()) {
                    echo '<tr>';
                    echo '<td>' . e((string) $report->id) . '</td>';
                    echo '<td ' . $tdDate . '>' . e($report->tanggal?->format('d/m/Y') ?? '') . '</td>';
                    echo '<td>' . e($report->user->name ?? 'Akun Sales Tidak Aktif') . '</td>';
                    echo '<td ' . $tdDate . '>' . e($report->created_at?->setTimezone('Asia/Jakarta')->format('d/m/Y H:i:s') ?? '') . '</td>';
                    echo '<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
                    echo '</tr>';
                    $reportNo++;
                    continue;
                }

                $totalKunjungan = $details->count();
                foreach ($details as $index => $detail) {
                    echo '<tr>';
                    echo '<td>' . e((string) $report->id) . '</td>';
                    echo '<td ' . $tdDate . '>' . e($report->tanggal?->format('d/m/Y') ?? '') . '</td>';
                    echo '<td>' . e($report->user->name ?? 'Akun Sales Tidak Aktif') . '</td>';
                    echo '<td ' . $tdDate . '>' . e($report->created_at?->setTimezone('Asia/Jakarta')->format('d/m/Y H:i:s') ?? '') . '</td>';
                    echo '<td>' . e((string) ($index + 1)) . '</td>';
                    echo '<td>' . e((string) $detail->outlet) . '</td>';
                    echo '<td>' . e((string) $detail->alamat) . '</td>';
                    echo '<td>' . e((string) $detail->pic) . '</td>';
                    echo '<td>' . e((string) ($detail->keterangan ?? '')) . '</td>';
                    echo '<td>' . e((string) ($detail->captured_at_label ?? '')) . '</td>';
                    echo '<td>' . e((string) ($detail->latitude ?? '')) . '</td>';
                    echo '<td>' . e((string) ($detail->longitude ?? '')) . '</td>';
                    echo '</tr>';
                }
            }

            echo '</tbody></table></body></html>';
        }, 200, $headers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'kunjungan' => 'required|array|min:1',
            'kunjungan.*.outlet' => 'required|string|max:255',
            'kunjungan.*.alamat' => 'required|string',
            'kunjungan.*.pic' => 'required|string|max:255',
            'kunjungan.*.keterangan' => 'nullable|string',
            'kunjungan.*.foto' => 'required|string',
            'kunjungan.*.captured_at_label' => 'nullable|string|max:50',
            'kunjungan.*.latitude' => 'nullable|numeric',
            'kunjungan.*.longitude' => 'nullable|numeric',
        ], [
            'kunjungan.required' => 'Minimal harus ada 1 kunjungan',
            'kunjungan.min' => 'Minimal harus ada 1 kunjungan',
            'kunjungan.*.outlet.required' => 'Nama outlet wajib diisi',
            'kunjungan.*.alamat.required' => 'Alamat wajib diisi',
            'kunjungan.*.pic.required' => 'Nama PIC wajib diisi',
            'kunjungan.*.foto.required' => 'Foto wajib diambil',
        ]);

        // Create report
        $report = Report::create([
            'user_id' => $user->id,
            'tanggal' => today(),
        ]);

        // Save details
        foreach ($request->kunjungan as $kunjungan) {
            // Save photo
            $fotoPath = null;
            if (!empty($kunjungan['foto'])) {
                // Decode base64 image
                $image = str_replace('data:image/png;base64,', '', $kunjungan['foto']);
                $image = str_replace(' ', '+', $image);
                $imageName = 'kunjungan_' . time() . '_' . uniqid() . '.png';
                Storage::disk('public')->put('kunjungan/' . $imageName, base64_decode($image));
                $fotoPath = 'kunjungan/' . $imageName;
            }

            ReportDetail::create([
                'report_id' => $report->id,
                'outlet' => $kunjungan['outlet'],
                'alamat' => $kunjungan['alamat'],
                'pic' => $kunjungan['pic'],
                'keterangan' => $kunjungan['keterangan'] ?? null,
                'foto_path' => $fotoPath,
                'captured_at_label' => $kunjungan['captured_at_label'] ?? null,
                'latitude' => $kunjungan['latitude'] ?? null,
                'longitude' => $kunjungan['longitude'] ?? null,
            ]);
        }

        return redirect()->route('reports.show', $report)
            ->with('success', 'Laporan berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        $user = Auth::user();

        // Check authorization
        if ($user->role === 'sales' && $report->user_id !== $user->id) {
            abort(403);
        }

        $report->load('details', 'user');

        return view('reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        $user = Auth::user();

        // Admin can delete all reports, sales can only delete their own reports.
        if ($user->role === 'sales' && $report->user_id !== $user->id) {
            abort(403);
        }

        $report->load('details');

        foreach ($report->details as $detail) {
            if (!empty($detail->foto_path)) {
                Storage::disk('public')->delete($detail->foto_path);
            }
        }

        $report->delete();

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    /**
     * Add a new detail to an existing report.
     */
    public function addDetail(Request $request, Report $report)
    {
        $user = Auth::user();

        // Check authorization
        if ($user->role === 'sales' && $report->user_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'outlet' => 'required|string|max:255',
            'alamat' => 'required|string',
            'pic' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'foto' => 'required|string',
            'captured_at_label' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // Save photo
        $fotoPath = null;
        if (!empty($request->foto)) {
            $image = str_replace('data:image/png;base64,', '', $request->foto);
            $image = str_replace(' ', '+', $image);
            $imageName = 'kunjungan_' . time() . '_' . uniqid() . '.png';
            Storage::disk('public')->put('kunjungan/' . $imageName, base64_decode($image));
            $fotoPath = 'kunjungan/' . $imageName;
        }

        $detail = ReportDetail::create([
            'report_id' => $report->id,
            'outlet' => $request->outlet,
            'alamat' => $request->alamat,
            'pic' => $request->pic,
            'keterangan' => $request->keterangan,
            'foto_path' => $fotoPath,
            'captured_at_label' => $request->captured_at_label,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kunjungan berhasil ditambahkan',
            'detail' => $detail,
        ]);
    }
}
