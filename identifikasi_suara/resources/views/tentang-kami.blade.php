<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Suaraku</title>
    <!-- Use the same CSS as auth/home if available, otherwise CDN for Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif; /* Assuming font, can fallback to sans-serif */
            color: #333;
        }
        .text-primary-custom {
            color: #1e3a8a; /* Dark blue specific to the design */
        }
        .bg-dark-blue {
            background-color: #1e3a8a;
            color: white;
        }
        .bg-light-blue {
            background-color: #8dacdb; /* Lighter blue from image */
            color: white;
        }
        .bg-vision {
            background-color: #e0f2fe; /* Very light blue for Vision section */
            color: #1e3a8a;
        }
        .feature-box {
            padding: 20px;
            border-radius: 4px;
            height: 100%;
        }
    </style>
</head>
<body>

    <div class="container py-5">
        <!-- Header -->
        <div class="row mb-3">
            <div class="col-12 text-center">
                <h1 class="fw-bold text-primary-custom mb-4">Tentang Kami - Suaraku</h1>
            </div>
            
            <div class="col-md-2 fw-bold text-dark">
                Tentang<br>suaraku.
            </div>
            <div class="col-md-10 text-secondary">
                <p>
                    SUARAKU adalah sebuah platform analisis suara berbasis kecerdasan buatan yang dirancang untuk mengenali emosi dan suku melalui karakteristik suara manusia. Website ini terinspirasi dari inovasi museum Indonesia yang menggunakan face recognition untuk mengenali asal suku. Dengan semangat yang sama, SUARAKU mencoba menghadirkan teknologi serupa namun melalui voice recognition, sehingga memberikan pengalaman baru dalam memahami ekspresi dan identitas suara.
                </p>
            </div>
        </div>

        <!-- Apa yang kami lakukan -->
        <div class="row mb-1">
            <div class="col-12 text-center mb-1">
                <h3 class="fw-bold text-primary-custom">Apa yang kami lakukan</h3>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <!-- Box 1 -->
            <div class="col-md-6">
                <div class="feature-box bg-dark-blue p-4">
                    <div class="d-flex align-items-start">
                        <i class="fa-regular fa-face-laugh-beam fs-2 me-3"></i>
                        <div>
                            <h5 class="fw-bold">Deteksi Emosi Suara</h5>
                            <p class="mb-0 small">
                                Sistem mengklasifikasi suara ke dalam 5 kategori emosi (Bahagia, Terkejut, Netral, Marah, Sedih)
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Box 2 -->
            <div class="col-md-6">
                <div class="feature-box bg-light-blue p-4">
                    <div class="d-flex align-items-start">
                        <i class="fa-regular fa-face-laugh-beam fs-2 me-3"></i>
                        <div>
                            <h5 class="fw-bold">Klasifikasi Suku Suara</h5>
                            <p class="mb-0 small">
                                Suaraku juga memberikan estimasi asal suku melalui 5 kategori (Jawa, Sunda, Minang, Batak, Betawi)
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fitur Utama -->
        <div class="row mb-1">
            <div class="col-12">
                <h3 class="fw-bold text-primary-custom mb-1">Fitur Utama</h3>
                <p class="text-secondary mb-1">SUARAKU menyediakan dua metode untuk melakukan analisis suara:</p>
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-md-6">
                <h6 class="fw-bold mb-2">1. Rekaman Audio Langsung</h6>
                <p class="text-secondary small mb-2">
                    Pengguna dapat merekam suara secara langsung melalui website.
                    Durasi rekaman dapat diatur oleh admin dengan dua opsi:
                </p>
                <ul class="text-secondary small">
                    <li>Minimal 3-5 menit</li>
                    <li>Minimal 9-10 menit</li>
                </ul>
                <p class="text-secondary small">
                    Rekaman kemudian langsung diproses dan dianalisis menggunakan model AI kami.
                </p>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold mb-2">2. Upload File Audio</h6>
                <p class="text-secondary small">
                    Pengguna dapat mengunggah file audio dalam format .wav.<br>
                    Format selain .wav tidak dapat diproses demi menjaga konsistensi kualitas analisis suara.
                </p>
            </div>
        </div>
    </div>

    <!-- Visi Kami (Full Width Background) -->
    <div class="bg-vision py-5">
        <div class="container text-center">
            <h3 class="fw-bold text-primary-custom mb-4">Visi Kami</h3>
            <p class="text-primary-custom px-md-5">
                Menjadi platform analisis suara berbasis AI yang dapat membantu masyarakat memahami ekspresi dan karakteristik suara secara lebih mendalam, sekaligus menjadi sarana edukasi mengenai keberagaman budaya di Indonesia.
            </p>
        </div>
    </div>

</body>
</html>
