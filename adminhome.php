<?php
require_once("includes/vars.php");

$totalUsers = 0;
$totalOrders = 0;
$pendingOrders = 0;
$totalRevenue = 0;

$conn = mysqli_connect(dbhost, dbuname, dbpass, dbname);

if ($conn) {

    // Total Users
    $q1 = "SELECT COUNT(*) AS total FROM register";
    $r1 = mysqli_query($conn, $q1);
    if ($row = mysqli_fetch_assoc($r1)) {
        $totalUsers = $row['total'];
    }

    // Total Orders
    $q2 = "SELECT COUNT(*) AS total FROM ordertable";
    $r2 = mysqli_query($conn, $q2);
    if ($row = mysqli_fetch_assoc($r2)) {
        $totalOrders = $row['total'];
    }

    // Pending Orders
    $q3 = "SELECT COUNT(*) AS total FROM ordertable WHERE status='Pending'";
    $r3 = mysqli_query($conn, $q3);
    if ($row = mysqli_fetch_assoc($r3)) {
        $pendingOrders = $row['total'];
    }

    // Total Revenue
    $q4 = "SELECT SUM(orderamount) AS total FROM ordertable";
    $r4 = mysqli_query($conn, $q4);
    if ($row = mysqli_fetch_assoc($r4)) {
        $totalRevenue = $row['total'] ?? 0;
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php require_once("includes/headlink.php"); ?>
</head>

<body>

<?php require_once("includes/adminheader.php"); ?>

<div class="container mt-4">

    <h2 class="adhome">Admin Dashboard</h2>

    <div class="row">

        <!-- Users -->
        <div class="col-md-4 crd1">
            <a href="listofmembers.php"><div class="card text-white bg-primary mb-3 shadow  crd">
                <div class="card-body">
                    <h5 class="card-title">Users</h5>
                    <h3><?php echo $totalUsers; ?></h3>
                    <p>View Users</p>
                </div>
            </div></a>
        </div>

        <!-- Orders -->
        <div class="col-md-4">
           <a href="listoforders.php"><div class="card text-white bg-success mb-3 shadow crd">
                <div class="card-body">
                    <h5 class="card-title">Orders</h5>
                    <h3><?php echo $totalOrders; ?></h3>
                    <p>Total Orders</p>
                </div>
            </div></a> 
        </div>

        <!-- Pending Orders -->
        <div class="col-md-4">
            <a href="listoforders.php"><div class="card text-white bg-warning mb-3 shadow crd">
                <div class="card-body">
                    <h5 class="card-title">Pending</h5>
                    <h3><?php echo $pendingOrders; ?></h3>
                    <p>Pending Orders</p>
                </div>
            </div></a>
        </div>  
</div>
</div>

<?php require_once("includes/footer.php"); ?>

</body>
</html>
