<?php
function setLeaveValue($id, $key, $temp)
{
    global $con;
    $now = "UPDATE leave_temp SET $key ='$temp' WHERE chat_id='$id'";
    mysqli_query($con, $now);
}
