<?php
include "Db_connetion/connect.php";
include "Admin/adduser.php";
include "Admin/adminafternoonmenu.php";
include "Admin/adminmorningmenu.php";
include "Admin/approvalMenu.php";
include "Admin/listleaverequest.php";
include "Admin/listFieldRequests.php";
include "Admin/listregistrationrequest.php";
include "Admin/respondFieldwork.php";
include "Admin/respondleave.php";
include "Admin/setleaveconfirmation.php";
include "Admin/setvalueshift.php";
include "Common/checkinheckout.php";
include "Common/leaveconfirmation.php";
include "Common/menus.php";
include "Common/setvalue.php";
include "Common/totalhour.php";
include "Registration/dataconfirmtiom.php";
include "Registration/registrationConfirmation.php";
include "Registration/registrationmenu.php";
include "Registration/setregistrationvalue.php";
include "User/requestfieldwork.php";
include "User/userafternoonmenu.php";
include "User/usermorningmenu.php";
$update = json_decode(file_get_contents('php://input'), TRUE);
$botToken = "5490432130:AAFKwfIYrpygguMm3Q0omKdYfC6VM_Vxx2Q";
$botAPI = "https://api.telegram.org/bot" . $botToken;
$msg = $update['message']['text'];
$chat_id = $update['message']['from']['id'];
$latitude = $update['message']['location']['latitude'];
$longitude = $update['message']['location']['longitude'];
date_default_timezone_set('Africa/Addis_Ababa');
$checkinmorning = date('H:i');
//file_get_contents($botAPI."/sendMessage?text=$checkinmorning&chat_id=$chat_id&parse_mode=html");
//////////////////
$addQ = "select * from user_temp where chat_id='$chat_id'";
$qur = mysqli_query($con, $addQ);
$row_num = mysqli_num_rows($qur);
/////////////////
$lev = "select * from leave_temp where chat_id='$chat_id'";
$levQ = mysqli_query($con, $lev);
$lev_num = mysqli_num_rows($levQ);
//////////////////////////////
$shiftrow = "select * from shift_temp where chat_id='$chat_id'";
$shiftrowQ = mysqli_query($con, $shiftrow);
$shiftnum = mysqli_num_rows($shiftrowQ);
////////////////////////////////
$admin = "SELECT * FROM user WHERE role='admin' AND chat_id = '$chat_id' ";
$adminQ = mysqli_query($con, $admin);
$adminrow = mysqli_num_rows($adminQ);
///////////////////////////////
$field = $con->query("SELECT * FROM field_temp");
$fieldRow = mysqli_num_rows($field);
///////////////////////////////
$shift = $con->query("SELECT * FROM shift_table");
while ($ro = mysqli_fetch_array($shift)) {
    $Mstarttime = $ro['morning'];
    $Mendtime = $ro['lunch'];
    $Astarttime = $ro['afternoon'];
    $Aendtime = $ro['night'];
}

date_default_timezone_set('Africa/Addis_Ababa');
$timenow = date('H:i');
/////////////////////////////////
$count = 0;
while ($ad = mysqli_fetch_array($adminQ)) {
    $count += 1;
}
if ($count >= 1) {
    if ($msg == "/start") {
        if ($Mstarttime <= $timenow && $Mendtime >= $timenow) {

            $uname = $update['message']['from']['first_name'];
            $welcome = "Hello " . $uname . ", welcome to the B-agro attendance bot ";
            $welcome = rawurlencode($welcome);
            file_get_contents($botAPI . "/sendMessage?text=$welcome&chat_id=$chat_id&parse_mode=html");
            adminMorningMenu($chat_id);
        } else if ($Astarttime <= $timenow && $Aendtime >= $timenow) {
            $uname = $update['message']['from']['first_name'];
            $welcome = "Hello " . $uname . ", welcome to the B-agro attendance bot ";
            $welcome = rawurlencode($welcome);
            file_get_contents($botAPI . "/sendMessage?text=$welcome&chat_id=$chat_id&parse_mode=html");
            adminAfternoonMenu($chat_id);
        } else {
            adminEndSession($chat_id);
        }
    } else if ($update['message']['forward_from']) {

        disableForwarding($chat_id);
    } else if ($update['message']['location']) {
        if ($update['message']['reply_to_message']) {
            $startingRangeCompanyLatitude = 8.988425;
            $endingRangeCompanyLatitude = 8.988620;
            $startingRangeCompanyLongitude = 38.788910;
            $endingRangeCompanyLongitude = 38.789140;
            if (($startingRangeCompanyLatitude <= $latitude && $latitude <= $endingRangeCompanyLatitude) &&
                ($startingRangeCompanyLongitude <= $longitude && $longitude <= $endingRangeCompanyLongitude)
            ) {

                if ($Mstarttime <= $timenow && $Mendtime >= $timenow) {
                    morningchekincheckout($chat_id);
                } else if ($Astarttime <= $timenow && $Aendtime >= $timenow) {
                    afternoonchekincheckout($chat_id);
                }
            } else {
                file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=you are not in the location of the company &parse_mode=html");
                adminEndSession($chat_id);
            }
        } else {
            disableForwarding($chat_id);
        }
    } else if ($update['callback_query']['data']) {
        $chat_id = $update['callback_query']['from']['id'];
        $message_id =  $update['callback_query']['message']['message_id'];
        $season_data = $update['callback_query']['data'];
        list($first, $id) = explode(" ", $update['callback_query']['data']);
        if ($first == "u") {
            acceptAsUser($id, $chat_id, $message_id);
        } else if ($first == "a") {
            acceptAsAdmin($id, $chat_id, $message_id);
        } else if ($first == "d") {
            delete($id, $chat_id, $message_id);
        } else if ($first == "l") {
            acceptLeave($id, $chat_id, $message_id);
        } else if ($first == "n") {
            declineLeave($id, $chat_id, $message_id);
        } else if ($first == "f") {
            acceptFieldWork($id, $chat_id, $message_id);
        } else if ($first == "r") {
            declineFieldWork($id, $chat_id, $message_id);
        }
    }
}
if ($count < 1) {

    if ($msg == "/start") {

        $user = "SELECT * FROM user WHERE role='user' AND chat_id = '$chat_id'";
        $userQ = mysqli_query($con, $user);

        $count = 0;
        while ($ro = mysqli_fetch_array($userQ)) {
            $count += 1;
        }
        if ($count >= 1) {
            if ($Mstarttime <= $timenow && $Mendtime >= $timenow) {

                $uname = $update['message']['from']['first_name'];
                $welcome = "Hello " . $uname . ", welcome to the B-agro attendance bot";
                $welcome = rawurlencode($welcome);
                file_get_contents($botAPI . "/sendMessage?text=$welcome&chat_id=$chat_id&parse_mode=html");
                userMorningMenu($chat_id);
            } else if ($Astarttime <= $timenow && $Aendtime >= $timenow) {
                $uname = $update['message']['from']['first_name'];
                $welcome = "Hello " . $uname . ", welcome to the B-agro attendance bot";
                $welcome = rawurlencode($welcome);
                file_get_contents($botAPI . "/sendMessage?text=$welcome&chat_id=$chat_id&parse_mode=html");
                userAfternoonMenu($chat_id);
            } else {
                userEndSession($chat_id);
            }
        } else {
            register($chat_id);
        }
    } else if ($update['message']['forward_from']) {
        disableForwarding($chat_id);
    } else if ($update['message']['location']) {
        $startingRangeCompanyLatitude = 8.988425;
        $endingRangeCompanyLatitude = 8.988620;
        $startingRangeCompanyLongitude = 38.788910;
        $endingRangeCompanyLongitude = 38.789140;
        if (($startingRangeCompanyLatitude <= $latitude && $latitude <= $endingRangeCompanyLatitude) &&
            ($startingRangeCompanyLongitude <= $longitude && $longitude <= $endingRangeCompanyLongitude)
        ) {
            if ($Mstarttime <= $timenow && $Mendtime >= $timenow) {
                morningchekincheckout($chat_id);
            } else if ($Astarttime <= $timenow && $Aendtime >= $timenow) {
                afternoonchekincheckout($chat_id);
            }
        } else {
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=you are not in the location of the company &parse_mode=html");
            userEndSession($chat_id);
        }
    } else if ($update['callback_query']['data']) {
        $chat_id = $update['callback_query']['from']['id'];
        $message_id =  $update['callback_query']['message']['message_id'];
        $season_data = $update['callback_query']['data'];
        list($first, $id) = explode(" ", $update['callback_query']['data']);
        if ($first == "u") {
            acceptAsUser($id, $chat_id, $message_id);
        } else if ($first == "a") {
            acceptAsAdmin($id, $chat_id, $message_id);
        } else if ($first == "d") {
            delete($second, $chat_id, $message_id);
        } else if ($first == "l") {
            acceptLeave($id, $chat_id, $message_id);
        } else if ($first == "n") {
            declineLeave($id, $chat_id, $message_id);
        } else if ($first == "f") {
            acceptFieldWork($id, $chat_id, $message_id);
        } else if ($first == "r") {
            declineFieldWork($id, $chat_id, $message_id);
        }
    }
}
///////////////////////////////////////////////
////////MOrning///////////////////////////////   
/////////////////////////////////////////////////
if ($msg == "📝 Morning Checkin") {
    $keyboard = array(array(array("text" => "send location", "request_location" => true, "has_protected_content" => true,)));
    $reply = json_encode(array("keyboard" => $keyboard, "resize_keyboard" => true, "one_time_keyboard" => true));
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=confirm your location &reply_markup=" . $reply);
}
if ($msg == "Morning Checkin") {

    date_default_timezone_set('Africa/Addis_Ababa');
    $time = date("h:ia");
    $today = date('y-m-d');
    $employename = $con->query("SELECT name FROM user WHERE chat_id = '$chat_id'");
    while ($ro = mysqli_fetch_array($employename)) {
        $name = $ro['name'];
    }
    $checkk = "SELECT * FROM attendance_shit WHERE date ='$today' AND chat_id = '$chat_id' AND morning is not NULL";
    $qqq = mysqli_query($con, $checkk);
    $cou = 0;
    while ($ro = mysqli_fetch_array($qqq)) {
        $cou += 1;
    }
    if ($cou > 0) {

        $errormessage = "You have alrady signed morning attendance";
        file_get_contents($botAPI . "/sendMessage?text=$errormessage&chat_id=$chat_id&parse_mode=html");
        returnmenu($chat_id);
    } else {
        $inserQuery = "INSERT INTO attendance_shit(employee_name,morning,date,chat_id,morning_in,morning_out) VALUES('$name','Morning Checkin','$today','$chat_id','$time','$time')";
        mysqli_query($con, $inserQuery);
        file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=you have signed your attendance.At:" . " " . $time);
        totalHour($chat_id);
        returnmenu($chat_id);
    }
}
//////////////////////////////////////////////////////////////
//Afternooon//
////////////////////////////////////////////////////////////// 
if ($msg == "📝 Afternoon Checkin") {
    $keyboard = array(array(array("text" => "send location", "request_location" => true, "has_protected_content" => true,)));
    $reply = json_encode(array("keyboard" => $keyboard, "resize_keyboard" => true, "one_time_keyboard" => true));
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=confirm your location &reply_markup=" . $reply);
}
if ($msg == "Afternoon Checkin") {
    date_default_timezone_set('Africa/Addis_Ababa');
    $time = date("h:ia");
    $today = date('y-m-d');
    $employename = $con->query("SELECT name FROM user WHERE chat_id = '$chat_id'");
    while ($ro = mysqli_fetch_array($employename)) {
        $name = $ro['name'];
    }
    $checkk = "SELECT * FROM attendance_shit WHERE date ='$today' AND chat_id = '$chat_id' AND afternoon is not NULL";
    $qqq = mysqli_query($con, $checkk);
    $cou = mysqli_num_rows($qqq);
    if ($cou > 0) {
        $errormessage = "You have alrady signed your  attendance";
        file_get_contents($botAPI . "/sendMessage?text=$errormessage&chat_id=$chat_id&parse_mode=html");
        returnmenu($chat_id);
    } else {
        $morn = "SELECT * FROM attendance_shit WHERE date ='$today' AND chat_id = '$chat_id' AND morning is not NULL";
        $mmm = mysqli_query($con, $morn);
        $num = mysqli_num_rows($mmm);
        if ($num > 0) {
            if ($time >= $Astarttime && $time <= date('12:59')) {
                $updateQuery = "UPDATE attendance_shit SET afternoon = '$msg', afternoon_in='date('01:00')',afternoon_out='date('01:00')' WHERE date = '$today' AND chat_id='$chat_id'";
                mysqli_query($con, $updateQuery);
                file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=You have signed your attendance.At:" . " " . $time);
                totalHour($chat_id);
                returnmenu($chat_id);
            } else {
                $updateQuery = "UPDATE attendance_shit SET afternoon = '$msg', afternoon_in='$time',afternoon_out='$time' WHERE date = '$today' AND chat_id='$chat_id'";
                mysqli_query($con, $updateQuery);
                file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=You have signed your attendance.At:" . " " . $time);
                totalHour($chat_id);
                returnmenu($chat_id);
            }
        } else {
            $inserQuery = "INSERT INTO attendance_shit(employee_name,afternoon,date,chat_id,afternoon_in,afternoon_out) VALUES('$name','$msg','$today','$chat_id','$time','$time')";
            mysqli_query($con, $inserQuery);
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=You have signed your attendance.At:" . " " . $time);
            totalHour($chat_id);
            returnmenu($chat_id);
        }
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////CHECKOUT////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////
if ($msg == "📝 Morning Checkout") {
    $keyboard = array(array(array("text" => "send location", "request_location" => true, "has_protected_content" => true,)));
    $reply = json_encode(array("keyboard" => $keyboard, "resize_keyboard" => true, "one_time_keyboard" => true));
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=confirm your location &reply_markup=" . $reply);
}
if ($msg == "Morning Checkout") {
    date_default_timezone_set('Africa/Addis_Ababa');
    $time = date("h:ia");
    $today = date('y-m-d');

    $checkk = "SELECT * FROM attendance_shit WHERE date ='$today' AND chat_id = '$chat_id' AND morning is not NULL";
    $qqq = mysqli_query($con, $checkk);
    $cou = mysqli_num_rows($qqq);
    if ($cou > 0) {
        $updateQuery = "UPDATE attendance_shit SET morning_out='$time' WHERE chat_id= '$chat_id' AND date='$today'";
        mysqli_query($con, $updateQuery);
        totalHour($chat_id);
        returnmenu($chat_id);
    } else {
        $errormessage = rawurldecode("You need to checkin first!");
        file_get_contents($botAPI . "/sendMessage?text=$errormessage&chat_id=$chat_id&parse_mode=html");
        returnmenu($chat_id);
    }
}
////////////////////////////////////////////////////////////////////////////////////
if ($msg == "📝 Afternoon Checkout") {
    $keyboard = array(array(array("text" => "send location", "request_location" => true, "has_protected_content" => true,)));
    $reply = json_encode(array("keyboard" => $keyboard, "resize_keyboard" => true, "one_time_keyboard" => true));
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=confirm your location &reply_markup=" . $reply);
}
if ($msg == "Afternoon Checkout") {
    date_default_timezone_set('Africa/Addis_Ababa');
    $time = date("h:ia");
    $today = date('y-m-d');
    $checkk = "SELECT * FROM attendance_shit WHERE date ='$today' AND chat_id = '$chat_id' AND afternoon is not NULL";
    $qqq = mysqli_query($con, $checkk);
    $afternoonsigncheck = mysqli_num_rows($qqq);
    if ($afternoonsigncheck > 0) {
        $updateQuery = "UPDATE attendance_shit SET afternoon_out='$time' WHERE date= '$today' AND chat_id= '$chat_id'";
        mysqli_query($con, $updateQuery);
        totalHour($chat_id);
        file_get_contents($botAPI . "/sendMessage?text=$succesmessage&chat_id=$chat_id&parse_mode=html");
        returnmenu($chat_id);
    } else {
        $errormessage = rawurldecode("You need to checkin first!");
        file_get_contents($botAPI . "/sendMessage?text=$errormessage&chat_id=$chat_id&parse_mode=html");
        returnmenu($chat_id);
    }
}
/////////////////////////////////////////////////////////////////////////////////////
if ($msg == 'Approve') {
    appruvalMenu($chat_id);
}
if ($msg == 'New User') {
    listrequesteduser($chat_id);
}
////////////////////////////////////////////////////////////////////////////////////
if ($msg == "Report") {
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=you can use this link https://b-agro.co/attendancetelegrambot/admin/");
}
///registration/////////////////////////////////////////////////////////////////////

if ($row_num < 1) {

    if ($msg == "Register") {

        $uname = $update['message']['from']['first_name'];
        $inserQuery = "INSERT INTO user_temp (chat_id) VALUES('$chat_id')";
        mysqli_query($con, $inserQuery);
        file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=Please enter your full name?");
    }
} else if ($row_num > 0) {
    while ($ro = mysqli_fetch_array($qur)) {
        $chat_id = $ro['chat_id'];
        $name = $ro['name'];
    }
    if ($chat_id != NULL && $name == NULL) {
        setUserValues($chat_id, "name", $msg);
        userDataConfirmation($chat_id);
    }
    if ($msg == 'Confirm' && $name != NULL) {
        confirmetionreplay($chat_id);
    }
    if ($msg == 'Discard') {
        $del = "DELETE FROM user_temp WHERE chat_id='$chat_id'";
        mysqli_query($con, $del);
        register($chat_id);
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////
if ($msg == 'Approve Leave') {
    listleaverequest($chat_id);
}
//////////////////////////////////////////////////////////////////////////////////////////////
if ($lev_num < 1) {
    if ($msg == "Leave") {
        date_default_timezone_set("Africa/Addis_Ababa");
        $today = date('y-m-d');
        $employename = $con->query("SELECT name FROM user WHERE chat_id = '$chat_id'");
        while ($ro = mysqli_fetch_array($employename)) {
            $name = $ro['name'];
        }
        $inserQuery = "INSERT INTO leave_temp (chat_id,name,requested_date) VALUES('$chat_id','$name','$today')";
        mysqli_query($con, $inserQuery);
        file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=Please state the number of days you want to take the leave");
    }
} else if ($lev_num > 0) {
    while ($ro = mysqli_fetch_array($levQ)) {
        $chat_id = $ro['chat_id'];
        $name = $ro['name'];
        $number_of_days = $ro['number_of_days'];
        $starting_from = $ro['starting_from'];
        $reason = $ro['reason'];
    }
    if ($name != NULL && $number_of_days == NULL) {
        if (is_numeric($msg)) {
            setLeaveValue($chat_id, "number_of_days", $msg);
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=From when do you want to take the leave.with format mm/dd/yy");
        } else {
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=please enter numeric value");
        }
    } else if ($number_of_days != NULL && $starting_from == NULL) {
        $test_arr  = explode('/', $msg);
        if (count($test_arr) == 3) {
            if (checkdate($test_arr[0], $test_arr[1], $test_arr[2])) {
                $today = date("y-m-d");
                if (strtotime($today) > strtotime($msg)) {
                    $marksHTML = "";
                    $marksHTML .= "Your input date must greater than or equal to today %0A";
                    $hel = "<b >Error</b>%0A";
                    $hel .= $marksHTML . '%0A';
                    file_get_contents($botAPI . "/sendmessage?chat_id=$chat_id&text=$hel&parse_mode=html");
                } else {
                    setLeaveValue($chat_id, "starting_from", $msg);
                    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=Reason?");
                }
            } else {
                file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=Please enter valid date!!! using this format mm/dd/yy !");
            }
        } else {

            file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=Please enter valid input!!! using this format mm/dd/yy !");
        }
    } else if ($starting_from != NULL && $reason == NULL) {
        setLeaveValue($chat_id, "reason", strtolower($msg));
        leaveDataConfirmation($chat_id);
        $hel = "<b>Confirm</b>%0A";
        $sql = "select * from leave_temp where chat_id ='$chat_id'";
        $rep = mysqli_query($con, $sql);
        while ($ro = mysqli_fetch_array($rep)) {
            $name = $ro['name'];
            $number_of_days = $ro['number_of_days'];
            $starting_from = $ro['starting_from'];
            $reason = $ro['reason'];
        }
        ///////////////////////
        list($firstname, $secondname) = explode(" ", $name);
        $fname = rawurlencode($firstname);
        //$ereason=rawurldecode($reason);
        ///////////////////////
        $marksHTML = "";
        $marksHTMLL = "";
        $marksHTMLL .= "Name :- " . $fname;
        $marksHTML .= "number_of_days :- " . $number_of_days . "%0A";
        $marksHTML .= "starting_from :- " . $starting_from . "%0A";
        $marksHTML .= "reason :- " . $reason . "%0A";

        $hel .= rawurlencode($marksHTMLL) . "%0A" . $marksHTML . "%0A";

        file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=" . $hel . "&parse_mode=html");
    }
    if ($msg == 'Confirm Leave' &&  $reason != NULL) {
        confirmationLeaveReplay($chat_id);
    }
    if ($msg == 'Discard!') {
        $del = "DELETE FROM leave_temp WHERE chat_id='$chat_id'";
        mysqli_query($con, $del);
        returnmenu($chat_id);
    }
}
///////////////////////////////setting_shift//////////////////////////////////////////////////////
if ($shiftnum < 1) {
    if ($msg == "Set Shift") {

        $inserQuery = "INSERT INTO shift_temp (chat_id) VALUES('$chat_id')";
        mysqli_query($con, $inserQuery);
        file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=Please enter shift starting time");
    }
} else if ($shiftnum > 0) {
    $shift = $con->query("SELECT * FROM shift_temp");
    while ($ro = mysqli_fetch_array($shift)) {
        $chat_id = $ro['chat_id'];
        $Mstarttime = $ro['morning'];
        $Mendtime = $ro['lunch'];
        $Astarttime = $ro['afternoon'];
        $Aendtime = $ro['night'];
    }
    if ($chat_id != NULL && $Mstarttime == NULL) {
        setShiftValue($chat_id, "morning", $msg);
        file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=Please enter shift brake time");
    } else if ($Mstarttime != NULL && $Mendtime == NULL) {
        setShiftValue($chat_id, "lunch", $msg);
        file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=Please enter end of shift brake time");
    } else if ($Mendtime != NULL && $Astarttime == NULL) {
        setShiftValue($chat_id, "afternoon", $msg);
        file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=Please enter end of shift time");
    } else if ($Astarttime != NULL && $Aendtime == NULL) {
        setShiftValue($chat_id, "night", $msg);
        SetShiftConfirmation($chat_id);
    }
    if ($msg == 'Set' && $Aendtime != NULL) {
        confirmshift($chat_id);
    }
    if ($msg == 'Unset') {
        $del = "DELETE FROM shift_temp WHERE chat_id='$chat_id'";
        mysqli_query($con, $del);
        returnmenu($chat_id);
    }
}
///////////////////////////////////////
if ($msg == 'Field Work') {
    lengthOfTheWork($chat_id);
}
if ($msg == "Fullday") {
    date_default_timezone_set("Africa/Addis_Ababa");
    $today = date('y-m-d');
    $employename = $con->query("SELECT name FROM user WHERE chat_id = '$chat_id'");
    while ($ro = mysqli_fetch_array($employename)) {
        $name = $ro['name'];
    }
    $inserQuery = "INSERT INTO field_temp (chat_id,name,num_of_hour,date) VALUES('$chat_id','$name','$msg','$today')";
    mysqli_query($con, $inserQuery);
    notifyadmin($chat_id);
    returnmenu($chat_id);
}
if ($msg == "Halfday") {
    timeOfTheWork($chat_id);
}
if ($msg == "📝 Morning") {
    date_default_timezone_set("Africa/Addis_Ababa");
    $today = date('y-m-d');
    $employename = $con->query("SELECT name FROM user WHERE chat_id = '$chat_id'");
    while ($ro = mysqli_fetch_array($employename)) {
        $name = $ro['name'];
    }
    $inserQuery = "INSERT INTO field_temp (chat_id,name,num_of_hour,time_of_work,date) VALUES('$chat_id','$name','Half Day','$msg','$today')";
    mysqli_query($con, $inserQuery);
    notifyadmin($chat_id);
    returnmenu($chat_id);
} else if ($msg == "📝 Afternoon") {
    date_default_timezone_set("Africa/Addis_Ababa");
    $today = date('y-m-d');
    $employename = $con->query("SELECT name FROM user WHERE chat_id = '$chat_id'");
    while ($ro = mysqli_fetch_array($employename)) {
        $name = $ro['name'];
    }
    $inserQuery = "INSERT INTO field_temp (chat_id,name,num_of_hour,time_of_work,date) VALUES('$chat_id','$name','Half Day','$msg','$today')";
    mysqli_query($con, $inserQuery);
    notifyadmin($chat_id);
    returnmenu($chat_id);
}
if ($msg == 'Field') {
    listFieldWork($chat_id);
}
