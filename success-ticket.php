<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Success Page</title>
  <style>
    body {
      margin: 0;
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #FF9800, #E65100);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      padding: 1rem;
    }

    .container {
      text-align: center;
      background: rgba(255, 255, 255, 0.1);
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
      animation: fadeIn 1s ease-out;
      max-width: 600px;
      width: 100%;
    }

    h1 {
      font-size: 2rem;
      margin-bottom: 1rem;
      color: #f1f1f1;
    }

    p {
      font-size: 1rem;
      line-height: 1.6;
      margin-bottom: 2rem;
    }

    .ticket-id {
      font-weight: bold;
      color: #FFD700;
    }

    .button {
      display: inline-block;
      background: #FFD700;
      color: #E65100;
      text-decoration: none;
      padding: 0.8rem 1.5rem;
      border-radius: 25px;
      font-weight: bold;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
      transition: all 0.3s ease;
      font-size: 1rem;
    }

    .button:hover {
      background: #FFC107;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 768px) {
      h1 {
        font-size: 1.8rem;
      }

      p {
        font-size: 0.9rem;
      }

      .button {
        font-size: 0.9rem;
        padding: 0.7rem 1.2rem;
      }
    }

    @media (max-width: 480px) {
      h1 {
        font-size: 1.5rem;
      }

      p {
        font-size: 0.85rem;
      }

      .button {
        font-size: 0.8rem;
        padding: 0.6rem 1rem;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>🎉 Success!</h1>
    <p>Your ticket has been successfully raised. <br>
       Your <span class="ticket-id">Ticket ID</span> has been sent to your email.</p>
    <p>Please check your email to track your ticket status.</p>
    <a href="ticket_login.php" class="button">Check Ticket Status</a>
  </div>
</body>
</html>