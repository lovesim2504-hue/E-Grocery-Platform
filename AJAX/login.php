<?php
session_start();

require_once("../includes/vars.php");
$email=$_POST["uname"];
$passw=$_POST["pass"];
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/custom_php_error.log'); // File in same folder as this script
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = null;
try 
{
    $conn = new mysqli(dbhost,dbuname,dbpass,dbname);
    $q = "select * from register where username='$email'";
    $result=$conn->query($q);//here $result is an object of mysqli_result class object
    $rescount=$result->num_rows;//1 or 0
    if($rescount==1)
    {
        $resarr= $result->fetch_array();
        if(password_verify($passw,$resarr['Password']))
        {   
            if($resarr['Actstatus']==1)
            {        
                $_SESSION["pname"] = $resarr[0];//storing person name into the session
                $_SESSION["un"] = $resarr[2];//storing person username into the session
                $_SESSION["utype"] = $resarr['UserType'];//storing person usertype into the session

               

                if(isset($_POST['rembme']))
                {
                    $cookiedata = json_encode(["un" => $resarr[2], "pn" =>$resarr[0], "utype"=>$resarr['UserType']]);
                    setcookie("ucookie", urlencode($cookiedata), time()+60*60*24*15, "/");
                }

                if($resarr['UserType']==="admin")
                {
                    print "admin";
                }
                else if($resarr['UserType']==="normal")
                {
                   print "user";
                }
            }
            else
            {
                print "Account not activated. Please check your email to activate your account";
            }
        }
        else
        {
            print "Incorrect Password";
        }
    }
    else
    {
        print "Incorrect Username";
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