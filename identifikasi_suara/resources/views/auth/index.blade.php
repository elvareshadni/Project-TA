<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Pribadi | Suara Ku</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f6fc;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .login-container {
        display: flex;
        background-color: #fff;
        width: 800px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0px 4px 20px rgba(0,0,0,0.1);
    }

    .form-section {
        flex: 1;
        padding: 50px;
    }

    .info-section {
        flex: 1;
        background-color: #1e3a5f;
        text-align: center;
        padding: 60px 30px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 10px;
    }

    .text-primary {
        color: #000000ff !important; 
    }

    .btn-primary {
        background-color: #1e3a5f;
        border: 1px solid #1e3a5f; 
        border-radius: 10px;
        padding: 8px;
        font-weight: 600;
        color: white;
    }

    .btn-primary:hover {
        background-color: #1e3a5f;
    }

    .btn-outline-primary {
        color: #1e3a5f;
        border-color: #1e3a5f;
    }

    .btn-outline-primary:hover,
    .btn-outline-primary:active,
    .btn-outline-primary:focus {
        background-color: #1e3a5f;
        color: #fff;
        border-color: #1e3a5f;
    }


    @media (max-width: 768px) {
        body {
            height: auto;            
            align-items: flex-start; 
            padding: 20px 0;
        }

        .login-container {
            flex-direction: column;
            width: 100%;
            max-width: 480px;
            margin: 0 15px;
            box-shadow: none;    
        }

        .info-section {
            display: none;        
        }

        .form-section {
            padding: 30px 20px;        
        }
    }
</style>

</head>
<body>
    @yield('content')
</body>
</html>
