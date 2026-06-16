<?php
if(isset($_POST["uname"]))
{
    require_once("../includes/vars.php");
	$email=$_POST["uname"];
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/custom_php_error.log'); // File in same folder as this script
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = null;
    try 
    {
        $conn =new mysqli(dbhost,dbuname,dbpass,dbname);
        $q = "select * from register where username='$email'";
        $result = $conn->query( $q);//$result is an object of mysqli_result class
        $rescount=mysqli_affected_rows($conn);//1 or 0

        if($rescount==1)
        {
            $resarr=mysqli_fetch_array($result);//hee we are converting an object to an array
        }
        else
        {
            $msg="Incorrect Username";
        }
        
    } 
    catch (Exception $e) 
    {
        error_log("Database error: " . $e->getMessage());
        $msg="An error occurred during registration. Please try again later.";
    } 
    finally // it always run, even if there is error in try block or if there is no error
    {
        if ($conn) 
        {
         $conn->close();
        }
    }
}
?>