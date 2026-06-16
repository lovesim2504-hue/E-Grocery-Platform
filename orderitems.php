<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Items</title>
    <?php
    require_once("includes/headlink.php");
    ?>
</head>

<body>
    <?php
    if($_SESSION["utype"]==="admin")
    {
        require_once("includes/adminheader.php");
    }
    else
    {
        require_once("includes/header.php");
    }
    ?>

    <div class="container">
        <div class="account">
            <h1>Order Items</h1>
            <?php
            $oid=$_GET["oid"];
            require_once("includes/vars.php");
            ini_set('log_errors', 1);
            ini_set('error_log', __DIR__ . '/custom_php_error.log'); // File in same folder as this script
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $conn = null;
            try 
            {
                $uname=$_SESSION["un"];
                $conn =new mysqli(dbhost,dbuname,dbpass,dbname);
                $q = "select * from orderitems where orderid='$oid'";
                $result =$conn->query($q);//$result is an object of mysqli_result class
                $rescount=mysqli_affected_rows($conn);//1 or 0

                if($rescount==0)
                {
                    print "No products found";
                }
                else
                {
                    print "<table class='table table-striped'>
                    <tr>
                    <th>Picture</th>
                    <th>Name</th>
                    <th>Rate</th>
                    <th>Qty</th>
                    <th>Total Cost</th>
                    </tr>";
                    $billamt=0;
                    while($resarr=mysqli_fetch_array($result))
                    {
                        print "<tr>
                        <td><img src='uploads/$resarr[picture]' height='75'></td>
                        <td>$resarr[prodname]</td>
                        <td>$resarr[rate]</td>
                        <td>$resarr[qty]</td>
                        <td>$resarr[totalcost]</td>
                        </tr>";
                        $billamt=$billamt+$resarr['totalcost'];
                    }
                    print "</table>
                    $rescount product(s) found<br/><br/>Total bill is Rs.$billamt/-";
                }
                
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
                    mysqli_close($conn);
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