<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suaraku</title>
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
            --border-color: #cbd5e1;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-gray);
            color: var(--text-dark);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }

        /* Split Container Card */
        .login-container {
            display: flex;
            background-color: #fff;
            width: 920px;
            min-height: 220px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0px 10px 30px rgba(12, 86, 208, 0.06);
            border: 2px solid #e2e8f0;
        }

        /* Left side: White Form */
        .form-section {
            flex: 1.1;
            padding: 40px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Right side: Blue Card */
        .info-section {
            flex: 0.9;
            background-color: var(--primary-blue);
            color: white;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 15px;
            position: relative;
        }

        /* Form Controls */
        .form-label {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            font-weight: 700;
            color: #475569;
            letter-spacing: 0.8px;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1.5px solid var(--border-color);
            padding: 10px 14px;
            font-size: 13.5px;
            color: var(--text-dark);
            background-color: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(12, 86, 208, 0.12);
            outline: none;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        /* Checkbox & Radios */
        .form-check-input {
            border: 1.5px solid var(--border-color);
        }

        .form-check-input:checked {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .form-check-label {
            font-size: 13.5px;
            color: #334155;
            font-weight: 500;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary-blue);
            border: none;
            border-radius: 8px;
            padding: 11px;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 14.5px;
            color: white;
            transition: background-color 0.2s, transform 0.1s;
        }

        .btn-primary:hover {
            background-color: var(--primary-blue-hover);
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .btn-outline-primary {
            background-color: transparent;
            border: 1.5px solid var(--primary-blue);
            color: var(--primary-blue);
            border-radius: 8px;
            padding: 10px;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 14px;
            transition: background-color 0.2s, color 0.2s, transform 0.1s;
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:active,
        .btn-outline-primary:focus {
            background-color: var(--primary-blue);
            color: #fff;
            border-color: var(--primary-blue);
        }

        .btn-outline-primary:active {
            transform: scale(0.98);
        }

        /* Animations */
        .logo-img {
            width: 20px;
            height: 20px;
            object-fit: contain;
        }

        .audio-wave {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            height: 35px;
            gap: 4px;
        }

        .audio-wave span {
            display: block;
            width: 4px;
            height: 6px;
            background: white;
            border-radius: 2px;
            animation: wave 1.2s infinite ease-in-out;
        }

        .audio-wave span:nth-child(1) { animation-delay: 0s; }
        .audio-wave span:nth-child(2) { animation-delay: 0.1s; }
        .audio-wave span:nth-child(3) { animation-delay: 0.2s; }
        .audio-wave span:nth-child(4) { animation-delay: 0.3s; }
        .audio-wave span:nth-child(5) { animation-delay: 0.4s; }
        .audio-wave span:nth-child(6) { animation-delay: 0.5s; }

        @keyframes wave {
            0%, 100% {
                height: 6px;
                opacity: 0.4;
            }
            50% {
                height: 28px;
                opacity: 1;
            }
        }

        /* Responsive */
        @media (max-width: 900px) {
            .login-container {
                width: 100%;
                max-width: 800px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
                height: auto;
                align-items: flex-start;
            }

            .login-container {
                flex-direction: column;
                width: 100%;
                max-width: 450px;
                box-shadow: none;
                border: none;
                background-color: transparent;
            }

            .form-section {
                padding: 30px 20px;
                background-color: #fff;
                border-radius: 20px;
                box-shadow: 0px 4px 20px rgba(12, 86, 208, 0.05);
                border: 1px solid #e2e8f0;
            }

            .info-section {
                display: none !important;
            }
        }
    </style>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    @yield('content')
</body>
</html>
