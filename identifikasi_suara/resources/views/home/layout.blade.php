<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUARA KU - Identifikasi Emosi Suara</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<header>
    <div class="logo">
        <img src="./img/logo-suaraku.png" alt="Logo Suara Ku" width="100" class="logo-img">
    </div>
    <nav>
        <span class="username">
            Halo, <strong>{{ session('username') }}</strong>
        </span>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn-logout" style="background:none; border:none; color:#1e3a5f;">
                <i class="fa-solid fa-arrow-right-from-bracket fa-lg"></i>
            </button>
        </form>
    </nav>
</header>

<footer class="home-footer">
    Copyright © Your Website {{ now()->year }}
</footer>