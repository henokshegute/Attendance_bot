<?php
function totalHour($chat_id)
{
    global $botAPI;
    global $con;
    date_default_timezone_set('Africa/Addis_Ababa');
    $today = date('y-m-d');
    $timequery = "SELECT * From attendance_shit Where date='$today' AND chat_id='$chat_id'";
    $row = mysqli_query($con, $timequery);
    while ($ro = mysqli_fetch_array($row)) {
        $morning_in = $ro['morning_in'];
        $morning_out = $ro['morning_out'];
        $afternoon_in = $ro['afternoon_in'];
        $afternoon_out = $ro['afternoon_out'];
        $total_hour = $ro['total_hour'];
    }
    if ($morning_in === NULL && $afternoon_in === NULL) {
        $total_hour = "0";
        $updatequery = "UPDATE attendance_shit SET total_hour='$total_hour' Where chat_id='$chat_id' AND date='$today' ";
        mysqli_query($con, $updatequery);
    } else if ($morning_in == NULL || $afternoon_in == NULL) {
        if ($morning_in == NULL) {
            $a = strtotime($afternoon_out);
            $b = strtotime($afternoon_in);
            $total_hour = $a - $b;
            $t = gmdate("H:i:s", $total_hour);
            $updatequery = "UPDATE attendance_shit SET total_hour='$t' Where chat_id='$chat_id' AND date='$today'";
            mysqli_query($con, $updatequery);
        } else {
            $a = strtotime($morning_out);
            $b = strtotime($morning_in);
            $total_hour = $a - $b;
            $t = gmdate("H:i:s", $total_hour);
            $updatequery = "UPDATE attendance_shit SET total_hour='$t' Where chat_id='$chat_id' AND date= '$today'";
            mysqli_query($con, $updatequery);
        }
    } else {
        $a = strtotime($morning_out);
        $b = strtotime($morning_in);
        $c = strtotime($afternoon_out);
        $d = strtotime($afternoon_in);
        $Mtotal = $a - $b;
        echo "morn " . $Mtotal;
        $Atotal = $c - $d;
        echo "after " . $Atotal;
        $total_hour = $Mtotal + $Atotal;
        $t = gmdate("H:i:s", $total_hour);
        $updatequery = "UPDATE attendance_shit SET total_hour='$t' Where chat_id='$chat_id' AND date='$today'";
        mysqli_query($con, $updatequery);
    }
}