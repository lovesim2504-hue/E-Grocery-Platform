
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
            <h1>Account</h1>
            <div class="account-pass">
                <div class="col-md-8 account-top">
                    <form name="form1" id="form1" method="post">
                        <div>
                            <span>Email</span>
                            <input type="text" name="uname">
                        </div>
                        <div>
                            <span>Password</span>
                            <input type="password" name="pass">
                        </div>

                        <div>
                            <span></span>
                            <label><input type="checkbox" name="rembme">&nbsp;Remember Me</label>
                        </div>

                        <input type="submit" name="btn" value="Login">
                        <div id="loginresult"></div>
                    </form>
                </div>
                <div class="col-md-4 left-account ">
                    <a href="single.html"><img class="img-responsive " src="images/s1.jpg" alt=""></a>
                    <div class="five">
                        <h2>Login </h2><span>into Account</span>
                    </div>
                    <a href="register.php" class="create">Create an account</a>
                    <div class="clearfix"> </div>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>

    </div>


    <?php
    require_once("includes/footer.php");
    ?>

<script>
    $(document).ready(function() 
	{
	  $("#form1").submit(function(e) 
	  {
        e.preventDefault();
        $.ajax({
          url: "ajaxfiles/login.php",   // PHP file
          type: "POST",
          data: $(this).serialize(),
          beforeSend: function() 
		  {
            loader = $("<img src='images/Loading_icon.gif' height='32'>");
            $("#loginresult").html(loader);
          },
          success: function(response) 
		  {
            if($.trim(response)==="admin")
            {
                location.href="adminhome.php";
            }
            else if($.trim(response)==="user")
            {
                location.href="home.php";
            }
            else
            {
                $("#loginresult").html(response);
            }
          },
          error: function(xhr, status, error) 
		  {
			$("#loginresult").html("❌ Error while processing request.");
			setTimeout(function() 
			{
                $("#loginresult").fadeOut(); // clear error message after 10 seconds
            }, 10000);
          },
          complete: function() 
          {
                // ✅ always remove loader after success or error
                if (loader) loader.remove();
          }
        });
      });
    });
</script>

</body>

</html>