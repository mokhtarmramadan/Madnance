<?php
$HOSTNAME='localhost';
$USERNAME='root';
$PASSWORD='';
$DATABASE='madnance';

$connectdb = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);

if ($connectdb)
{
    echo "Connected";
}
else{
    echo "Not";
};
?>