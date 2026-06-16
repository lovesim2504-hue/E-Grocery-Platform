<?php
session_start();
if(isset($_POST["btn"]))
{
    require_once("includes/vars.php");
    $un=$_SESSION["un"];
	$addr=$_POST["addr"];
    $pmode="Cash on Delivery";
    $status="Order received, processing";
	$orderamt=$_SESSION["tbill"];
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/custom_php_error.log'); // File in same folder as this script
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = null;
    try 
    {
        $conn = mysqli_connect(dbhost,dbuname,dbpass,dbname);
        $q = "insert into ordertable(username,address,pmode,status,orderamount) values('$un','$addr','$pmode','$status','$orderamt')";
        mysqli_query($conn, $q);
        $rescount=mysqli_affected_rows($conn);//1 or 0

        if($rescount==1)
        {
           header("location:ordersummary.php");
        }
        else
        {
            $msg="Problem while placing the order, try again";
        }
    } 
    catch (Exception $e) 
    {
        error_log("Database error: " . $e->getMessage());
        $msg="An error occurred during login. Please try again later.";
    } 
    finally // it always run, even if there is error in try block or if there is no error
    {
        if ($conn) 
        {
            mysqli_close($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
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
            <h1>Checkout</h1>
            <div class="account-pass">
                <div class="col-md-8 account-top">
                    <form name="form1" method="post">
                        <div>
                            <span>Shipping Address</span>
                            <textarea name="addr"></textarea>
                        </div>
                        <div>
                            <span>Payment Mode</span>
                            Cash on delivery
                        </div>
                        <input type="submit" name="btn" value="Checkout">
                        <?php
                        if(isset($msg))
                        {
                            print $msg;
                        }
                        ?>
                    </form>
                </div>
                <div class="col-md-4 left-account ">
                    <a href="single.html"><img class="img-responsive " src="images/s1.jpg" alt=""></a>
                    <div class="five">
                        <h2>Checkout </h2><span>& place order</span>
                    </div>
                    
                    <div class="clearfix"> </div>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>

    </div>


    <?php
    require_once("includes/footer.php");
    ?>
</body>

</html>