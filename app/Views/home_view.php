<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Classic CRUD</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #f4f4f4;
            color: #1a1a1a;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .main-card {
            background-color: #ffffff;
            padding: 50px;
            border-radius: 2px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 600px;
            text-align: center;
            border: 1px solid #e0e0e0;
            position: relative;
        }

        .main-card::before {
            content: "";
            position: absolute;
            top: 10px; left: 10px; right: 10px; bottom: 10px;
            border: 1px solid #f0f0f0;
            pointer-events: none;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 400;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }

        p.subtitle {
            font-style: italic;
            color: #666;
            margin-bottom: 40px;
        }

        .nav-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .btn {
            text-decoration: none;
            color: #1a1a1a;
            padding: 10px 25px;
            border: 1px solid #1a1a1a;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background-color: #1a1a1a;
            color: #fff;
        }

        .btn-black {
            background-color: #1a1a1a;
            color: #fff;
        }

        .btn-black:hover {
            background-color: #fff;
            color: #1a1a1a;
        }

        #auth-section { display: none; }
        #guest-section { display: none; }

        .status-badge {
            font-size: 10px;
            text-transform: uppercase;
            background: #eee;
            padding: 4px 8px;
            display: inline-block;
            margin-bottom: 20px;
        }

        .product-preview {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px double #ddd;
            font-size: 14px;
            color: #444;
        }
    </style>
</head>
<body>

<div class="main-card">
    <div id="status-container"></div>
    <h1>The Classic Archive</h1>
    <p class="subtitle">Personal Goods Management System</p>

    <div id="guest-section">
        <p>Silakan masuk ke akun Anda untuk mengelola arsip barang.</p>
        <div class="nav-links">
            <a href="/auth/login" class="btn">Login</a>
            <a href="/auth/register" class="btn btn-black">Join Member</a>
        </div>
    </div>

    <div id="auth-section">
        <div class="status-badge">Authenticated Session</div>
        <p>Selamat datang kembali. Anda memiliki akses penuh ke sistem.</p>
        <div class="nav-links">
            <a href="/products" class="btn btn-black">Manage Products</a>
            <a href="#" onclick="doLogout()" class="btn">Logout</a>
        </div>
        
        <div class="product-preview">
            <small>Ready to synchronize with your database.</small>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const token = localStorage.getItem('access_token');
        const guestSec = document.getElementById('guest-section');
        const authSec = document.getElementById('auth-section');

        if (token) {
            authSec.style.display = 'block';
            guestSec.style.display = 'none';
        } else {
            authSec.style.display = 'none';
            guestSec.style.display = 'block';
        }
    });

    function doLogout() {
        if(confirm("Apakah Anda ingin keluar dari sistem?")) {
            localStorage.removeItem('access_token');
            alert("Sesi diakhiri.");
            window.location.reload(); 
        }
    }
</script>

</body>
</html>