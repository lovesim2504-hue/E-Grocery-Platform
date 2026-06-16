<?php

if(isset($_POST["btn"]))
{
	require_once("includes/vars.php");
	$pn=$_POST["pname"];
	$phone=$_POST["ph"];
	$email=$_POST["em"];
	$passw=$_POST["pass"];
	$cpassw=$_POST["cpass"];
	if($passw===$cpassw)
	{
		ini_set('log_errors', 1);
		ini_set('error_log', __DIR__ . '/custom_php_error.log'); // File in same folder as this script
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		$conn = null;
		try 
		{
			$conn = new mysqli(dbhost,dbuname,dbpass,dbname);
			if ($conn->connect_error) 
			{
  				die("Connection failed: " . $conn->connect_error);
			}
			$encpass=password_hash($passw, PASSWORD_DEFAULT);
			$activation_token = bin2hex(random_bytes(32));

			$stmt = $conn->prepare("INSERT INTO register(name,phone,username,password,acttoken) VALUES (?,?,?,?,?)");
			$stmt->bind_param("sssss", $pn, $phone, $email,$encpass,$activation_token);

			$success=$stmt->execute();

			if($success==true)
			{
				try 
				{
					header("location:thanks.php");
				} 
				catch (Exception $e) //$e is object of class Exception
				{
					echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
				}
			}
			else
			{
				$msg="An error occurred during registration. Please try again later.";
			}
		} 
		catch (Exception $e) 
		{
			error_log("Database error: " . $e->getMessage());
			$msg="An error occurred during registration. Please try again later.";
		} 
		finally // it always run, even if there is error in try block or if there is no error
		{
			$stmt->close();
			$conn->close();
		}
	}
	else
	{
		$msg="Password and confirm password doesn';'t match";
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <?php
    require_once("includes/headlink.php");
    ?>
</head>

<body>
    <?php
    require_once("includes/header.php");
    ?>

<div class=" container">
<div class=" register">
	<h1>Register</h1>
		  	  <form name="form1" method="post"> 
				 <div class="col-md-6 register-top-grid">
					<h3>Personal infomation</h3>
					 <div>
						<span>Name</span>
						<input type="text" name="pname" id="txtbx"> 
						<div id="showname"></div>
					 </div>
					 <div>
						<span>Phone</span>
						<input type="number" name="ph"> 
					 </div>
					 <div>
						 <span>Email Address(Username)</span>
						 <input type="email" name="em" id="txtemail"> 
						 <div id="showavail"></div>
					 </div>
					 
					 </div>
				     <div class="col-md-6 register-bottom-grid">
						    <h3>Login information</h3>
							 <div>
								<span>Password</span>
								<input type="password" name="pass">
							 </div>
							 <div>
								<span>Confirm Password</span>
								<input type="password" name="cpass">
							 </div>
							 <input type="submit" value="Submit" name="btn">
							 <?php
							 if(isset($msg))
							 {
								print $msg;
							 }
							 ?>
					 </div>
					 <div class="clearfix"> </div>
				</form>
			</div>
</div>


    <?php
    require_once("includes/footer.php");
    ?>


	<script>
		$(document).ready(function()
		{
			$("#txtbx").blur(function()
			{
				var name=$(this).val().trim();
				if(name === "")
				{
					$("#showname").html("Please enter a name");
					return;
				}

				$.ajax({
					url: "ajaxfiles/showname.php",  //php file
					type: "POST",
					data: {pname:name},
					beforeSend: function()
					{
						$("#showname").html("Loading....");
					},
					success: function(response)
					{
						$("#showname").html(response);
					},
					error: function(xhr, status, error)
					{
						$("#showname").html("Error while processing request");
						$("#showname").html("Error while proessing request <br>" +
						"<b>Status:</b>" +status+"<br>"+
						"<b>Error:</b>" +error+"<br>"+
						"<b>Response:</b>" +xhr.responseText);
						

					}
				})
			});
		});
	
		$(document).ready(function()
		{
			$("#txtemail").blur(function()
			{
				var em=$(this).val().trim();
				if(em === "")
				{
					$("#showavail").html("Please enter a email");
					return;
				}

				$.ajax({
					url: "ajaxfiles/checkavail.php",  //php file
					type: "GET",
					data: {email:em},
					beforeSend: function()
					{
						$("#showavail").html("Loading....");
					},
					success: function(response)
					{
						$("#showavail").html(response);
						setTimeout(function()
						{
							$("#showavail").html("");
						} ,1000);
					},
					error: function(xhr, status, error)
					{
						$("#showavail").html("Error while processing request");
							setTimeout(function()
							{
								$("#showavail").html("");
							},1000)
					}
				})
			});
		});		
	</script>
</body>

</html>