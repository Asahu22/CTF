<head>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<title>Knowhere Bank</title>
<?php

ini_set('session.gc_maxlifetime', 60);
// Start a session
session_start();

// Initialize user data array
$users = array(
"Daisuke Kambe" => 850,
"Naruto" => 2300,
"Nastu" => 2430,
"Kira" => 4250,
"Deku" => 3570,
"Zero" => 2650,
"Goku" => 2640
);

// Check if balance is already set in session
if (!isset($_SESSION['users'])) {
// Set initial balance
$_SESSION['users'] = $users;
}

// Check if form was submitted via GET request
if (isset($_GET['receiver']) && isset($_GET['sender']) && isset($_GET['amount'])) {
// Retrieve parameter data
$sender = $_GET['sender'];
$receiver = $_GET['receiver'];
$amount = $_GET['amount'];



$query_string = $_SERVER['QUERY_STRING'];
$num_receivers = substr_count($query_string, 'receiver');

if ($num_receivers == 1 && $receiver == 'Daisuke Kambe') {
    echo "Money cannot be sent to oneself.!!!";
    exit(0);
}


if ($sender == $receiver){
    echo "Money cannot be sent to oneself.!!!";
    exit(0);
}


  else{
    if ($amount <= 0) {
        echo "Incorrect amount entered.";
        exit(0);
    }

    // Check if sender exists in the user data array
    if (!array_key_exists($sender, $_SESSION['users'])) {
        echo "Invalid sender entered.";
        exit(0);
    }

    // Check if receiver exists in the user data array
    if (!array_key_exists($receiver, $_SESSION['users'])) {
        echo "Invalid receiver entered.";
        exit(0);
    }

    // Calculate new balances
    $sender_balance = $_SESSION['users'][$sender] - $amount;
    $receiver_balance = $_SESSION['users'][$receiver] + $amount;

    // Check if sender has sufficient balance
    if ($sender_balance < 0) {
        echo "Not enough Money. As of right now, your balance is: {$_SESSION['users'][$sender]}";
        exit(0);
    }

    // Update balances
    $_SESSION['users'][$sender] = $sender_balance;
    $_SESSION['users'][$receiver] = $receiver_balance;

    // Redirect to homepage after successful transaction
    header('Location: index.php');
    exit(0);
 }

} else {
// Print account owner's balance
echo "<h2>Welcome Daisuke Kambe</h2>";
echo "<p>Your current balance is: {$_SESSION['users']['Daisuke Kambe']}</p>";
 echo "<table>";
    echo "<tr><th>Name</th><th>Balance</th></tr>";
    foreach ($_SESSION['users'] as $name => $balance) {
        if ($name != "Daisuke Kambe") {
            echo "<tr><td>$name</td><td>$balance</td></tr>";
       }
    }
    echo "</table>";

    // Print form to send money
    echo "<form action='' method='get'>";
    echo "<input type='hidden' name='sender' value='Daisuke Kambe'>";
    echo "<label for='receiver'>Receiver:</label>";
    echo "<select name='receiver' id='receiver'>";
    foreach ($_SESSION['users'] as $name => $balance) {
        if ($name != "Daisuke Kambe") {
            echo "<option value='$name'>$name</option>";
        }
    }
    echo "</select><br><br>";
    echo "<label for='amount'>Amount:</label>";
    echo "<input type='number' name='amount' id='amount'><br><br>";
    echo "<button type='submit'>Submit</button>";
    echo "</form>";
}

if ($_SERVER['REQUEST_URI'] == '/admin') {
  header('Location: admin.php');
  exit();
}
?>
