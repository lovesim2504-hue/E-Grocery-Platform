<?php
session_start();

// Ensure the session key you expect is used. I use 'un' here — change to your actual session key.
if (!isset($_SESSION['un'])) {
    header('Location: login.php');
    exit;
}

require_once("includes/vars.php"); // dbhost, dbuname, dbpass, dbname

$msg = '';

if (isset($_POST['btn'])) {
    $currp = $_POST['currpass'] ?? '';
    $newp  = $_POST['newpass'] ?? '';
    $cnewp = $_POST['cnewpass'] ?? '';
    $uname = $_SESSION['un'];

    if ($newp !== $cnewp) {
        $msg = "New Password and Confirm new password does not match";
    } elseif (strlen($newp) < 6) {
        $msg = "New password must be at least 6 characters";
    } else {
        // connect
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $conn = null;
        try {
            $conn = mysqli_connect(dbhost, dbuname, dbpass, dbname);
            // 1) fetch stored hash for this user
            $sql = "SELECT password FROM register WHERE username = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 's', $uname);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $storedHash);
            if (!mysqli_stmt_fetch($stmt)) {
                // no such user
                $msg = "User not found or session invalid.";
                mysqli_stmt_close($stmt);
            } else {
                mysqli_stmt_close($stmt);
                // 2) verify current password
                if (!password_verify($currp, $storedHash)) {
                    $msg = "Current password is incorrect.";
                } else {
                    // 3) update with new hashed password
                    $newHash = password_hash($newp, PASSWORD_DEFAULT);
                    $updateSql = "UPDATE register SET password = ? WHERE username = ?";
                    $ustmt = mysqli_prepare($conn, $updateSql);
                    mysqli_stmt_bind_param($ustmt, 'ss', $newHash, $uname);
                    mysqli_stmt_execute($ustmt);
                    $affected = mysqli_stmt_affected_rows($ustmt);
                    mysqli_stmt_close($ustmt);

                    if ($affected === 1) {
                        $msg = "Password changed successfully";
                    } else {
                        // Could be 0 if same hash (rare) — still handle
                        $msg = "Password not changed. Try a different password.";
                    }
                }
            }
        } catch (Exception $e) {
            error_log("DB error: " . $e->getMessage());
            $msg = "An error occurred. Please try again later.";
        } finally {
            if ($conn) mysqli_close($conn);
        }
    }
}
?>
<!-- HTML form same as yours, echo $msg where needed -->


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
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
            <h1>Change Password</h1>
            <div class="account-pass">
                <div class="col-md-8 account-top">
                    <form name="form1" method="post">
                        <div>
                            <span>Current Password</span>
                            <input type="password" name="currpass">
                        </div>
                        <div>
                            <span>New Password</span>
                            <input type="password" name="newpass">
                        </div>
                        <div>
                            <span>Confirm New Password</span>
                            <input type="password" name="cnewpass">
                        </div>
                        <input type="submit" name="btn" value="Change Password">
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