<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Maatwebsite\Excel\Facades\Excel;

class FileUploadController extends BaseController
{
    // Menampilkan form unggah file
    public function showUploadForm()
    {
        return view('upload_file');
    }

    // Proses unggah dan parsing file
    public function upload(Request $request)
    {
        // Validasi file yang diunggah
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Membaca file Excel dan proses data
        $file = $request->file('file');
        $data = Excel::toArray([], $file)[0]; // Mengambil sheet pertama dari Excel

        // Variabel untuk menyimpan hasil rekapitulasi
        $resellerData = [];

        // Variabel untuk menyimpan total data
        $totalSuccess = 0;
        $totalFailed = 0;
        $totalGMV = 0;
        $totalProfit = 0;
        $totalBABE = 0;
        $totalNetProfit = 0;

        // Loop melalui data yang diunggah, mulai dari baris ke-2 (indeks 1) untuk melewati header
        foreach ($data as $index => $row) {
            if ($index == 0) continue; // Lewati header

            // Ambil nilai dari kolom yang relevan
            $reseller = isset($row[1]) ? $row[1] : ''; // Kolom ke-2 (indeks 1)
            $rcValue = isset($row[8]) ? $row[8] : ''; // Kolom ke-9 (indeks 8)
            $gmv = isset($row[7]) ? (float)$row[7] : 0; // Kolom ke-8 (indeks 7)
            $profit = isset($row[6]) ? (float)$row[6] : 0; // Kolom ke-7 (indeks 6)
            $productCode = isset($row[12]) ? $row[12] : ''; // Kolom ke-13 (indeks 12)

            // Hitung Success dan Failed secara total
            if ($rcValue == '00') {
                $totalSuccess++;
            } else {
                $totalFailed++;
            }

            // Akumulasi total GMV dan Profit
            $totalGMV += $gmv;
            $totalProfit += $profit;

            // Hitung total BABE khusus untuk reseller 'Gigapulsa'
            if ($reseller === 'Gigapulsa' && $rcValue == '00' && $productCode != 'REFUND' && $productCode != 'DEPOSIT') {
                $totalBABE += $profit;
            }

            // Inisialisasi data reseller jika belum ada
            if (!isset($resellerData[$reseller])) {
                $resellerData[$reseller] = [
                    'Success' => 0,
                    'Failed' => 0,
                    'GMV' => 0,
                    'Profit' => 0,
                    'BABE' => 0,
                    'NetProfit' => 0,
                    'TotalDepo' => 0,
                    'TrxDepo' => 0,
                    'products' => []
                ];
            }

            // Hitung nilai Success dan Failed berdasarkan nilai kolom RC
            if ($rcValue == '00') {
                $resellerData[$reseller]['Success']++;
            } else {
                $resellerData[$reseller]['Failed']++;
            }

            // Akumulasi nilai GMV dan Profit
            $resellerData[$reseller]['GMV'] += $gmv;
            $resellerData[$reseller]['Profit'] += $profit;

            // Cek dan hitung BABE khusus untuk reseller 'Gigapulsa' dengan ketentuan yang diberikan
            if ($reseller === 'Gigapulsa' && $rcValue == '00' && $productCode != 'REFUND' && $productCode != 'DEPOSIT') {
                $resellerData[$reseller]['BABE'] += $profit;
            }

            // Hitung transaksi deposit jika produk adalah 'DEPOSIT'
            if ($productCode == 'DEPOSIT') {
                $resellerData[$reseller]['TrxDepo']++;
                $resellerData[$reseller]['TotalDepo'] += $gmv;
            }

            // Inisialisasi data produk jika belum ada
            if (!isset($resellerData[$reseller]['products'][$productCode])) {
                $resellerData[$reseller]['products'][$productCode] = [
                    'Trx' => 0,
                    'GMV' => 0,
                    'Profit' => 0,
                ];
            }

            // Akumulasi data produk
            $resellerData[$reseller]['products'][$productCode]['Trx']++;
            $resellerData[$reseller]['products'][$productCode]['GMV'] += $gmv;
            $resellerData[$reseller]['products'][$productCode]['Profit'] += $profit;
        }

        // Hitung Net Profit untuk setiap reseller
        foreach ($resellerData as $reseller => $data) {
            $resellerData[$reseller]['NetProfit'] = $data['Profit'] - $data['BABE'];
        }

        // Hitung total Net Profit
        $totalNetProfit = $totalProfit - $totalBABE;

        // Kirim data total ke view
        return view('excel-result', compact('resellerData', 'totalSuccess', 'totalFailed', 'totalGMV', 'totalProfit', 'totalBABE', 'totalNetProfit'));
    }
}
