<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekapitulasi; // Pastikan model Rekapitulasi sudah ada


class RekapitulasiController extends Controller
{
    public function index()
    {
        // Mengambil semua data dari tabel rekapitulasi
        $rekapitulasi = Rekapitulasi::all();

        // Mengirim data ke view
        return view('rekapitulasi', ['rekapitulasi' => $rekapitulasi]);
    }
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'success' => 'required|integer',
            'failed' => 'required|integer',
            'gmv' => 'required|numeric',
            'profit' => 'required|numeric',
            'babe' => 'required|numeric',
            'net_profit' => 'required|numeric',
            'tanggal' => 'required|date',
        ]);

        // Buat record baru di database
        Rekapitulasi::create([
            'success' => $request->success,
            'failed' => $request->failed,
            'gmv' => $request->gmv,
            'profit' => $request->profit,
            'babe' => $request->babe,
            'net_profit' => $request->net_profit,
            'tanggal' => $request->tanggal,
        ]);

        // Redirect atau memberikan respon sukses
        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }
}

