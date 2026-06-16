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
        <h1>List of Members</h1>
        <?php
        require_once("includes/vars.php");
        ini_set('log_errors', 1);
        ini_set('error_log', __DIR__ . '/custom_php_error.log');
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $conn = null;

        try {
            $conn = new mysqli(dbhost, dbuname, dbpass, dbname);

            $limit = 2; 
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            if ($page < 1) $page = 1;
            $offset = ($page - 1) * $limit;
            $countQ = "SELECT COUNT(*) AS total FROM register";
            $countRes = $conn->query($countQ);
            $totalRecords = $countRes->fetch_assoc()['total'];
            $totalPages = ceil($totalRecords / $limit);
            $q = "SELECT * FROM register LIMIT $offset, $limit";
            $result = $conn->query($q);
            $rescount = $result->num_rows;

            if ($rescount == 0) {
                print "No members found";
            } else {
                print "<table class='table table-striped'>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Username</th>
                            <th>Delete</th>
                        </tr>";
                while ($resarr = $result->fetch_assoc()) {
                    print "<tr>
                            <td>{$resarr['Name']}</td>
                            <td>{$resarr['Phone']}</td>
                            <td>{$resarr['Username']}</td>
                            <td><a class='delbtn' href='#' uname='{$resarr['Username']}'>Delete</a></td>
                        </tr>";
                }
                print "</table>";

                if ($totalPages > 1) {
                    echo "<ul class='pagination'>";

                    if ($page > 1) {
                        echo "<li><a href='?page=" . ($page - 1) . "'>Prev</a></li>";
                    }
                    $range = 2; 
                    for ($i = max(1, $page - $range); $i <= min($totalPages, $page + $range); $i++) 
                    {
                        $active = ($i == $page) ? "style='background:#007bff;color:#fff;padding:5px 10px;border-radius:4px;'" : "";
                        echo "<li><a href='?page=$i' $active>$i</a></li>";
                    }

                    if ($page < $totalPages) 
                    {
                        echo "<li><a href='?page=" . ($page + 1) . "'>Next</a></li>";
                    }

                    echo "</ul>";
                }
            }
        } 
        catch (Exception $e) 
        {
            error_log("Database error: " . $e->getMessage());
            $msg = "An error occurred during fetching records. Please try again later.";
            echo "<p>$msg</p>";
        } 
        finally 
        {
            if ($conn) {
                $conn->close();
            }
        }
        ?>
    </div>
</div>

<style>
.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin-top: 15px;
}
.pagination li {
    margin: 0 5px;
}
.pagination a {
    display: block;
    padding: 6px 12px;
    border: 1px solid #ccc;
    color: #333;
    text-decoration: none;
}
.pagination a:hover {
    background: #f2f2f2;
}
</style>



    <?php
    require_once("includes/footer.php");
    ?>

<script>
$(document).ready(function() 
{
    $(".delbtn").click(function(e) 
    {
        e.preventDefault();
        if(confirm("Are you sure you want to delete?"))
        {
            var username = $(this).attr("uname");
            var td=$(this).parent();
            var tr=$(this).parent().parent();
            $.ajax(
            {
                url: "ajaxfiles/delmembers.php",   // PHP file
                type: "GET",
                data: {un:username},
                beforeSend: function() 
                {
                    loader = $("<img src='images/preloader.gif' height='32'>");
                    td.append(loader)
                },
                success: function(response) 
                {
                    if(response==="1")
                    {
                        tr.css({'backgroundColor':'#ffc0c0ff'});
                        tr.fadeOut(1000);
                    }
                    else
                    {
                        alert(" Error while processing request.");
                    }
                },
                error: function(xhr, status, error) 
                {
                    alert(" Error while processing request.");
                },
                complete: function() {
    
                    if (loader) loader.remove();
                }
            });
        }
    });
});
</script>
</body>

</html>