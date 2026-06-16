<?php
session_start();
  require_once("includes/vars.php");
  ini_set('log_errors', 1);
  ini_set('error_log', __DIR__ . '/custom_php_error.log'); // File in same folder as this script
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
  $conn = null;
  try 
  {
      $pid=$_GET["pid"];
      $conn = mysqli_connect(dbhost,dbuname,dbpass,dbname);
      $q = "select * from product where productid='$pid'";
      $result = mysqli_query($conn, $q);//$result is an object of mysqli_result class
      $rescount=mysqli_affected_rows($conn);//1 or 0

      if($rescount==0)
      {
          $msg="No details found";
      }
      else
      {
          $resarr=mysqli_fetch_array($result);
          $disamt=($resarr['rate']*$resarr['discount'])/100;
          $remamt=$resarr['rate']-$disamt;
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

<?php


if(isset($_POST["btn"]))
{
  if(isset($_SESSION["un"]))
  {
    require_once("includes/vars.php");
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/custom_php_error.log'); // File in same folder as this script
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = null;
    try 
    {
        $qt=$_POST["qty"];
        $tc=$remamt*$qt;
        $uname=$_SESSION["un"];
        $conn = mysqli_connect(dbhost,dbuname,dbpass,dbname);
        $q = "insert into cart(prodid,prodname,rate,qty,totalcost,picture,username) values('$pid','$resarr[prodname]','$remamt','$qt','$tc','$resarr[picture]','$uname')";
        mysqli_query($conn, $q);//$result is an object of mysqli_result class
        $rescount=mysqli_affected_rows($conn);//1 or 0

        if($rescount==1)
        {
            header("location:cart.php");
        }
        else
        {
           $msg="Error while adding to cart, try again";
        }
        
    } 
    catch (Exception $e) 
    {
        error_log("Database error: " . $e->getMessage());
        $msg="An error occurred during saving cart. Please try again later.";
    } 
    finally // it always run, even if there is error in try block or if there is no error
    {
        if ($conn) 
        {
            mysqli_close($conn);
        }
    }
  }
  else
  {
    header("location:login.php");
  }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
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
           <div class="col-md-12 product-price1">
				<div class="col-md-5 single-top">	
			<div class="flexslider">
  <ul class="slides">
    <li data-thumb="uploads/<?php print $resarr['picture']; ?>">
      <img src="uploads/<?php print $resarr['picture']; ?>"/>
    </li>
  
  </ul>
</div>
<!-- FlexSlider -->
  <script defer src="js/jquery.flexslider.js"></script>
<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />

<script>
// Can also be used with $(document).ready()
$(window).load(function() {
  $('.flexslider').flexslider({
    animation: "slide",
    controlNav: "thumbnails"
  });
});
</script>
					</div>	
					<div class="col-md-7 single-top-in simpleCart_shelfItem">
						<div class="single-para ">
						<h4><?php print $resarr['prodname']; ?></h4>
							<br/>
							
							<h5 class="item_price">
                                <?php print "₹$remamt/-";?>&nbsp;&nbsp;&nbsp;
                                <strike><?php print "₹$resarr[rate]/-";?></strike>
                            </h5>
							<p><?php print $resarr['description']; ?></p>
              
              <?php
              if($resarr[7]>0)
              {
              ?>
              <form name="form1" method="post">
							<div class="available">
								<ul>
									<li>Qty
									<select name="qty">
                    <option value="">Choose Quantity</option>
                    <?php
                    if($resarr[7]>10)
                    {
                      for($x=1;$x<=10;$x++)
                      {
                        print "<option>$x<option>";
                      }
                    }
                    else
                    {
                      for($x=1;$x<=$resarr[7];$x++)
                      {
                        print "<option>$x<option>";
                      }
                    }
                    ?>
									</select></li>
								
								<div class="clearfix"> </div>
							</ul>
						</div>
							
							<button type="submit" name="btn" class="btn btn-primary">ADD TO CART</button><br/><br/>
              <?php
              if(isset($msg))
              {
                print $msg;
              }
              ?>
							</form>

            <?php
            }
            else
            {
              print "<h3>Out of Stock</h3><br/><br/>";
            }
            ?>

						</div>
					</div>
				<div class="clearfix"> </div>
			<!---->
</div>
            
        </div>

    </div>


    <?php
    require_once("includes/footer.php");
    ?>
</body>

</html>