<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}
// rest of admin.php code

// Database configuration
$host = 'localhost';
$dbname = 'u221875567_ticket_isuues';
$username = 'u221875567_Prayatna_it';
$password = 'Prayatna1@';

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

// Handle ticket status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id']) && isset($_POST['current_status'])) {
    $ticket_id = $_POST['ticket_id'];
    $current_status = $_POST['current_status'];

    // Determine the next status based on the current status
    $next_status = '';
    if ($current_status === 'New') {
        $next_status = 'Received';
    } elseif ($current_status === 'Received') {
        $next_status = 'Resolved';
    }

    if (!empty($next_status)) {
        try {
            $stmt = $pdo->prepare("UPDATE tickets SET status = :status WHERE id = :id");
            $stmt->bindParam(':status', $next_status);
            $stmt->bindParam(':id', $ticket_id);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error updating ticket status: " . $e->getMessage();
        }
    }
}

// Fetch tickets that are New or Received
try {
    $stmt = $pdo->prepare("SELECT id, name, designation, email, mobile, issue, status 
                           FROM tickets 
                           WHERE status IN ('New','Received')
                           ORDER BY id DESC");
    $stmt->execute();
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin Ticket Portal</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* BODY & BACKGROUND */
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
            background-size: 400% 400%;
            animation: gradientBG 10s ease infinite;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        header {
            padding: 20px;
            text-align: center;
            color: #fff;
        }

        header h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .container {
            flex: 1;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        h2 {
            margin-top: 0;
            font-size: 1.8em;
            font-weight: 700;
            color: #fff;
            text-align: center;
            margin-bottom: 40px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .ticket-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            padding: 0 10px;
        }

        @media (max-width: 1000px) {
            .ticket-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }
        }

        @media (max-width: 600px) {
            .ticket-grid {
                grid-template-columns: 1fr;
            }
        }

        .ticket-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            position: relative;
            transition: transform 0.5s ease, box-shadow 0.5s ease;
            opacity: 1;
        }

        .ticket-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 14px;
            text-align: center;
            font-weight: 600;
            color: #fff;
            font-size: 1.1em;
            letter-spacing: 0.5px;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        .ticket-details {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .ticket-id {
            font-size: 0.95em;
            color: #999;
            margin-bottom: 10px;
        }

        .ticket-field {
            margin: 5px 0;
            font-size: 1em;
            line-height: 1.3;
        }

        .ticket-field strong {
            font-weight: 600;
            color: #555;
        }

        .ticket-issue {
            margin-top: 10px;
            font-size: 1em;
            line-height: 1.5;
            color: #555;
            white-space: pre-wrap;
        }

        .action-btn {
            margin-top: 20px;
            background: #ff5858;
            color: #fff;
            border: none;
            padding: 12px 0;
            cursor: pointer;
            font-size: 1em;
            border-radius: 50px;
            text-transform: uppercase;
            font-weight: 700;
            transition: background 0.3s, transform 0.3s;
            width: 100%;
            text-align: center;
            box-shadow: 0 5px 15px rgba(255,88,88,0.3);
        }

        .action-btn:hover {
            background: #ff3b3b;
            transform: translateY(-2px);
        }

        .no-tickets {
            text-align: center;
            font-size: 1.2em;
            color: #fff;
            margin-top: 50px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        /* Slide out animation */
        @keyframes slideOut {
            0% { transform: translateX(0); opacity: 1; }
            100% { transform: translateX(100%); opacity: 0; }
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Ticket Management</h1>
    </header>
    <div class="container">
        <h2>Handle Your Tickets with Style</h2>
        <?php if (count($tickets) > 0): ?>
            <div class="ticket-grid">
                <?php foreach ($tickets as $ticket): ?>
                    <div class="ticket-card" id="ticket-<?php echo htmlspecialchars($ticket['id']); ?>">
                        <div class="card-header">
                            <?php echo htmlspecialchars($ticket['status']) === 'New' ? 'NEW TICKET' : 'RECEIVED TICKET'; ?>
                        </div>
                        <div class="ticket-details">
                            <div class="ticket-id">#<?php echo htmlspecialchars($ticket['id']); ?></div>
                            <div class="ticket-field"><strong>Name:</strong> <?php echo htmlspecialchars($ticket['name']); ?></div>
                            <div class="ticket-field"><strong>Designation:</strong> <?php echo htmlspecialchars($ticket['designation']); ?></div>
                            <div class="ticket-field"><strong>Email:</strong> <?php echo htmlspecialchars($ticket['email']); ?></div>
                            <div class="ticket-field"><strong>Mobile:</strong> <?php echo htmlspecialchars($ticket['mobile']); ?></div>
                            <div class="ticket-issue"><strong>Issue:</strong> <?php echo htmlspecialchars($ticket['issue']); ?></div>
                            <form method="POST" action="admin.php" onsubmit="return animateCardAndSubmit(event, '<?php echo htmlspecialchars($ticket['id']); ?>')">
                                <input type="hidden" name="ticket_id" value="<?php echo htmlspecialchars($ticket['id']); ?>" />
                                <input type="hidden" name="current_status" value="<?php echo htmlspecialchars($ticket['status']); ?>" />
                                <?php if ($ticket['status'] === 'New'): ?>
                                    <button type="submit" class="action-btn">Mark as Received</button>
                                <?php elseif ($ticket['status'] === 'Received'): ?>
                                    <button type="submit" class="action-btn">Mark as Resolved</button>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-tickets">No new or received tickets to display.</div>
        <?php endif; ?>
    </div>

    <script>
        function animateCardAndSubmit(event, ticketId) {
            event.preventDefault();
            const form = event.target;
            const card = document.getElementById('ticket-' + ticketId);
            // Add a slide-out animation
            card.style.animation = 'slideOut 0.5s forwards';

            // After animation ends, submit the form
            card.addEventListener('animationend', () => {
                form.submit();
            });
            return false;
        }
    </script>
</body>
</html>