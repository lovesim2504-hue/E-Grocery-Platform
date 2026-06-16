<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activate Account</title>
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
            <h1>Activate Account</h1>
        <?php
        ini_set('log_errors', 1);
        ini_set('error_log', __DIR__ . '/custom_php_error.log'); // File in same folder as this script
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $conn = null;
        try 
        {
            require_once("includes/vars.php");
            $conn = new mysqli(dbhost,dbuname,dbpass,dbname);
            $token=$_GET["token"];
            $q = "update register set actstatus=1 where acttoken='$token'";
            $conn->query($q);
            $rescount=$conn->affected_rows;//1 or 0
            if($rescount==1)
            {
                print "<h2>Account activated successfully";
            }
            else
            {
                print "Problem while activating account, try again";
            }
        } 
        catch (Exception $e) 
        {
            error_log("Database error: " . $e->getMessage());
            print "An error occurred during login. Please try again later.";
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