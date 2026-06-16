<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Orders</title>
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
            <h1>List of Orders</h1>
            <?php
            require_once("includes/vars.php");
            ini_set('log_errors', 1);
            ini_set('error_log', __DIR__ . '/custom_php_error.log'); // File in same folder as this script
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $conn = null;
            try 
            {
                $conn = mysqli_connect(dbhost,dbuname,dbpass,dbname);
                $q = "select * from ordertable order by orderdt desc";
                $result = mysqli_query($conn, $q);//$result is an object of mysqli_result class
                $rescount=mysqli_affected_rows($conn);//1 or 0

                if($rescount==0)
                {
                    print "No orders found";
                }
                else
                {
                    print "<table class='table table-striped'>
                    <tr>
                    <th>Order ID</th>
                    <th>Username</th>
                    <th>Address</th>
                    <th>Amount</th>
                     <th>Status</th>
                    <th>Update Status</th>
                    </tr>";
                    while($resarr=mysqli_fetch_array($result))
                    {
                        print "<tr>
                        <td><a href='orderitems.php?oid=$resarr[orderid]'>$resarr[orderid]</a></td>
                        <td>$resarr[username]</td>
                        <td>$resarr[address]</td>
                        <td>$resarr[orderamount]</td>
                        <td>$resarr[status]</td>
                        <td><a href='updatestatus.php?oid=$resarr[orderid]&currst=$resarr[status]'>Update Status</a></td>
                        </tr>";
                    }
                    print "</table>
                    $rescount order(s) found";
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