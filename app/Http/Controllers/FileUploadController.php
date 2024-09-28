<?php  

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class FileUploadController extends BaseController
{
    // Menampilkan form unggah file
    public function showUploadForm()
    {
        return view('upload_file');
    }
    
    public function showChart() {
        // Ambil data dari database atau sumber lain
        $resellerData = DB::table('your_table')
            ->select('reseller_name', DB::raw('count(*) as success_count'))
            ->whereIn('rc', ['00', '0']) // Hanya transaksi sukses dengan rc '00' atau '0'
            ->groupBy('reseller_name')
            ->pluck('success_count', 'reseller_name');
    
        return view('your_view_name', compact('resellerData'));
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
        $totalDepo = 0; // Total deposit global
        $totalTrxDepo = 0; // Jumlah transaksi deposit global

        // Loop melalui data yang diunggah, mulai dari baris ke-2 (indeks 1) untuk melewati header
        foreach ($data as $index => $row) {
            if ($index == 0) continue; // Lewati header

            // Ambil nilai dari kolom yang relevan
            $reseller = isset($row[1]) ? $row[1] : ''; // Kolom ke-2 (indeks 1)
            $supplier = isset($row[2]) ? $row[2] : ''; // Kolom ke-3 (indeks 2)
            $price = isset($row[5]) ? (float)$row[5] : 0; // Kolom ke-6 (indeks 5)
            $gmv = isset($row[7]) ? (float)$row[7] : 0; // Kolom ke-8 (indeks 7)
            $profit = isset($row[6]) ? (float)$row[6] : 0; // Kolom ke-7 (indeks 6)
            $productCode = isset($row[12]) ? strtoupper($row[12]) : ''; // Kolom ke-13 (indeks 12)
            $rc = isset($row[8]) ? $row[8] : ''; // Kolom ke-9 (indeks 8)

            // Hitung Success dan Failed secara total berdasarkan RC
            if (in_array($rc, ['00', '0'])) {
                $totalSuccess++;
            } else {
                $totalFailed++;
            }

            // Akumulasi total GMV dan Profit
            $totalGMV += $price;
            $totalProfit += $profit;

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
            if (in_array($rc, ['00', '0'])) {
                $resellerData[$reseller]['Success']++;
            } else {
                $resellerData[$reseller]['Failed']++;
            }

            // Akumulasi nilai GMV dan Profit
            $resellerData[$reseller]['GMV'] += $price;
            $resellerData[$reseller]['Profit'] += $profit;

            // Ketentuan perhitungan BABE
            $isResellerValid = ($reseller === 'Gigapulsa' || $reseller === 'H2H FIFA');
            $isSupplierValid = ($supplier === 'GGP' || $supplier === 'FFP');

            // Hitung profit berdasarkan supplier
            if ($isSupplierValid && $productCode != 'REFUND' && $productCode != 'DEPOSIT') {
                if (str_contains($productCode, 'PLN')) {
                    $totalProfit += 5; // profit untuk produk PLN
                } elseif ($price <= 20000) {
                    $totalProfit += 10; // profit untuk produk selain PLN dengan harga <= 20.000
                } else {
                    $totalProfit += 30; // profit untuk produk selain PLN dengan harga > 20.000
                }
            }

            // Hanya tambahkan BABE jika reseller adalah Gigapulsa atau H2H FIFA
            if ($isResellerValid && $productCode != 'REFUND' && $productCode != 'DEPOSIT') {
                $fee = 0;
                if (str_contains($productCode, 'PLN')) {
                    $fee = 5; // Fee untuk produk PLN
                } elseif ($price <= 20000) {
                    $fee = 10; // Fee untuk produk selain PLN dengan harga <= 20.000
                } else {
                    $fee = 30; // Fee untuk produk selain PLN dengan harga > 20.000
                }
                $totalBABE += $fee; // Akumulasi total BABE
                $resellerData[$reseller]['BABE'] += $fee; // Tambahkan BABE untuk reseller
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
            $resellerData[$reseller]['products'][$productCode]['GMV'] += $price;
            $resellerData[$reseller]['products'][$productCode]['Profit'] += $profit;
        }

        // Hitung total Net Profit
        $totalNetProfit = $totalProfit - $totalBABE;

        // Kirim data total ke view, termasuk total depo dan transaksi depo global
        return view('excel-result', compact('resellerData', 'totalSuccess', 'totalFailed', 'totalGMV', 'totalProfit', 'totalBABE', 'totalNetProfit', 'totalDepo', 'totalTrxDepo'));
    }
}
