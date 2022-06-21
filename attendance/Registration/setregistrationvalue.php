<?php
function setUserValues($id, $key, $temp)
{
    global $con;
    $now = "UPDATE user_temp SET $key ='$temp' WHERE chat_id='$id'";
    mysqli_query($con, $now);
}