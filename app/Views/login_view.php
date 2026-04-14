<!DOCTYPE html>
<html>
<head>
    <title>Login App</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #f4f4f4;
            color: #1a1a1a;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 350px;
            text-align: center;
            border: 1px solid #e0e0e0;
        }

        h2 {
            margin-bottom: 30px;
            font-weight: 400;
            letter-spacing: 1px;
            border-bottom: 1px solid #1a1a1a;
            display: inline-block;
            padding-bottom: 5px;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        input {
            width: 100%;
            padding: 12px 0;
            border: none;
            border-bottom: 1px solid #ccc;
            background: transparent;
            outline: none;
            font-size: 16px;
            transition: border-color 0.3s ease;
            font-family: inherit;
        }

        input:focus {
            border-bottom: 1px solid #1a1a1a;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #1a1a1a;
            color: #ffffff;
            border: 1px solid #1a1a1a;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        button:hover {
            background-color: #ffffff;
            color: #1a1a1a;
        }

        .footer-link {
            margin-top: 25px;
            font-size: 12px;
            color: #666;
        }

        .footer-link a {
            color: #1a1a1a;
            text-decoration: none;
            border-bottom: 1px dotted #1a1a1a;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        
        <div class="input-group">
            <input type="text" id="username" placeholder="Username" autocomplete="off">
        </div>
        
        <div class="input-group">
            <input type="password" id="password" placeholder="Password">
        </div>

        <button onclick="doLogin()">Sign In</button>

        <div class="footer-link">
            Belum punya akun? <a href="/auth/register">Daftar di sini</a>
        </div>
    </div>

    <script>
        async function doLogin() {
            const user = document.getElementById('username');
            const pass = document.getElementById('password');

            const username = user.value;
            const password = pass.value;

            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        username: username,
                        password: password
                    })
                });

                const data = await response.json();

                if(response.ok) {
                    localStorage.setItem('access_token', data.token);
                    alert('Login Berhasil!')
                    user.value = '';
                    pass.value = '';

                    window.location.href = '/';
                } else {
                    alert("Login Gagal: " + data.messages.error);
                }
            } catch (error) {
                console.error("Fetch error:", error);
                alert("Terjadi kesalahan koneksi ke server.");
            }
        }
    </script>
</body>
</html>