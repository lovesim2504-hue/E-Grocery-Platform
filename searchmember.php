<?php
if(isset($_POST["btn"]))
{
    require_once("includes/vars.php");
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


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Member</title>
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
            <h1>Search Member</h1>
            <div class="account-pass">
                <div class="col-md-8 account-top">
                    <form name="form1" method="post">
                        <div>
                            <span>Email</span>
                            <input type="text" name="uname" id="mail">
                        </div>

                        <input type="submit" name="btn" id="btn" value="Search">
                        <div class="info"></div>
                        <?php
                        if(isset($msg))
                        {
                            print $msg;
                        }
                        if(isset($resarr))
                        {
                             print "<br/><b>Name:-</b> $resarr[0]<br/>
                             <b>Phone:-</b> $resarr[1]<br/>";
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
<!-- <script>
  	$(document).ready(function()
		{
			$("#btn").click(function()
			{
				var name=$(this).val();
				if(name === "")
				{
					$("#info").html("Please enter a name");
					return;
				}

				$.ajax({
					url: "ajaxfiles/serchmbr.php",  //php file
					type: "POST",
					data: {uname:email},
					beforeSend: function()
					{
						$("#info").html("Loading....");
					},
					success: function(response)
					{
						$("#info").html(response);
					},
					error: function(xhr, status, error)
					{
						$("#info").html("Error while processing request");
						

					}
				})
			});
		});	
	</script> -->
</body>

</html>