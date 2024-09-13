<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Upload dan Hasil Rekapitulasi Excel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #e9f5ff;
            color: #333;
            font-family: 'Arial', sans-serif;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand {
            color: #ffffff;
        }
        .navbar-nav .nav-link {
            color: #ffffff;
        }
        .navbar-nav .nav-link:hover {
            color: #e0f0ff;
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
        .container.blur {
            filter: blur(5px);
        }
        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 30px;
            font-weight: bold;
            font-size: 28px;
        }
        .upload-box {
            width: 100px;
            height: 100px;
            border: 2px dashed #007bff;
            border-radius: 10px;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            margin: 0 auto;
            position: relative;
            transition: all 0.3s ease;
        }
        .upload-box i {
            font-size: 40px;
            color: #007bff;
        }
        .upload-box.uploaded {
            border-color: #28a745;
            background-color: #e2f0e4;
        }
        .upload-box.uploaded i {
            color: #28a745;
        }
        .upload-box:hover {
            background-color: #e0f0ff;
            border-color: #0056b3;
        }
        input[type="file"] {
            opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            border-radius: 30px;
            margin-top: 20px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 12px rgba(0, 91, 187, 0.4);
        }
        .alert {
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        ul {
            padding-left: 20px;
        }
        ul li {
            color: #721c24;
        }
        .file-name {
            margin-top: 10px;
            font-style: italic;
            color: #007bff;
        }
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .loading-overlay.show {
            opacity: 1;
            visibility: visible;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">Dashboard</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">Transaksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Reseller</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Supplier</a>
                </li>
            </ul>
        </div>
    </nav>

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
