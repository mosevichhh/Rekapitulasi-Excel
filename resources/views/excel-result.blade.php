<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Rekapitulasi Excel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <style>
        body {
            background-color: #e9f5ff;
            color: #333;
            font-family: 'Arial', sans-serif;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 800px;
            margin: 50px auto;
            position: relative;
            transition: all 0.3s ease-in-out;
        }
        .container:hover {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
        }
        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 30px;
            font-weight: bold;
            font-size: 28px;
        }
        .summary {
            margin-bottom: 30px;
        }
        .summary p {
            margin: 10px 0;
            font-size: 16px;
        }
        .reseller {
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 10px;
            background-color: #f1faff;
        }
        .reseller h4 {
            color: #007bff;
            font-weight: bold;
        }
        .reseller ul {
            list-style: none;
            padding-left: 0;
        }
        .reseller ul li {
            padding: 10px 0;
            border-bottom: 1px solid #e0f0ff;
            color: #333;
            font-size: 16px;
        }
        .reseller ul li:last-child {
            border-bottom: none;
        }
        .products {
            margin-top: 20px;
        }
        .product-detail {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #e9f5ff;
            border-radius: 5px;
        }
        .copy-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            color: #007bff;
            font-size: 18px;
        }
        .copy-icon:hover {
            color: #0056b3;
        }
        .reseller + .reseller {
            margin-top: 50px;
        }
        .separator {
            margin: 20px 0;
            border-top: 1px dashed #007bff;
        }
        canvas {
            margin: 0 auto;
            display: block;
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Hasil Rekapitulasi Excel</h2>

    <!-- Tombol Salin di luar elemen reseller -->
    <i class="fas fa-copy copy-icon" onclick="copyAllText()" title="Copy All"></i>

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
                    <li>BABE: {{ number_format($data['BABE'], 0, ',', '.') }}</li>
                    <li>Net Profit: {{ number_format($data['NetProfit'], 0, ',', '.') }}</li>
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
</div>

<script>
    function copyAllText() {
        var content = document.getElementById('content-to-copy');
        var range = document.createRange();
        range.selectNode(content);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
        document.execCommand('copy');
        window.getSelection().removeAllRanges();
        alert('Content copied to clipboard!');
    }

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
                    'rgba(124, 252, 0, 1)',    // Hijau
                    'rgba(255, 50, 50, 1)'    // Merah
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
