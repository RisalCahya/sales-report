<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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

        $reports = $query->with('user')
            ->withCount('details')
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->appends($request->query());

        return view('reports.index', compact('reports'));
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

        $report->load('user')->loadCount('details');
        $details = $report->details()
            ->orderByDesc('created_at')
            ->paginate(8);

        return view('reports.show', compact('report', 'details'));
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
