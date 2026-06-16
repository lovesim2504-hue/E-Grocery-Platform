<?php
if(isset($_GET["email"]))
{
    require_once("../includes/vars.php");
	$email=$_GET["email"];
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/custom_php_error.log'); // File in same folder as this script
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = null;
    try 
    {
       	$conn = new mysqli(dbhost,dbuname,dbpass,dbname);
        $q = "select * from register where username='$email'";
        mysqli_query($conn,$q);//here $result is an object of mysqli_result class object
        $rescount=mysqli_affected_rows($conn);//1 or 0
        if($rescount==1)
        {
            print "Email already exist";
        }
        else
        {
           print "User not signed up with tis email ,you ca create account";
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
           $conn->close();
        }
    }
}
?>