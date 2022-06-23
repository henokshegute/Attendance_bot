<?php
function setShiftValue($id, $key, $temp)
{
    global $con;
    $now = "UPDATE shift_temp SET $key ='$temp' WHERE chat_id='$id'";
    mysqli_query($con, $now);
}