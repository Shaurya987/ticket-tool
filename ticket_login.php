<?php
// Database credentials
$host = 'localhost';
$dbname = 'u221875567_ticket_isuues';
$username = 'u221875567_Prayatna_it';
$password = 'Prayatna1@';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",$username,$password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection Error: " . $e->getMessage());
}

$error = '';
$email = '';
$ticket_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $ticket_id = trim($_POST['ticket_id'] ?? '');

    if (!empty($email) && !empty($ticket_id)) {
        $stmt = $pdo->prepare("SELECT status FROM tickets WHERE email = :email AND id = :id LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $ticket_id);
        if ($stmt->execute()) {
            $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($ticket) {
                // If ticket found, redirect to the status page
                header("Location: ticket_status.php?email=".urlencode($email)."&ticket_id=".urlencode($ticket_id));
                exit();
            } else {
                $error = "No ticket found with the provided details.";
            }
        } else {
            $error = "Database query failed.";
        }
    } else {
        $error = "Please enter both Email and Ticket ID.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Check Your Status - Login</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    /* Global Reset & Typography */
    * {
        box-sizing: border-box;
    }
    body {
        margin:0; 
        font-family:'Inter',sans-serif;
        background: linear-gradient(135deg, #fdfcfb 0%, #f8f4ec 100%);
        display:flex; 
        flex-direction:column; 
        min-height:100vh;
        color:#333;
        overflow-x:hidden;
    }

    /* Header */
    header {
        background:#f2c68f; 
        padding:20px;
        text-align:center;
        box-shadow:0 2px 4px rgba(0,0,0,0.1);
        position: relative;
        z-index: 10;
    }
    header h1 {
        margin:0;
        font-size:2em;
        font-weight:700;
        color:#000;
    }

    /* Container / Form Card */
    .container {
        flex:1;
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:center;
        padding:20px;
    }
    .form-card {
        width:100%;
        max-width:400px;
        background:#fff;
        border-radius:16px;
        box-shadow:0 4px 20px rgba(0,0,0,0.1);
        padding:30px 20px 40px;
        text-align:center;
        transform: translateY(20px);
        opacity:0;
        animation: fadeInUp 0.6s ease forwards 0.3s;
    }

    @keyframes fadeInUp {
        to {
            transform: translateY(0);
            opacity:1;
        }
    }

    h2 {
        font-size:1.25em;
        margin-bottom:30px;
        font-weight:600;
        color:#000;
    }

    /* Form Elements */
    form {
        width:100%;
        display:flex;
        flex-direction:column;
        gap:20px;
        margin-bottom:30px;
        align-items:center;
    }
    .input-field {
        width:100%;
        background:#f0f0f0;
        border-radius:25px;
        overflow:hidden;
        display:flex;
        align-items:center;
        padding:12px 20px;
        box-sizing:border-box;
        border:1px solid #ddd;
        transition: background 0.3s, box-shadow 0.3s;
    }
    .input-field:focus-within {
        background:#e9e9e9;
        box-shadow:0 0 0 3px rgba(242,198,143,0.3);
    }
    .input-field input {
        border:none;
        background:transparent;
        width:100%;
        font-size:1em;
        color:#000;
        outline:none;
        font-weight:500;
    }

    /* Button */
    .action-btn {
        background:#5c3b1e; 
        color:#fff;
        border:none;
        padding:14px 30px;
        border-radius:25px;
        font-size:1em;
        font-weight:600;
        cursor:pointer;
        transition:background 0.3s,transform 0.3s, box-shadow 0.3s;
        box-shadow:0 4px 10px rgba(0,0,0,0.1);
    }
    .action-btn:hover {
        background:#4a2e17;
        transform:translateY(-2px);
        box-shadow:0 6px 14px rgba(0,0,0,0.15);
    }

    /* Error Message */
    .error {
        color:#d9534f;
        font-weight:600;
        margin-bottom:20px;
    }

    /* Footer Info */
    .footer-info {
        text-align:center;
        font-weight:600;
        color:#000;
        font-size:0.95em;
        margin-bottom:20px;
        padding:10px;
    }
    .footer-info strong {
        display:block;
        margin-bottom:5px;
    }

    /* Responsive Adjustments */
    @media (max-width:600px) {
        header h1 {
            font-size:1.6em;
        }
        h2 {
            font-size:1.1em;
        }
        .action-btn {
            font-size:0.95em;
            padding:12px 25px;
        }
        .input-field {
            padding:10px 15px;
        }
        .input-field input {
            font-size:0.95em;
        }
        .form-card {
            padding:20px 20px 30px;
        }
    }
</style>
</head>
<body>
<header>
    <h1>CHECK YOUR STATUS</h1>
</header>
<div class="container">
    <div class="form-card">
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <h2>ENTER YOUR DETAILS</h2>
        <form method="POST">
            <div class="input-field">
                <input type="email" name="email" placeholder="Enter Your Email" required value="<?php echo htmlspecialchars($email); ?>"/>
            </div>
            <div class="input-field">
                <input type="text" name="ticket_id" placeholder="Enter Ticket ID" required value="<?php echo htmlspecialchars($ticket_id); ?>"/>
            </div>
            <button type="submit" class="action-btn">Processed</button>
        </form>
    </div>
</div>
<div class="footer-info">
    <strong>IT PRAYATNA</strong>
    CONTACT: +91 1203288717<br>
    Email: abc@praytanaworld.org
</div>
</body>
</html>