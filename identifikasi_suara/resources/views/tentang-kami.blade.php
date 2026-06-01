<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Suaraku</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-blue: #0c56d0;
            --primary-blue-hover: #0a4aba;
            --bg-gray: #f3f6fc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-gray);
            color: var(--text-dark);
            margin: 0;
            padding: 0;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }

        /* Hero Banner */
        .hero-banner {
            background-color: var(--primary-blue);
            color: white;
            padding: 50px 20px;
            text-align: center;
        }

        .hero-badge {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 6px 18px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
        }

        .hero-title {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }

        .hero-subtitle {
            font-size: 15px;
            max-width: 700px;
            margin: 0 auto;
            opacity: 0.9;
            line-height: 1.6;
            font-weight: 400;
        }

        /* Container Layout */
        .main-container {
            max-width: 820px;
            margin: -25px auto 60px auto;
            padding: 0 15px;
        }

        /* Info Card */
        .info-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0px 4px 20px rgba(12, 86, 208, 0.03);
            border: 1px solid #eef2f8;
            margin-bottom: 24px;
        }

        .card-header-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--primary-blue);
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            text-transform: uppercase;
        }

        .card-header-title i {
            font-size: 16px;
        }

        /* Apa itu Suaraku content */
        .desc-text {
            color: #334155;
            font-size: 14.5px;
            line-height: 1.7;
            margin: 0;
        }

        /* Fitur Utama Grid */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .feature-item-card {
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            padding: 24px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .feature-item-card:hover {
            transform: translateY(-2px);
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.02);
        }

        .feature-icon-wrapper {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background-color: rgba(12, 86, 208, 0.06);
            color: var(--primary-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 16px;
        }

        .feature-item-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .feature-item-desc {
            font-size: 13px;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.5;
        }

        /* Steps */
        .step-list {
            display: flex;
            flex-column: column;
            gap: 24px;
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--primary-blue);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
        }

        .step-content {
            flex-grow: 1;
        }

        .step-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .step-desc {
            font-size: 13.5px;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.6;
        }

        /* Guidelines/Rules List */
        .rules-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .rules-list li {
            position: relative;
            padding-left: 20px;
            margin-bottom: 12px;
            font-size: 13.5px;
            color: #334155;
            line-height: 1.6;
        }

        .rules-list li::before {
            content: "•";
            position: absolute;
            left: 0;
            top: 0;
            color: var(--primary-blue);
            font-size: 18px;
            line-height: 1;
        }

        .rules-list li:last-child {
            margin-bottom: 0;
        }

        /* Warning Alert Banner */
        .warning-alert {
            background-color: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 24px;
        }

        .warning-icon {
            color: #d97706;
            font-size: 18px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .warning-text {
            font-size: 13px;
            color: #b45309;
            line-height: 1.6;
            margin: 0;
        }

        /* FAQ Accordion overrides */
        .accordion-item {
            border: none;
            border-bottom: 1px solid #f1f5f9;
            background: transparent;
        }

        .accordion-item:last-child {
            border-bottom: none;
        }

        .accordion-button {
            font-family: 'Outfit', sans-serif;
            font-size: 15px;
            font-weight: 600;
            color: #334155;
            background-color: transparent;
            padding: 18px 0;
            box-shadow: none !important;
            border: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .accordion-button:not(.collapsed) {
            color: var(--primary-blue);
            background-color: transparent;
        }

        /* Symmetrical Chevron control */
        .accordion-button::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%2364748b'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            background-size: 14px;
            transition: transform 0.2s ease-in-out;
            width: 14px;
            height: 14px;
        }

        .accordion-button:not(.collapsed)::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230c56d0'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            transform: rotate(-180deg);
        }

        .accordion-body {
            padding: 0 0 18px 0;
            color: var(--text-muted);
            font-size: 13.5px;
            line-height: 1.6;
        }

        /* Footer */
        .footer-text {
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            margin-top: 40px;
            padding-bottom: 20px;
        }

        /* Responsive Breakpoints */
        @media (max-width: 768px) {
            .hero-banner {
                padding: 40px 15px;
            }

            .hero-title {
                font-size: 26px;
            }

            .hero-subtitle {
                font-size: 13.5px;
            }

            .info-card {
                padding: 20px;
            }

            .feature-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
    </style>
</head>
<body>

    <!-- Hero Banner -->
    <div class="hero-banner">
        <span class="hero-badge">Tentang Kami</span>
        <h1 class="hero-title">Mengenal Emosi & Suku<br>dari Suara Anda</h1>
        <p class="hero-subtitle">
            SUARAKU adalah aplikasi berbasis AI yang mampu mendeteksi emosi dan identitas suku melalui analisis suara secara real-time.
        </p>
    </div>

    <!-- Main Container -->
    <div class="main-container">

        <!-- APA ITU SUARAKU -->
        <div class="info-card">
            <div class="card-header-title">
                <i class="fa-regular fa-circle-question"></i> APA ITU SUARAKU?
            </div>
            <p class="desc-text">
                Suaraku memanfaatkan teknologi kecerdasan buatan (AI) untuk menganalisis pola suara manusia. Dengan cukup berbicara selama beberapa detik, sistem kami dapat mengenali emosi yang sedang Anda rasakan serta memperkirakan latar belakang suku dari intonasi dan karakteristik suara Anda.
            </p>
        </div>

        <!-- FITUR UTAMA -->
        <div class="info-card">
            <div class="card-header-title">
                <i class="fa-solid fa-wand-magic-sparkles"></i> FITUR UTAMA
            </div>
            <div class="feature-grid">
                <!-- Card 1 -->
                <div class="feature-item-card">
                    <div class="feature-icon-wrapper">
                        <i class="fa-regular fa-face-smile"></i>
                    </div>
                    <h4 class="feature-item-title">Deteksi Emosi</h4>
                    <p class="feature-item-desc">Kenali 5 emosi dari pola suara Anda</p>
                </div>
                <!-- Card 2 -->
                <div class="feature-item-card">
                    <div class="feature-icon-wrapper">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h4 class="feature-item-title">Identifikasi Suku</h4>
                    <p class="feature-item-desc">Prediksi suku dan karakteristik suara</p>
                </div>
                <!-- Card 3 -->
                <div class="feature-item-card">
                    <div class="feature-icon-wrapper">
                        <i class="fa-solid fa-chart-simple"></i>
                    </div>
                    <h4 class="feature-item-title">Skor Detail</h4>
                    <p class="feature-item-desc">Lihat persentase tiap emosi & suku</p>
                </div>
                <!-- Card 4 -->
                <div class="feature-item-card">
                    <div class="feature-icon-wrapper">
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                    <h4 class="feature-item-title">Kirim Email</h4>
                    <p class="feature-item-desc">Hasil analisis dikirim otomatis ke email</p>
                </div>
            </div>
        </div>

        <!-- CARA MENGGUNAKAN -->
        <div class="info-card">
            <div class="card-header-title">
                <i class="fa-solid fa-list-ol"></i> CARA MENGGUNAKAN
            </div>
            <div class="step-list d-flex flex-column gap-4">
                <!-- Step 1 -->
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h5 class="step-title">Buka halaman utama</h5>
                        <p class="step-desc">Pastikan perangkat Anda memiliki mikrofon yang berfungsi dan izin mikrofon telah diberikan.</p>
                    </div>
                </div>
                <!-- Step 2 -->
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h5 class="step-title">Klik tombol mikrofon</h5>
                        <p class="step-desc">Tekan ikon mikrofon untuk memulai perekaman. Indikator merah menandakan sesi rekam aktif.</p>
                    </div>
                </div>
                <!-- Step 3 -->
                <div class="step-item">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h5 class="step-title">Ucapkan kalimat dengan jelas</h5>
                        <p class="step-desc">Posisikan mulut ± 10 cm dari mikrofon. Ucapkan kalimat natural seperti biasa, minimal 5 detik.</p>
                    </div>
                </div>
                <!-- Step 4 -->
                <div class="step-item">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h5 class="step-title">Hentikan perekaman</h5>
                        <p class="step-desc">Klik kembali tombol mikrofon untuk menghentikan rekaman. AI akan mulai memproses suara Anda.</p>
                    </div>
                </div>
                <!-- Step 5 -->
                <div class="step-item">
                    <div class="step-number">5</div>
                    <div class="step-content">
                        <h5 class="step-title">Lihat hasil deteksi</h5>
                        <p class="step-desc">Hasil emosi dan suku dominan akan tampil beserta skor persentase. Hasil juga dikirim ke email terdaftar.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- KETENTUAN PENGGUNAAN -->
        <div class="info-card">
            <div class="card-header-title">
                <i class="fa-solid fa-shield-halved"></i> KETENTUAN PENGGUNAAN
            </div>
            <ul class="rules-list">
                <li>Aplikasi hanya digunakan untuk keperluan analisis suara yang sah dan tidak melanggar hukum.</li>
                <li>Data suara yang direkam tidak disimpan secara permanen dan hanya diproses untuk analisis satu sesi.</li>
                <li>Hasil identifikasi suku bersifat prediksi berbasis pola suara, bukan penentu identitas resmi.</li>
                <li>Pengguna bertanggung jawab atas keakuratan email yang didaftarkan untuk pengiriman hasil.</li>
                <li>Dilarang merekam suara orang lain tanpa seizin yang bersangkutan.</li>
                <li>Penggunaan berulang dalam waktu singkat dapat mempengaruhi akurasi hasil analisis.</li>
            </ul>
        </div>

        <!-- WARNING BANNER -->
        <div class="warning-alert">
            <i class="fa-solid fa-triangle-exclamation warning-icon"></i>
            <p class="warning-text">
                Akurasi hasil analisis dipengaruhi oleh kualitas mikrofon, tingkat kebisingan lingkungan, dan kejelasan ucapan. Gunakan di tempat yang tenang untuk hasil terbaik.
            </p>
        </div>

        <!-- PERTANYAAN UMUM -->
        <div class="info-card">
            <div class="card-header-title">
                <i class="fa-regular fa-circle-question"></i> PERTANYAAN UMUM
            </div>
            <div class="accordion" id="faqAccordion">
                <!-- FAQ 1 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            Apakah data suara saya aman?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Ya, data suara Anda aman. Rekaman suara hanya diproses secara real-time selama sesi berlangsung dan tidak disimpan secara permanen di server kami.
                        </div>
                    </div>
                </div>
                <!-- FAQ 2 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Seberapa akurat deteksi emosinya?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Model AI kami memiliki akurasi rata-rata 70–85% tergantung kondisi rekaman. Akurasi meningkat dengan kualitas mikrofon yang baik dan lingkungan yang tenang.
                        </div>
                    </div>
                </div>
                <!-- FAQ 3 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Suku apa saja yang bisa dideteksi?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Saat ini model kami mendukung deteksi 5 suku: Jawa, Sunda, Batak, Minang, dan Betawi. Akan terus dikembangkan.
                        </div>
                    </div>
                </div>
                <!-- FAQ 4 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Berapa lama durasi rekaman yang ideal?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Rekaman minimal 1 menit, idealnya 5–10 menit untuk hasil yang lebih akurat. Maksimal durasi rekaman adalah 30 menit.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <p class="footer-text">
            &copy; 2026 Suaraku. Seluruh hak cipta dilindungi.
        </p>

    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
