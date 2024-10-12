<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Upload dan Hasil Rekapitulasi Excel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset(path: 'css/upload.css') }}">
</head>
<body>
    <header>@include('layouts.header')</header>

    <div class="container">
        <!-- Upload Form -->
        <h2>Upload File Excel</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Bagian input tanggal dihapus -->
            <!-- <div class="form-group">
                <label for="upload_date">Pilih Tanggal:</label>
                <input type="date" class="form-control" id="upload_date" name="upload_date" required>
            </div> -->
            <div class="form-group text-center">
                <label for="file">Pilih File Excel:</label>
                <div class="upload-box" id="upload-box">
                    <i class="fas fa-file-excel"></i> <!-- Ikon file Excel -->
                    <input type="file" name="file" accept=".xlsx,.xls" id="file" required onchange="handleFileSelect(event)">
                </div>
                <div class="file-name" id="file-name">Tidak ada file yang dipilih</div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Upload</button>
        </form>
    </div>

    <!-- Lottie Loading -->
    <div class="loading-overlay" id="loading-overlay">
        <lottie-player src="https://lottie.host/364eb203-89bf-46e4-aa62-d0e790121b59/2OkvKL6WfG.json" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></lottie-player>
    </div>

    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script>
        function handleFileSelect(event) {
            const fileInput = event.target;
            const fileName = fileInput.files.length > 0 ? fileInput.files[0].name : 'Tidak ada file yang dipilih';
            document.getElementById('file-name').textContent = fileName;

            const uploadBox = document.getElementById('upload-box');
            if (fileInput.files.length > 0) {
                uploadBox.classList.add('uploaded');
            } else {
                uploadBox.classList.remove('uploaded');
            }
        }

        document.querySelector('form').addEventListener('submit', function(event) {
            // Tampilkan overlay loading dan blur container saat tombol Upload ditekan
            document.getElementById('loading-overlay').classList.add('show');
            document.querySelector('.container').classList.add('blur');
        });
    </script>
</body>
</html>
