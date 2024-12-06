<?php
session_start();

// If already logged in, redirect to admin page
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin.php");
    exit();
}

// Handle form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_password = $_POST['admin_password'] ?? '';
    $correct_password = 'adminlogin876866@#%@#%';

    if ($entered_password === $correct_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error = "Incorrect password, please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin Login</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            /* Animated gradient background */
            background: linear-gradient(-45deg, #ff9a9e, #fad0c4, #fad0c4, #ff9a9e);
            background-size: 400% 400%;
            animation: gradientBG 10s ease infinite;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: #333;
            justify-content: center;
            align-items: center;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        header {
            margin-bottom: 30px;
            text-align: center;
            color: #fff;
        }

        header h1 {
            margin: 0;
            font-size: 2.7em;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .container {
            width: 100%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
            display: flex;
            flex-direction: column;
            animation: fadeIn 0.8s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            margin-top: 0;
            font-size: 1.6em;
            font-weight: 700;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        input[type="password"], input[type="text"] {
            font-size: 1em;
            padding: 14px 50px 14px 20px;
            border: none;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.6);
            width: 100%;
            box-sizing: border-box;
            color: #333;
            font-weight: 500;
            transition: background 0.3s, box-shadow 0.3s;
        }

        input[type="password"]:focus, input[type="text"]:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 0 3px rgba(255,255,255,0.4);
        }

        .toggle-eye {
            position: absolute;
            right: 15px;
            cursor: pointer;
            width: 24px;
            height: 24px;
            fill: #555;
            transition: fill 0.3s;
        }

        .toggle-eye:hover {
            fill: #333;
        }

        .action-btn {
            background: #ff5858;
            color: #fff;
            border: none;
            padding: 14px 0;
            cursor: pointer;
            font-size: 1em;
            border-radius: 50px;
            font-weight: 700;
            text-align: center;
            box-shadow: 0 5px 15px rgba(255,88,88,0.3);
            transition: background 0.3s, transform 0.3s, box-shadow 0.3s;
        }

        .action-btn:hover {
            background: #ff3b3b;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255,88,88,0.4);
        }

        .error {
            text-align: center;
            color: #d9534f;
            font-weight: 600;
            margin-bottom: -10px;
        }

        @media (max-width: 600px) {
            .container {
                margin: 0 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Login</h1>
    </header>
    <div class="container">
        <h2>Enter Password</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="input-wrapper">
                <input type="password" name="admin_password" placeholder="Enter admin password" id="passwordInput" required />
                <svg class="toggle-eye" id="toggleEye" viewBox="0 0 24 24">
                    <path d="M12 4.5C7.305 4.5 3.24 7.634 1.5 12c1.74 4.366 5.805 7.5 10.5 7.5s8.76-3.134 10.5-7.5c-1.74-4.366-5.805-7.5-10.5-7.5zm0 12.375A4.875 4.875 0 1 1 12 7.125a4.875 4.875 0 0 1 0 9.75zm0-7.5a2.625 2.625 0 1 0 0 5.25 2.625 2.625 0 0 0 0-5.25z"/>
                </svg>
            </div>
            <button type="submit" class="action-btn">Login</button>
        </form>
    </div>

    <script>
        const toggleEye = document.getElementById('toggleEye');
        const passwordInput = document.getElementById('passwordInput');

        toggleEye.addEventListener('click', () => {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
    </script>
</body>
</html>