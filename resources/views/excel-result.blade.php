<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Rekapitulasi Excel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <link rel="stylesheet" href="{{ asset('css/result.css') }}">
</head>
<body>
<header>@include('layouts.header')</header>
<div class="container">
<h2>Hasil Rekapitulasi Excel</h2>
<body>
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
<!-- Form untuk menyimpan data ke database -->
<div class="mt-5">
<h2 class="mb-4 custom-title">Simpan Data Rekapitulasi</h2>
    <form action="{{ route('rekapitulasi.store') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Success -->
            <div class="col-md-4 mb-3">
                <label for="success" class="form-label">Success:</label>
                <input type="number" class="form-control form-control-sm" id="success" name="success" value="{{ $totalSuccess }}" required>
            </div>
            <!-- Failed -->
            <div class="col-md-4 mb-3">
                <label for="failed" class="form-label">Failed:</label>
                <input type="number" class="form-control form-control-sm" id="failed" name="failed" value="{{ $totalFailed }}" required>
            </div>
            <!-- GMV -->
            <div class="col-md-4 mb-3">
                <label for="gmv" class="form-label">GMV:</label>
                <input type="number" class="form-control form-control-sm" id="gmv" name="gmv" value="{{ $totalGMV }}" step="0.01" required>
            </div>
        </div>
        <div class="row">
            <!-- Profit -->
            <div class="col-md-4 mb-3">
                <label for="profit" class="form-label">Profit:</label>
                <input type="number" class="form-control form-control-sm" id="profit" name="profit" value="{{ $totalProfit }}" step="0.01" required>
            </div>
            <!-- BABE -->
            <div class="col-md-4 mb-3">
                <label for="babe" class="form-label">BABE:</label>
                <input type="number" class="form-control form-control-sm" id="babe" name="babe" value="{{ $totalBABE }}" step="0.01" required>
            </div>
            <!-- Net Profit -->
            <div class="col-md-4 mb-3">
                <label for="net_profit" class="form-label">Net Profit:</label>
                <input type="number" class="form-control form-control-sm" id="net_profit" name="net_profit" value="{{ $totalNetProfit }}" step="0.01" required>
            </div>
        </div>
        <div class="row">
            <!-- Tanggal -->
            <div class="col-md-6 mb-3">
                <label for="tanggal" class="form-label">Tanggal:</label>
                <input type="date" class="form-control form-control-sm" id="tanggal" name="tanggal" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Simpan ke Database</button>
    </form>
</div>

    <!-- Header Section dengan Tanggal Upload dan Tombol Copy -->
    <div class="d-flex justify-content-between align-items-center header-section">
        <div class="copy-button">
            <i class="fas fa-copy copy-icon" onclick="copyAllText()" title="Copy All"></i>
        </div>
    </div>

    <!-- Tampilan data total di bagian atas -->
    <div class="summary">
        <p><strong>Success:</strong> {{ number_format($totalSuccess, 0, ',', '.') }}</p>
        <p><strong>Failed:</strong> {{ number_format($totalFailed, 0, ',', '.') }}</p>
        <p><strong>GMV:</strong> {{ number_format($totalGMV, 0, ',', '.') }}</p>
        <p><strong>Profit:</strong> {{ number_format($totalProfit, 0, ',', '.') }}</p>
        <p><strong>BABE:</strong> {{ number_format($totalBABE, 0, ',', '.') }}</p>
        <p><strong>Net Profit:</strong> {{ number_format($totalNetProfit, 0, ',', '.') }}</p>
    </div>

    <!-- Canvas untuk Pie Chart -->
    <canvas id="resellerPieChart" width="400" height="400"></canvas>

    <!-- Bagian konten reseller -->
    <div id="content-to-copy">
        @foreach ($resellerData as $reseller => $data)
            <div class="reseller">
                <h4>{{ $reseller }}</h4>
                <ul>
                    <li>Success: {{ number_format($data['Success'], 0, ',', '.') }}</li>
                    <li>Failed: {{ number_format($data['Failed'], 0, ',', '.') }}</li>
                    <li>Trx Depo: {{ number_format($data['TrxDepo'], 0, ',', '.') }}</li>
                    <li>Total Depo: {{ number_format($data['TotalDepo'], 0, ',', '.') }}</li>
                    <li>GMV: {{ number_format($data['GMV'], 0, ',', '.') }}</li>
                    <li>Profit: {{ number_format($data['Profit'], 0, ',', '.') }}</li>

                    @if($reseller === 'Gigapulsa' || $reseller === 'H2H FIFA')
                        <li>BABE: {{ number_format($data['BABE'], 0, ',', '.') }}</li>
                    @endif
                </ul>

                <div class="products">
                    <h5>Detail Produk</h5>
                    @foreach ($data['products'] as $productCode => $product)
                        @if($productCode !== 'REFUND' && $productCode !== 'DEPOSIT')
                            <div class="product-detail">
                                <strong>{{ $productCode }}</strong> -> (Trx: {{ number_format($product['Trx'], 0, ',', '.') }} | GMV: {{ number_format($product['GMV'], 0, ',', '.') }} | Profit: {{ number_format($product['Profit'], 0, ',', '.') }})
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="separator"></div>
        @endforeach
    </div>


<script>
    // Data dari Laravel
    var resellerLabels = {!! json_encode(array_keys($resellerData)) !!};
    var resellerCounts = {!! json_encode(array_column($resellerData, 'Success')) !!};

    // Hitung total
    var total = resellerCounts.reduce((a, b) => a + b, 0);

    // Hitung persentase
    var percentages = resellerCounts.map(count => (count / total * 100).toFixed(2) + '%');

    // Inisialisasi Chart.js
    var ctx = document.getElementById('resellerPieChart').getContext('2d');
    var resellerPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: resellerLabels,
            datasets: [{
                label: 'Jumlah Transaksi Sukses',
                data: resellerCounts,
                backgroundColor: [
                    'rgba(255, 105, 180, 0.7)',  // Pink
                    'rgba(54, 162, 235, 0.7)',   // Biru
                    'rgba(153, 102, 255, 0.7)',  // Ungu
                    'rgba(124, 252, 0, 1)',      // Hijau
                    'rgba(255, 50, 50, 1)'       // Merah
                ],
                borderColor: [
                    'rgba(255, 105, 180, 1)',    // Pink
                    'rgba(54, 162, 235, 1)',     // Biru
                    'rgba(153, 102, 255, 1)',    // Ungu
                    'rgba(75, 192, 192, 1)',     // Hijau
                    'rgba(255, 99, 132, 1)'      // Merah
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            animation: {
                duration: 5000,  // Durasi animasi 5 detik
                easing: 'easeInOutCubic',  // Gaya animasi
                loop: true,  // Animasi terus berulang
                animateRotate: true,  // Rotasi animasi
                animateScale: false  // Nonaktifkan skala animasi
            },
            plugins: {
                datalabels: {
                    color: '#fff',
                    formatter: function(value, context) {
                        return percentages[context.dataIndex];
                    }
                }
            }
        }
    });

    // Saat kursor masuk, hentikan animasi dan set chart pada posisi penuh
    document.getElementById('resellerPieChart').addEventListener('mouseenter', function() {
        resellerPieChart.stop();
        resellerPieChart.options.animation.loop = false;
        resellerPieChart.update();
    });

    // Saat kursor keluar, mulai animasi kembali dari awal
    document.getElementById('resellerPieChart').addEventListener('mouseleave', function() {
        resellerPieChart.options.animation.loop = true;
        resellerPieChart.update();
        resellerPieChart.reset();
        resellerPieChart.update();
    });
</script>
</body>
</html>
