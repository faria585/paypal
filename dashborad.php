<?php
session_start();
if (!isset($_SESSION['user_id'])) header('Location: login.php');

$conn = new mysqli('localhost', 'ubcq5myob5oyh', '8gktvc2di2wt', 'dbgc0tpjy1sw9n');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        table { margin: 20px auto; border-collapse: collapse; width: 80%; }
        th, td { padding: 10px; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo $user['email']; ?></h2>
    <p>Balance: $<?php echo $user['balance']; ?></p>
    <form method="post" action="send_payment.php">
        <input type="email" name="receiver_email" placeholder="Recipient Email" required>
        <input type="number" name="amount" placeholder="Amount" required>
        <button type="submit">Send Payment</button>
    </form>
    <h3>Your Transactions</h3>
    <table>
        <tr><th>Transaction ID</th><th>Type</th><th>Amount</th><th>Timestamp</th></tr>
        <?php
        $transactions = $conn->query("SELECT * FROM transactions WHERE sender_id=$user_id OR receiver_id=$user_id");
        while ($txn = $transactions->fetch_assoc()) {
            $type = $txn['sender_id'] == $user_id ? 'Sent' : 'Received';
            echo "<tr>
                    <td>{$txn['id']}</td>
                    <td>{$type}</td>
                    <td>{$txn['amount']}</td>
                    <td>{$txn['timestamp']}</td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>
