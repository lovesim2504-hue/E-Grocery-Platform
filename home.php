<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <?php
    require_once("includes/headlink.php");
    ?>
</head>

<body>
    <?php
    require_once("includes/header.php");
    ?>

   <div class="banner">
		
			  <script src="js/responsiveslides.min.js"></script>
  <script>
    $(function () {
      $("#slider").responsiveSlides({
      	auto: true,
      	nav: true,
      	speed: 500,
        namespace: "callbacks",
        pager: true,
      });
    });
  </script>
			<div  id="top" class="callbacks_container">
			<ul class="rslides" id="slider">
    <li style="background-image:url('images/slide1.jpg');">
        <div class="banner-text">
            <h3>Lorem Ipsum is not simply dummy</h3>
            <p>
                Contrary to popular belief, Lorem Ipsum is not simply random text.
            </p>
            <a href="single.html">Learn More</a>
        </div>
    </li>

    <li style="background-image:url('images/slide2.png');">
        <div class="banner-text">
            <h3>There are many variations</h3>
            <p>
                It has roots in a piece of classical Latin literature.
            </p>
            <a href="single.html">Learn More</a>
        </div>
    </li>

    <li style="background-image:url('images/slide3.png');">
        <div class="banner-text">
            <h3>Sed ut perspiciatis unde omnis</h3>
            <p>
                Richard McClintock, a Latin professor discovered the source.
            </p>
            <a href="single.html">Learn More</a>
        </div>
    </li>
</ul>

		</div>

	</div>
	</div>

     <div class="container">
        <div class="account">
            <h1>Featured Products</h1>
            <div class="col-md-12 product1">
				<div class=" bottom-product">
					
            <?php
            require_once("includes/vars.php");
            ini_set('log_errors', 1);
            ini_set('error_log', __DIR__ . '/custom_php_error.log'); // File in same folder as this script
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $conn = null;
            try 
            {
                $conn = mysqli_connect(dbhost,dbuname,dbpass,dbname);
                $q = "select * from product where featured='yes'";
                $result = mysqli_query($conn, $q);//$result is an object of mysqli_result class
                $rescount=mysqli_affected_rows($conn);//1 or 0

                if($rescount==0)
                {
                    print "No products found";
                }
                else
                {
                    while($resarr=mysqli_fetch_array($result))
                    {
                        print "<div class='col-md-4 bottom-cd simpleCart_shelfItem'>
						<div class='product-at '>
						<a href='details.php?pid=$resarr[0]'>
                            <img class='img-responsive' src='uploads/$resarr[9]' height='75' alt=''>
                            <p class='tun'>$resarr[3]</p>
						</a>	
						</div>
					    </div>";
                    }
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

                 
					
					<div class="clearfix"> </div>
				</div>
				
			</div>
            
        </div>

    </div>

    <div class="container">
        <div class="account">
            <h1>Latest Products</h1>
            <div class="col-md-12 product1">
				<div class=" bottom-product">
					
            <?php
            require_once("includes/vars.php");
            ini_set('log_errors', 1);
            ini_set('error_log', __DIR__ . '/custom_php_error.log'); // File in same folder as this script
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $conn = null;
            try 
            {
                $conn = mysqli_connect(dbhost,dbuname,dbpass,dbname);
                $q = "select * from product order by addedon desc limit 6";
                $result = mysqli_query($conn, $q);//$result is an object of mysqli_result class
                $rescount=mysqli_affected_rows($conn);//1 or 0

                if($rescount==0)
                {
                    print "No products found";
                }
                else
                {
                    while($resarr=mysqli_fetch_array($result))
                    {
                        print "<div class='col-md-4 bottom-cd simpleCart_shelfItem'>
						<div class='product-at '>
						<a href='details.php?pid=$resarr[0]'>
                            <img class='img-responsive' src='uploads/$resarr[9]' height='75' alt=''>
                            <p class='tun'>$resarr[3]</p>
						</a>	
						</div>
					    </div>";
                    }
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

                 
					
					<div class="clearfix"> </div>
				</div>
				
			</div>
            
        </div>

    </div>


    <?php
    require_once("includes/footer.php");
    ?>
</body>

</html>