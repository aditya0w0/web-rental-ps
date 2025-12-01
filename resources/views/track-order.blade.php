<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main PS - Track Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f0f0f, #1a1a1a);
            color: white;
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        }
        .btn-track {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            border: none;
            font-weight: bold;
            padding: 14px 35px;
            border-radius: 50px;
            transition: all 0.4s;
            box-shadow: 0 8px 20px rgba(255, 107, 107, 0.3);
        }
        .btn-track:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(255, 107, 1070.5);
        }
        .input-group input {
            border-radius: 50px 0 0 50px;
            border: none;
            padding: 14px 25px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .input-group input::placeholder {
            color: #ccc;
        }
        .input-group button {
            border-radius: 0 50px 50px 0;
        }
        .logo {
            font-size: 3rem;
            font-weight: 900;
            background: linear-gradient(45deg, #ff6b6b, #feca57);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .tagline {
            color: #aaa;
            font-size: 1.1rem;
        }
        .result-card {
            background: rgba(0, 255, 0, 0.1);
            border: 1px solid rgba(0, 255, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="container mt-5 pt-5">
        <div class="text-center mb-5">
            <h1 class="logo">MAIN PS</h1>
            <p class="tagline">Rental PlayStation 24 Jam • Harga Terjangkau • Unit Terawat</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card p-5">
                    <h3 class="text-center mb-4 fw-bold">Track Your Order</h3>
                    <form action="{{ route('transaction.track.post