<?php
// Database credentials
$host = 'localhost';
$dbname = 'u221875567_ticket_isuues';
$username = 'u221875567_Prayatna_it';
$password = 'Prayatna1@';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection Error: " . $e->getMessage());
}

$error = '';
$status = '';
$email = isset($_GET['email']) ? trim($_GET['email']) : '';
$ticket_id = isset($_GET['ticket_id']) ? trim($_GET['ticket_id']) : '';

if (!empty($email) && !empty($ticket_id)) {
    $stmt = $pdo->prepare("SELECT status FROM tickets WHERE email = :email AND id = :id LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id', $ticket_id);
    if ($stmt->execute()) {
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($ticket) {
            $dbStatus = strtolower($ticket['status']);
            if ($dbStatus === 'new') {
                $status = 'Issue Raised';
            } elseif ($dbStatus === 'received') {
                $status = 'In Process';
            } elseif ($dbStatus === 'resolved') {
                $status = 'Resolved';
            } else {
                $error = "Unknown status found.";
            }
        } else {
            $error = "No ticket found. Please re-enter your details.";
        }
    } else {
        $error = "Database query failed.";
    }
} else {
    $error = "Invalid access. Please go back and enter your details.";
}
?>
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Ticket Status</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <style>
        /* (Your existing CSS remains unchanged) */
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #fff;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            background: #f2c68f;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        header h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
            color: #000;
        }
        .container {
            flex: 1;
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            text-align: center;
        }
        .progress-section {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 90%;
            max-width: 1200px;
            margin: 50px auto;
        }
        .progress-line {
            position: absolute;
            top: 50%;
            left: 5%;
            right: 5%;
            height: 6px;
            background: #ccc;
            border-radius: 5px;
            z-index: 1;
        }
        .line-fill {
            position: absolute;
            top: 50%;
            left: 0;
            height: 6px;
            background: linear-gradient(270deg, #7a5335, #5c3b1e);
            border-radius: 5px;
            z-index: 2;
            width: 0;
            transition: width 0.8s ease-in-out;
        }
        .progress-step {
            position: relative;
            z-index: 3;
            width: 30px;
            height: 30px;
            background: #ccc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.5s ease-in-out;
        }
        .progress-step.active {
            background: #5c3b1e;
            color: #fff;
            transform: scale(1.2);
            box-shadow: 0 0 10px rgba(92, 59, 30, 0.6);
        }
        .progress-step-label {
            position: absolute;
            top: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
            text-align: center;
            width: 90px;
        }
        .progress-section .progress-step:not(:last-child) {
            margin-right: 150px; /* Increased spacing between dots */
        }
        @media (max-width: 768px) {
            .progress-step {
                width: 25px;
                height: 25px;
            }
            .progress-step-label {
                font-size: 0.8rem;
                top: 40px;
                width: 80px;
            }
            .progress-line {
                left: 10%;
                right: 10%;
            }
            .progress-section .progress-step:not(:last-child) {
                margin-right: 100px; /* Adjusted spacing for tablets */
            }
        }
        @media (max-width: 480px) {
            .progress-step {
                width: 20px;
                height: 20px;
            }
            .progress-step-label {
                font-size: 0.7rem;
                top: 35px;
                width: 70px;
            }
            .progress-line {
                height: 4px;
            }
            .progress-section .progress-step:not(:last-child) {
                margin-right: 70px; /* Increased spacing for mobile devices */
            }
        }
        .home-btn {
            margin: 50px auto 30px;
            background: #5c3b1e;
            color: #fff;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .home-btn:hover {
            background: #7a5335;
        }
        footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            font-size: 0.9rem;
            color: #333;
            background: #f8f8f8;
            box-shadow: 0 -1px 4px rgba(0, 0, 0, 0.1);
        }
        footer strong {
            display: block;
            font-weight: 700;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<header>
    <h1>CHECK YOUR STATUS</h1>
</header>
<div class="container">
    <?php if (!empty($error)): ?>
        <div style="color: red; margin-bottom: 20px;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php else: ?>
        <div class="progress-section" id="progressSection">
            <div class="progress-line"></div>
            <div class="line-fill"></div>
            <div class="progress-step"><span class="progress-step-label">Issue Raised</span></div>
            <div class="progress-step"><span class="progress-step-label">In Process</span></div>
            <div class="progress-step"><span class="progress-step-label">Resolved</span></div>
        </div>
        <a href="index.html" class="home-btn">Home Page</a>
    <?php endif; ?>
</div>
<footer>
    <strong> IT Department PRAYATNA</strong>
     CONTACT: +91 1203288717<br>
     Email: itsupport@prayatnaworld.org

</footer>
<script>
    (function () {
        const status = "<?php echo $status; ?>"; // Dynamically set status from PHP

        const stages = {
            "Issue Raised": { width: "33%", step: 1 },
            "In Process": { width: "66%", step: 2 },
            "Resolved": { width: "100%", step: 3 },
        };

        const progressSection = document.getElementById('progressSection');
        const lineFill = document.querySelector('.line-fill');
        const steps = document.querySelectorAll('.progress-step');

        if (status && stages[status]) {
            const stage = stages[status];
            let currentStep = 0;

            const animateSteps = () => {
                if (currentStep < stage.step) {
                    steps[currentStep].classList.add('active'); // Activate the current step
                    lineFill.style.width = `${((currentStep + 1) / 3) * 100}%`; // Update line width dynamically
                    currentStep++;
                    setTimeout(animateSteps, 800); // Delay for smooth animation
                }
            };

            animateSteps(); // Start the animation
        }
    })();
</script>
</body>
</html>