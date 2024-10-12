<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualisasi Data Chart</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <style>
        #rekapitulasiChart {
            height: 400px;
            min-width: 310px;
            margin: 0 auto;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="my-4">Data Rekapitulasi</h2>
        <div id="rekapitulasiChart"></div>
    </div>

    <script>
        // Helper function untuk generate tanggal urut dari 1 ke 30 untuk satu bulan tertentu
        function generateDates(month, year) {
            const dates = [];
            for (let day = 1; day <= 30; day++) {
                // Format DD-MM-YYYY
                const formattedDay = day.toString().padStart(2, '0');
                const formattedDate = `${formattedDay}-${month}-${year}`;
                dates.push(formattedDate);
            }
            return dates;
        }

        // Dummy data yang hanya tersedia untuk beberapa tanggal
        const data = {
            tanggal: ['08-10-2024', '09-10-2024', '12-10-2024', '05-10-2024', '01-10-2024'], // Data asli
            success: [1000, 1200, 1300, 900, 850],
            failed: [10, 15, 20, 5, 3],
            gmv: [138052811, 140000000, 150000000, 125000000, 123000000],
            profit: [50000000, 60000000, 70000000, 40000000, 38000000],
            babe: [300, 320, 340, 310, 290],
            net_profit: [4000000, 5000000, 6000000, 3500000, 3200000]
        };

        // Sort data berdasarkan tanggal
        const sortedData = data.tanggal
            .map((tanggal, index) => ({
                tanggal,
                success: data.success[index],
                failed: data.failed[index],
                gmv: data.gmv[index],
                profit: data.profit[index],
                babe: data.babe[index],
                net_profit: data.net_profit[index]
            }))
            .sort((a, b) => new Date(a.tanggal.split('-').reverse().join('-')) - new Date(b.tanggal.split('-').reverse().join('-')));

        // Extract kembali data yang sudah diurutkan
        const sortedDates = sortedData.map(item => item.tanggal);
        const successData = sortedData.map(item => item.success);
        const failedData = sortedData.map(item => item.failed);
        const gmvData = sortedData.map(item => item.gmv);
        const profitData = sortedData.map(item => item.profit);
        const babeData = sortedData.map(item => item.babe);
        const netProfitData = sortedData.map(item => item.net_profit);

        Highcharts.chart('rekapitulasiChart', {
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: 'Rekapitulasi Data'
            },
            xAxis: {
                categories: sortedDates // Tanggal yang sudah diurutkan
            },
            yAxis: [{ // Primary Y-Axis
                title: {
                    text: 'Jumlah (Nilai Kecil)',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                min: 0
            }, { // Secondary Y-Axis
                title: {
                    text: 'Jumlah (Nilai Besar)',
                    style: {
                        color: Highcharts.getOptions().colors[2]
                    }
                },
                opposite: true, // Sumbu berada di sebelah kanan
                min: 0
            }],
            series: [{
                name: 'Success',
                type: 'line',
                yAxis: 0,
                data: successData, // Data yang sudah disesuaikan dengan tanggal urut
                color: '#1E90FF'
            }, {
                name: 'Failed',
                type: 'line',
                yAxis: 0,
                data: failedData, // Data yang sudah disesuaikan dengan tanggal urut
                color: '#FF6347'
            }, {
                name: 'GMV',
                type: 'line',
                yAxis: 1, // Menggunakan sumbu Y kedua
                data: gmvData, // Data yang sudah disesuaikan dengan tanggal urut
                color: '#32CD32'
            }, {
                name: 'Profit',
                type: 'line',
                yAxis: 1, // Menggunakan sumbu Y kedua
                data: profitData, // Data yang sudah disesuaikan dengan tanggal urut
                color: '#FFD700'
            }, {
                name: 'BABE',
                type: 'line',
                yAxis: 0,
                data: babeData, // Data yang sudah disesuaikan dengan tanggal urut
                color: '#8A2BE2'
            }, {
                name: 'Net Profit',
                type: 'line',
                yAxis: 0,
                data: netProfitData, // Data yang sudah disesuaikan dengan tanggal urut
                color: '#FF4500'
            }]
        });
    </script>
</body>
</html>
