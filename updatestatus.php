<?php
if(isset($_POST["btn"]))
{
    require_once("includes/vars.php");
	$oid=$_GET["oid"];
    $newstatus=$_POST["newst"];
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/custom_php_error.log'); // File in same folder as this script
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = null;
    try 
    {
        $conn = mysqli_connect(dbhost,dbuname,dbpass,dbname);
        $q = "update ordertable set status='$newstatus' where orderid=$oid";
        mysqli_query($conn, $q);//$result is an object of mysqli_result class
        $rescount=mysqli_affected_rows($conn);//1 or 0

        if($rescount==1)
        {
           header("location:listoforders.php");
        }
        else
        {
            $msg="Error while updating status";
        }
        
    } 
    catch (Exception $e) 
    {
        error_log("error: " . $e->getMessage());
        $msg="Error while updating status. Please try again later.";
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
    <title>Update Status</title>
    <?php
    require_once("includes/headlink.php");
    ?>
</head>

<body>
    <?php
    require_once("includes/adminheader.php");
    ?>

    <div class="container">
        <div class="account">
            <h1>Update Status</h1>
            <div class="account-pass">
                <div class="col-md-8 account-top">
                    <form name="form1" method="post">
                        <div>
                            <span>Current Status</span>
                            <?php print $_GET["currst"]; ?>
                        </div>
                        <div>
                            <span>Choose New Status</span>
                            <select name="newst">
                                <option value="">Choose</option>
                                <option>Confirmed</option>
                                <option>Shipped</option>
                                <option>In-Transit</option>
                                <option>Out for delivery</option>
                                <option>Delivered</option>
                                <option>Cancelled</option>
                                <option>Returned</option>
                            </select>
                        </div>

                        <input type="submit" name="btn" value="Update">
                        <?php
                        if(isset($msg))
                        {
                            print $msg;
                        }
                        ?>
                    </form>
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