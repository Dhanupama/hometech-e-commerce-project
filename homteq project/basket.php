<?php
session_start();
include("db.php");

$pagename = "Smart Basket";

echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";
echo "<title>" . $pagename . "</title>";

echo "<body>";

include("headfile.html");

echo "<h4>" . $pagename . "</h4>";

// If the value of the product id to be deleted (which was posted through the hidden field) is set
if (isset($_POST['del_prodid'])) {
    // Capture the posted product id and assign it to a local variable $delprodid
    $delprodid = $_POST['del_prodid'];
    // Unset the cell of the session for this posted product id variable
    unset($_SESSION['basket'][$delprodid]);
    // Display a "1 item removed from the basket" message
    echo "<p>1 item removed</p>";
}

// Check if a new product is being added
if (isset($_POST['h_prodid'])) {
    $newprodid = $_POST['h_prodid'];
    $reququantity = $_POST['p_quantity'];

    // Store product in session basket
    $_SESSION['basket'][$newprodid] = $reququantity;
    echo "<p>1 item added</p>";
} else {
    echo "<p>Basket unchanged</p>";
}

// Initialize total price
$total = 0;

// Display basket contents
echo "<table id='baskettable'>";
echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th>Action</th></tr>";

if (isset($_SESSION['basket']) && !empty($_SESSION['basket'])) {
    foreach ($_SESSION['basket'] as $index => $value) {
        $SQL = "SELECT prodId, prodName, prodPrice FROM Product WHERE prodId=" . $index;
        $exeSQL = mysqli_query($conn, $SQL) or die(mysqli_error($conn));
        $arrayp = mysqli_fetch_array($exeSQL);

        $subtotal = $arrayp['prodPrice'] * $value;
        $total += $subtotal;

        echo "<tr>";
        echo "<td>" . $arrayp['prodName'] . "</td>";
        echo "<td>&pound" . number_format($arrayp['prodPrice'], 2) . "</td>";
        echo "<td style='text-align:center;'>" . $value . "</td>";
        echo "<td>&pound" . number_format($subtotal, 2) . "</td>";
        
        // Remove button
        echo "<td>";
        echo "<form action='basket.php' method='post'>";
        echo "<input type='hidden' name='del_prodid' value='" . $arrayp['prodId'] . "'>";
        echo "<input type='submit' value='Remove'>";
        echo "</form>";
        echo "</td>";

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>Your basket is empty</td></tr>";
}

// Display total row
echo "<tr><td colspan='3'><strong>TOTAL</strong></td>";
echo "<td>&pound" . number_format($total, 2) . "</td><td></td></tr>";
echo "</table>";

echo "<br><p><a href='clearbasket.php'>CLEAR BASKET</a></p>";

echo "<br><p>New homteq customers: <a href='signup.php'>Sign up</a></p>";
echo "<p>Returning homteq customers: <a href='login.php'>Login</a></p>";

include("footfile.html");
echo "</body>";
?>
