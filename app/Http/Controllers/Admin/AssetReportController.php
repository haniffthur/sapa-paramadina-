<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssetReport;
use Illuminate\Http\Request;

class AssetReportController extends Controller
{
   public function index()
{
    $reports = AssetReport::with(['asset.room', 'petugas'])
                ->latest()
                ->paginate(15);

    // Sesuaikan dengan nama folder lo: reportasset
    return view('admin.reportasset.index', compact('reports'));
}

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai'
        ]);

        $report = AssetReport::findOrFail($id);
        $report->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status perbaikan aset berhasil diperbarui.');
    }
}