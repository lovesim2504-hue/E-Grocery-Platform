<?php
// sleep(1);
if(isset($_POST['pname']))
{
    $name = htmlspecialchars($_POST['pname']);
    echo "Welcome, $name";
}
else
{
    echo "No name received";
}
?>