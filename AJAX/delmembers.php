<?php
require_once("../includes/vars.php");
$uname=$_GET["un"];
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/custom_php_error.log'); 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = null;
try 
{
    $conn = new mysqli(dbhost,dbuname,dbpass,dbname);
    $q = "delete from register where username='$uname'";
    $success=$conn->query($q);
  
    if($success==true)
    {
      print true;
    }
    else
    {
    print false;
    }
    
} 
catch (Exception $e) 
{
    error_log("Database error: " . $e->getMessage());
    print false;
} 
finally 
{
    if ($conn) 
    {
       $conn->close();
    }
}
?>
