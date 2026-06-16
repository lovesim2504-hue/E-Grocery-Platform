<?php
    sleep(3);
    $catid=$_POST["cid"];
    require_once("../includes/vars.php");
    $conn = null;
    $conn = mysqli_connect(dbhost,dbuname,dbpass,dbname);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/custom_php_error.log'); // File in same folder as this script
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try 
    {
        $q = "select * from subcategory where catid='$catid'";
        $result = mysqli_query($conn, $q);
        $rescount=mysqli_affected_rows($conn);
        if($rescount==0)
        {
            print "<option value=''>No sub category available<option>";
        }
        else
        {
            print "<option value=''>Choose</option>";
            while($resarr=mysqli_fetch_array($result))
            {
                print "<option value='$resarr[0]'>$resarr[1]</option>";
            }
        }
    } 
    catch (Exception $e) 
    {
        error_log("Database error: " . $e->getMessage());
        print "An error occurred. Please try again later.";
    } 
    finally // it always run, even if there is error in try block or if there is no error
    {
        if ($conn) 
        {
            mysqli_close($conn);
        }
    }
?>