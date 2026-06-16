<?php
if(isset($_POST["btn"]))
{
    require_once("includes/vars.php");
    
    $conn = null;
    $conn = mysqli_connect(dbhost,dbuname,dbpass,dbname);
    $cid=$_POST["cat"];
	$scname= mysqli_real_escape_string($conn,$_POST["scatname"]);
	$errcode=$_FILES["scatpic"]["error"];
    if($errcode==0)//admin has chosen a file and it has been uploaded successfully to temp folder
    {
        $t=time();
        $tname=$_FILES["scatpic"]["tmp_name"];
        $fn=$t.$_FILES["scatpic"]["name"];//543543245abc.jpg
        if(!move_uploaded_file($tname,"uploads/$fn"))
        {
             $fn="nopic.jpeg";//default image name already stored in uploads folder
        }
    }
    else
    {
        $fn="nopic.jpeg";//default image name already stored in uploads folder
    }

    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/custom_php_error.log'); // File in same folder as this script
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    
    try 
    {
        $q = "insert into subcategory(catid,subcatname,subcatpic) values('$cid','$scname','$fn')";
        mysqli_query($conn, $q);
        $qcount=mysqli_affected_rows($conn);//1 or 0
        if($qcount==1)
        {
            $msg="Sub Category added successfully";
        }
        else
        {
            $msg="Sub Category not added";
        }
        
    } 
    catch (Exception $e) 
    {
        error_log("Database error: " . $e->getMessage());
        $msg="An error occurred. Please try again later.";
    } 
    finally // it always run, even if there is error in try block or if there is no error
    {
        if ($conn) 
        {
            mysqli_close($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sub Category</title>
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
            <h1>Manage Sub Category</h1>
            <div class="account-pass">
                <div class="col-md-8 account-top">
                    <form name="form1" method="post" enctype="multipart/form-data">
                        <div>
                            <span>Choose Category</span>
                            <select name="cat" required>
                                <option value="">Choose</option>
                                <?php
                                    require_once("includes/vars.php");
                                    $conn = null;
                                     $conn = mysqli_connect(dbhost,dbuname,dbpass,dbname);
                                    ini_set('log_errors', 1);
                                    ini_set('error_log', __DIR__ . '/custom_php_error.log'); // File in same folder as this script
                                    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                                    try 
                                    {
                                        $q = "select * from category";
                                        $result = mysqli_query($conn, $q);
                                        $rescount=mysqli_affected_rows($conn);
                                        if($rescount==0)
                                        {
                                            print "<option value=''>No category available<option>";
                                        }
                                        else
                                        {
                                            while($resarr=mysqli_fetch_array($result))
                                            {
                                                print "<option value='$resarr[0]'>$resarr[1]</option>";
                                            }
                                        }
                                        
                                    } 
                                    catch (Exception $e) 
                                    {
                                        error_log("Database error: " . $e->getMessage());
                                        $msg="An error occurred. Please try again later.";
                                    } 
                                    finally // it always run, even if there is error in try block or if there is no error
                                    {
                                        if ($conn) 
                                        {
                                            mysqli_close($conn);
                                        }
                                    }

                                ?>
                            </select>
                        </div>

                        <div>
                            <span>Sub Category Name</span>
                            <input type="text" name="scatname">
                        </div>
                        <div>
                            <span>Sub Category Picture</span>
                            <input type="file" name="scatpic">
                        </div>


                        <input type="submit" name="btn" value="Add">
                        <?php
                        if(isset($msg))
                        {
                            print $msg;
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
</body>

</html>