<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- CDN untuk Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>
    <header>@include('layouts.header')</header> <!-- Menggunakan header yang sama dari layouts -->

    <div class="container">
        <h2 class="my-4">Data Rekapitulasi</h2> <!-- Judul halaman -->

        <!-- Grafik -->
        <div id="rekapChart" style="height: 400px; width: 100%;"></div>

        <!-- Tabel -->
        <table class="table table-bordered mt-5"> <!-- mt-5 menambah margin atas -->
            <thead class="thead-dark">
                <tr>
                    <th>Success</th>
                    <th>Failed</th>
                    <th>GMV</th>
                    <th>Profit</th>
                    <th>BABE</th>
                    <th>Net Profit</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekapitulasi as $data)
                <tr>
                    <td>{{ number_format($data->success, 0, ',', '.') }}</td> <!-- Menampilkan angka dengan format ribuan -->
                    <td>{{ number_format($data->failed, 0, ',', '.') }}</td> <!-- Menampilkan angka dengan format ribuan -->
                    <td>{{ number_format($data->gmv, 0, ',', '.') }}</td> <!-- Menampilkan GMV dengan format ribuan -->
                    <td>{{ number_format($data->profit, 0, ',', '.') }}</td> <!-- Menampilkan Profit dengan format ribuan -->
                    <td>{{ number_format($data->babe, 0, ',', '.') }}</td> <!-- Menampilkan BABE dengan format ribuan -->
                    <td>{{ number_format($data->net_profit, 0, ',', '.') }}</td> <!-- Menampilkan Net Profit dengan format ribuan -->
                    <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Script untuk Highcharts -->
    <script>
        Highcharts.chart('rekapChart', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Rekapitulasi Data'
            },
            xAxis: {
                categories: [@foreach($rekapitulasi as $data) '{{ \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y') }}', @endforeach]
            },
            yAxis: [{
                title: {
                    text: 'GMV'
                },
                labels: {
                    formatter: function() {
                        return this.value; 
                    }
                }
            }, {
                title: {
                    text: 'Failed, Profit, BABE, Net Profit'
                },
                opposite: true
            }],
            series: [{
                name: 'Success',
                data: [@foreach($rekapitulasi as $data) {{ $data->success }}, @endforeach],
                color: 'rgba(75, 192, 192, 1)'
            }, {
                name: 'Failed',
                data: [@foreach($rekapitulasi as $data) {{ $data->failed }}, @endforeach],
                yAxis: 1, // Menggunakan sumbu Y yang berbeda
                color: 'rgba(255, 99, 132, 1)'
            }, {
                name: 'GMV',
                data: [@foreach($rekapitulasi as $data) {{ $data->gmv }}, @endforeach], // Nilai GMV asli
                color: 'rgba(54, 162, 235, 1)'
            }, {
                name: 'Profit',
                data: [@foreach($rekapitulasi as $data) {{ $data->profit }}, @endforeach], // Nilai Profit asli
                yAxis: 1,
                color: 'rgba(153, 102, 255, 1)'
            }, {
                name: 'BABE',
                data: [@foreach($rekapitulasi as $data) {{ $data->babe }}, @endforeach], // Nilai BABE asli
                yAxis: 1,
                color: 'rgba(255, 206, 86, 1)'
            }, {
                name: 'Net Profit',
                data: [@foreach($rekapitulasi as $data) {{ $data->net_profit }}, @endforeach], // Nilai Net Profit asli
                yAxis: 1,
                color: 'rgba(255, 159, 64, 1)'
            }]
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
