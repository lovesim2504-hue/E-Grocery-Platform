<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    <?php
    require_once("includes/headlink.php");
    ?>
</head>

<body>
    <?php
    require_once("includes/header.php");
    ?>

    <div class="container">
        <div class="account">
            <h1>Order Summary</h1>
            <?php
            $uname=$_SESSION["un"];
            require_once("includes/vars.php");
            ini_set('log_errors', 1);
            ini_set('error_log', __DIR__ . '/custom_php_error.log'); // File in same folder as this script
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $conn = null;
            try 
            {
                $uname=$_SESSION["un"];
                $conn =new mysqli(dbhost,dbuname,dbpass,dbname);
                $q = "select * from ordertable where username='$uname' order by orderid desc limit 1";
                $result = $conn->query($q);//$result is an object of mysqli_result class
                $orderarr=mysqli_fetch_array(($result));
                print "Thanks for shopping on our website. Your order number is $orderarr[0]";
                
                $q = "select * from cart where username='$uname'";//here we will get latest order products
                $result = mysqli_query($conn, $q);//$result is an object of mysqli_result class

                while($cartarr=mysqli_fetch_array($result))
                {
                    $q = "insert into orderitems(prodid,prodname,rate,qty,totalcost,picture,orderid) values('$cartarr[1]','$cartarr[2]','$cartarr[3]','$cartarr[4]','$cartarr[5]','$cartarr[6]','$orderarr[0]')";
                    mysqli_query($conn, $q);

                    $q = "update product set stock=stock-$cartarr[4] where productid=$cartarr[1]";
                    mysqli_query($conn, $q);
                }
                $q = "delete from cart where username='$uname'";
                mysqli_query($conn, $q);
            } 
            catch (Exception $e) 
            {
                error_log("Database error: " . $e->getMessage());
                $msg="An error occurred during fetching records. Please try again later.";
            } 
            finally // it always run, even if there is error in try block or if there is no error
            {
                if ($conn) 
                {
                   $conn->close();
                }
            }
            ?>
        </div>

    </div>


    <?php
    require_once("includes/footer.php");
    ?>
</body>

</html>