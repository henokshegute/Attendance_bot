<?php
    $database = 'attendance';
    $host = '127.0.0.1:4306';
    $user = 'root';
    $pass = '';
    $con=mysqli_connect("$host","$user","$pass")or die("Failed To Connect");
    mysqli_select_db($con,"$database")or die("Failed to select database");    
    $update = json_decode(file_get_contents('php://input'), TRUE);
    $botToken = "5274375244:AAF8ta3FhmGpDK62eRKdsKaRburmeAjzNZ8";
    $botAPI = "https://api.telegram.org/bot".$botToken;
    $msg = $update['message']['text'];
    $chat_id = $update['message']['from']['id'];
    date_default_timezone_set('Africa/Addis_Ababa');
    $checkinmorning=date('H:i');
    //file_get_contents($botAPI."/sendMessage?text=$checkinmorning&chat_id=$chat_id&parse_mode=html");
    //////////////////
    $addQ="select * from user_temp where chat_id='$chat_id'";
    $qur=mysqli_query($con,$addQ);
    $row_num=mysqli_num_rows($qur);
    /////////////////
    $lev="select * from leave_temp where chat_id='$chat_id'";
    $levQ=mysqli_query($con,$lev);
    $lev_num=mysqli_num_rows($levQ);
    //
    $admin = "SELECT * FROM user WHERE role='admin' AND chat_id = '$chat_id' ";
    $adminQ=mysqli_query($con,$admin);
   // $num=mysqli_num_rows($admin);
    $count = 0;
    while ($ad = mysqli_fetch_array($adminQ)){
    $count += 1;
    }
    if ($count >=1){
        if ($msg == "/start"  ){

                date_default_timezone_set('Africa/Addis_Ababa');
                $timenow=date ('H:i');
                $Mstarttime=date('01:00');
                $Mendtime=date('20:00');
                $Astarttime=date('8:00');
                $Aendtime=date('3:30');

                if ($Mstarttime <= $timenow && $Mendtime >=$timenow){

                    $uname=$update['message']['from']['first_name'];
                    $welcome="Hello ".$uname. ", welcome to the B-agro attendance bot ";
                    $welcome=rawurlencode($welcome);
                    file_get_contents($botAPI."/sendMessage?text=$welcome&chat_id=$chat_id&parse_mode=html");  
                    admin($chat_id);
                }else if($Astarttime <= $timenow && $Aendtime >=$timenow) {
                    $uname=$update['message']['from']['first_name'];
                    $welcome="Hello ".$uname. ", welcome to the B-agro attendance bot ";
                    $welcome=rawurlencode($welcome);
                    file_get_contents($botAPI."/sendMessage?text=$welcome&chat_id=$chat_id&parse_mode=html");  
                    afternoonAdmin($chat_id);
                }else{
                    endsession($chat_id);
                }
        }else if ($update['callback_query']['data']){
            $chat_id = $update['callback_query']['from']['id'];
            $message_id =  $update['callback_query']['message']['message_id'];
            $season_data= $update['callback_query']['data'];
            list($first,$second) = explode(" ", $update['callback_query']['data']);
            if($first=="u"){
                    asUserFunction($second,$chat_id,$message_id);
    
                    }
            else if($first=="a"){

                asAdminFunction($second,$chat_id,$message_id);
                }   
            else if($first=="d"){
                delete($second,$chat_id,$message_id);
                }    
            else if($first=="l"){
                    accept($second,$chat_id,$message_id);
                }  
            else if($first=="n"){
                    decline($second,$chat_id,$message_id);
                } 
        }
      
    }
    if ($count <1){
        
        if ($msg == "/start"  ){
            
            $user = "SELECT * FROM user WHERE role='user' AND chat_id = '$chat_id'";
            $userQ=mysqli_query($con,$user);
            
            $count = 0;
            while ($ro = mysqli_fetch_array($userQ)){
                $count += 1;
            }
                if ($count>=1){
                    date_default_timezone_set('Africa/Addis_Ababa');
                    $timenow=date ('H:i');
                    $Mstarttime=date('08:00 ');
                    $Mendtime=date('24:00');
                    $Astarttime=date('4:00');
                    $Aendtime=date('4:30');
    
                    if ($Mstarttime <= $timenow && $Mendtime >=$timenow){
    
                        $uname=$update['message']['from']['first_name'];
                        $welcome="Hello ".$uname. ", welcome to the B-agro attendance botttt ";
                        $welcome=rawurlencode($welcome);
                        file_get_contents($botAPI."/sendMessage?text=$welcome&chat_id=$chat_id&parse_mode=html");  
                        startMenu($chat_id);
                    }else if($Astarttime <= $timenow && $Aendtime >=$timenow) {
                        $uname=$update['message']['from']['first_name'];
                        $welcome="Hello ".$uname. ", welcome to the B-agro attendance bot ";
                        $welcome=rawurlencode($welcome);
                        file_get_contents($botAPI."/sendMessage?text=$welcome&chat_id=$chat_id&parse_mode=html");  
                        AfternoonMenu($chat_id);
                    }else{
                         endsession($chat_id);
                        
                    }
                }else{
                    register($chat_id);
                    }
                
            }else
             if ($update['callback_query']['data'])
                {
                $chat_id = $update['callback_query']['from']['id'];
                $message_id =  $update['callback_query']['message']['message_id'];
                $season_data= $update['callback_query']['data'];
                list($first,$second) = explode(" ", $update['callback_query']['data']);
                if($first=="u"){
                        asUserFunction($second,$chat_id,$message_id);
        
                        }
                else if($first=="a"){
                    asAdminFunction($second,$chat_id,$message_id);
                    }   
                else if($first=="d"){
                    delete($second,$chat_id,$message_id);
                }  else if($first=="l"){
                    accept($second,$chat_id,$message_id);
                }  
                else if($first=="n"){
                    decline($second,$chat_id,$message_id);
                }   
            }
          
        }       

///////////////////////////////////////////////
 ////////MOrning///////////////////////////////   
/////////////////////////////////////////////////
if ($msg =="📝 Mornng Checkin" ){


        {
            date_default_timezone_set('Africa/Addis_Ababa');
            $time=date ("h:ia"); 
            $today = date('d/m/Y ');
            $employename= $update['message']['from']['first_name'];
                
                
            $checkk = "SELECT * FROM attendance_shit WHERE date ='$today' AND chat_id = '$chat_id' AND morning is not NULL";
            $qqq=mysqli_query($con,$checkk);

            $cou = 0;
            while ($ro = mysqli_fetch_array($qqq)){
            $cou += 1;
            }
            if($cou > 0){
            $errormessage="You have alrady signed morning attendance";
            file_get_contents($botAPI."/sendMessage?text=$errormessage&chat_id=$chat_id&parse_mode=html");

             } else 
             {

           $inserQuery="INSERT INTO attendance_shit(employee_name,morning,date,chat_id,morning_time) VALUES('$employename','$msg','$today','$chat_id','$time')";
           mysqli_query($con,$inserQuery);
           
           if($time<=$checkinmorning){
           
            file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=you have signed your attendance. Thank you for being on time &parse_mode=html");
            }else {
            file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=you have signed your attendance.At:"." ".$time);
            }
        }
    }              
 }
//////////////////////////////////////////////////////////////
        //Afternooon//
//////////////////////////////////////////////////////////////
      
if ($msg =="📝 Afternoon Checkin" ){

    date_default_timezone_set('Africa/Addis_Ababa');
    $time=date ("h:ia"); 
    $today = date('d/m/Y ');
    $employename= $update['message']['from']['first_name'];
    
    $checkk = "SELECT * FROM attendance_shit WHERE date ='$today' AND chat_id = '$chat_id' AND afternoon is not NULL";
    $qqq=mysqli_query($con,$checkk);

    $cou = 0;
    while ($ro = mysqli_fetch_array($qqq)){
        $cou += 1;
    }
     if($cou > 0){
        $errormessage="You have alrady signed your  attendance";
        file_get_contents($botAPI."/sendMessage?text=$errormessage&chat_id=$chat_id&parse_mode=html");

    } else{  
           
           $morn = "SELECT * FROM attendance_shit WHERE date ='$today' AND chat_id = '$chat_id' AND morning is not NULL";
           $mmm=mysqli_query($con,$morn); 
           $num=mysqli_num_rows($mmm);
           if ($num>0){
            
                $updateQuery="UPDATE attendance_shit SET afternoon = '$msg', afternoon_time='$time' WHERE date = '$today'";
                mysqli_query($con,$updateQuery);

                if($time<=$checkinmorning){
                file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=You have signed your attendance. Thank you for being on time &parse_mode=html");
                 }else {
                file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=You have signed your attendance.At:"." ".$time);
                }
           }else  {
            $inserQuery="INSERT INTO attendance_shit(employee_name,afternoon,date,chat_id,afternoon_time) VALUES('$employename','$msg','$today','$chat_id','$time')";
            mysqli_query($con,$inserQuery);

                if($time<=$checkinmorning){
                file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=You have signed your attendance. Thank you for bing on time &parse_mode=html");
                 }else {
                 file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=You have signed your attendance.But late  &parse_mode=html");
                }
           }
        }
    }

/////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////For listing Attendance////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////

if ($msg == "List_Attendance" ){

    date_default_timezone_set('Africa/Addis_Ababa');
    $timenow=date ('H:i');
    $Mstarttime=date('08:00 ');
    $Mendtime=date('24:00');
    $Astarttime=date('18:00');
    $Aendtime=date('24:30');
    if ($Mstarttime <= $timenow && $Mendtime >=$timenow){
        date_default_timezone_set("Africa/Addis_Ababa");
        $today =date('d/m/Y ');
        $marksHTML="";
        $getQuery="SELECT employee_name,morning_time FROM attendance_shit WHERE date= '$today' ";
        $query=mysqli_query($con,$getQuery);
        while ($ls = mysqli_fetch_array($query)){
            $name=$ls['employee_name'];
            $time=$ls['morning_time'];  
            $marksHTML.=rawurlencode("Name :- ".$name."  ".$time."\n");
        }

        file_get_contents($botAPI."/sendMessage?text=$marksHTML&chat_id=$chat_id&parse_mode=html");
    }else if ($Astarttime <= $timenow && $Aendtime >=$timenow) {
        date_default_timezone_set("Africa/Addis_Ababa");
        $today =date('d/m/Y ');
        $marksHTML="";
        $getQuery="SELECT employee_name,afternoon_time FROM attendance_shit WHERE date= '$today' ";
        $query=mysqli_query($con,$getQuery);
        while ($ls = mysqli_fetch_array($query)){
            $name=$ls['employee_name'];
            $time=$ls['afternoon_time'];  
            $marksHTML.=rawurlencode("Name :- ".$name."  ".$time."\n");
        }

        file_get_contents($botAPI."/sendMessage?text=$marksHTML&chat_id=$chat_id&parse_mode=html");
    }else {
        file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=There is no data at this time");
    }
}
/////////////////////////////////////////////////////////////////////////////////////
if ($msg=='Approve'){

$conf="SELECT * FROM user_temp";
$confQ=mysqli_query($con,$conf);
$count = 0;
global $botAPI;
$id='415379196';
    if (mysqli_num_rows($confQ)>0)
    while ($ro=mysqli_fetch_array($confQ))
        {
        
        $chat_id=$ro['chat_id'];
        $name=$ro['name'];
        
        $marksHTML="";
        $marksHTML.="name :- ".$name."%0A";
        $hel="<b>Aprove:</b>%0A";
        $hel.=$marksHTML.'%0A';
        $approve_as_user="u ";
        $approve_as_user.=$chat_id;
        $approve_as_admin="a ";
        $approve_as_admin.=$chat_id;
        $delete="d ";
        $delete.=$chat_id;
                $keyboard = json_encode(["inline_keyboard" => [[
                            ["text" => " ✔️ User","callback_data" => $approve_as_user],
                            ["text" => " ✔️ Admin","callback_data" => $approve_as_admin],
                            ["text" => " ❌ Delete","callback_data" => $delete],
                            ],],'resize_keyboard' => true,"one_time_keyboard" => true]);
        file_get_contents($botAPI . "/sendmessage?chat_id=".$id."&text=".$hel."&parse_mode=html&reply_markup={$keyboard}");
        }
        else{
        file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=There is no registration requestes for now");
    }
}
///registration/////////////////////////////////////////////////////////////////////

    if ($row_num<1){
        
        if ($msg == "Register" ){

        $uname=$update['message']['from']['first_name'];
        $inserQuery="INSERT INTO user_temp (chat_id) VALUES('$chat_id')";
        mysqli_query($con,$inserQuery);
        file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=Please enter your full name?");
        } 
     
      }else if ($row_num>0){
    
        while ($ro=mysqli_fetch_array($qur))
        {
       
        $chat_id=$ro['chat_id'];
        $name=$ro['name']; 
        
        }
        if($chat_id!=NULL && $name==NULL)
            {
            setValues($chat_id,"name",rawurlencode($msg));
            confirmDiscard($chat_id);
        }
    
    if ($msg == 'Confirm' && $name!=NULL)
        {
            confirmetionreplay($chat_id);
        }
    if ($msg=='Discard')
        {
            $del="DELETE FROM user_temp WHERE chat_id='$chat_id'";
            mysqli_query($con,$del);
            file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=Your regitration is canceled");
            register($chat_id);
        }
    }
   
///////////////////////////////////////////////////////////////////////////////////////////////
if ($msg=='Approve_Leave'){
    $cont="SELECT * FROM leave_temp";
    $confs=mysqli_query($con,$cont);
    global $botAPI;
    $id='415379196';
    if(mysqli_num_rows($confs)>0){
        while ($ro=mysqli_fetch_array($confs))
        {   
            $chat_id=$ro['chat_id'];
            $name=$ro['name'];
            $number_of_days=$ro['number_of_days'];
            $starting_from=$ro['starting_from'];
            $reason=$ro['reason']; 
            $marksHTML="";
            $marksHTML.="name :- ".$name."%0A";
            $marksHTML.="number_of_days :- ".$number_of_days."%0A";
            $marksHTML.="starting_from :- ".$starting_from."%0A";
            $marksHTML.="reason :- ".$reason."%0A";
            $hel="<b>Aprove:</b>%0A";
            $hel.=$marksHTML.'%0A';
            $accept="l ";
            $accept.=$chat_id;
            $decline="n ";
            $decline.=$chat_id;
            $keyboard = json_encode(["inline_keyboard" => [[
                        ["text" => " ✔️ Accept","callback_data" => $accept],
                        ["text" => " ❌ Decline","callback_data" => $decline],
                        ],],'resize_keyboard' => true,"one_time_keyboard" => true]);
    file_get_contents($botAPI . "/sendmessage?chat_id=".$id."&text=".$hel."&parse_mode=html&reply_markup={$keyboard}");
    }
    }else {
    file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=There is no leave requestes for now");
    }
}
//////////////////////////////////////////////////////////////////////////////////////////////

if ($lev_num<1){
        
        if ($msg == "Leave" ){
        date_default_timezone_set("Africa/Addis_Ababa");
        $today =date('d/m/Y ');
        $uname=$update['message']['from']['first_name'];
        $inserQuery="INSERT INTO leave_temp (chat_id,requested_date) VALUES('$chat_id','$today')";
        mysqli_query($con,$inserQuery);
        file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=Please enter your full name?");
        } 
     
      }else if ($lev_num>0){
        while ($ro=mysqli_fetch_array($levQ))
        {
       
            $chat_id=$ro['chat_id'];
            $name=$ro['name'];
            $number_of_days=$ro['number_of_days'];
            $starting_from=$ro['starting_from'];
            $reason=$ro['reason']; 
        
        }
        
        if($chat_id!=NULL && $name==NULL)
            {
            setleave($chat_id,"name",rawurlencode($msg));
            file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=Please state the number of days you want to take the leave");
            }
        else if ($name!=NULL && $number_of_days==NULL){
            setleave($chat_id,"number_of_days",$msg);
            file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=From when do you want to take the leave");

        }else if ($number_of_days!=NULL && $starting_from==NULL){
            // setleave($chat_id,"starting_from",$msg);
            // file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=Reason?");
            $test_arr  = explode('/', $msg); 
                if (count($test_arr) == 3){ 
                    if (checkdate($test_arr[0],$test_arr[1], $test_arr[2])) {
                        $today = date("Y/m/d");
                        if( strtotime($today) > strtotime($msg)) {
                            $marksHTML="";
                            $marksHTML.="Your input date must greater than or equal to today %0A";
                            $hel="<b >Error</b>%0A";
                            $hel.=$marksHTML.'%0A';
                            file_get_contents($botAPI . "/sendmessage?chat_id=$chat_id&text=$hel&parse_mode=html");
                            enterdatefunction($chat_id);
                        }
                        else{
                            setleave($chat_id,"starting_from",$msg);
                            file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=Reason?");
                        }
                    } else {
                        $marksHTML="";
                        $marksHTML.="Please enter valid date!!! %0A";
                        $hel="<b >Error</b>%0A";
                        $hel.=$marksHTML.'%0A';
                        file_get_contents($botAPI . "/sendmessage?chat_id=$chat_id&text=$hel&parse_mode=html");
                        enterdatefunction($chat_id);
                    }
                } else {
                    $marksHTML="";
                    $marksHTML.="Please enter valid input like --/--/---- %0A";
                    $hel="<b>Error</b>%0A";
                    $hel.=$marksHTML.'%0A';
                    file_get_contents($botAPI . "/sendmessage?chat_id=$chat_id&text=$hel&parse_mode=html");
                    enterdatefunction($chat_id);
             }
            
        }else if ($starting_from!=NULL && $reason==NULL){
            setleave($chat_id,"reason",rawurlencode($msg));
            confirmleave($chat_id);
            $hel="<b>Confirm</b>%0A";         
            $sql = "select * from leave_temp where chat_id ='$chat_id'";
            
            $rep = mysqli_query($con, $sql);
            while ($ro=mysqli_fetch_array($rep))
            {
           
            
            $name=$ro['name'];
            $number_of_days=$ro['number_of_days'];
            $starting_from=$ro['starting_from'];
            $reason=$ro['reason']; 
            
            }
            $marksHTML.="Name :- ".$name."%0A";
            $marksHTML.="number_of_days :- ".$number_of_days."%0A";
            $marksHTML.="starting_from :- ".$starting_from."%0A";
            $marksHTML.="reason :- ".$reason."%0A";
            
            $hel.=$marksHTML.'%0A';
            
            file_get_contents($botAPI . "/sendmessage?chat_id=".$chat_id."&text=".$hel."&parse_mode=html");
           
            
          }
          if ($msg == 'Confirm_leave' &&  $reason!=NULL )
        {
            
            confirmetion_leave($chat_id);
            
        } if ($msg=='Discard!'){
            $del="DELETE FROM leave_temp WHERE chat_id='$chat_id'";
            mysqli_query($con,$del);
            file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text= Your request is cancceled! Thank you");
            back($chat_id);
    }
}
        
/////////////////////////////////////////////////////////////////////////////////////
function startMenu($chat_id){
        global $botAPI;
        global $con;
        global $update;

        $keyboard = array(array("📝 Mornng Checkin","List_Attendance",),array("Leave","Checkout_M"));
        $resp = array("keyboard" => $keyboard,"resize_keyboard" => true,"one_time_keyboard" => true);
        $reply = json_encode($resp);
        file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text= Please Press Mornng Checkin button to sign your attendance &reply_markup=".$reply);
        
    }
function AfternoonMenu($chat_id){
        global $botAPI;
        global $con;
        global $update;

        $keyboard = array(array("📝 Afternoon Checkin ","List_Attendance"),array("Leave","Checkout_A"));
        $resp = array("keyboard" => $keyboard,"resize_keyboard" => true,"one_time_keyboard" => true);
        $reply = json_encode($resp);
        file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text= Please Press Afternoon Checkin button to sign your attendance &reply_markup=".$reply);
        
    }
function admin($chat_id){
            global $botAPI;
            global $con;
            global $update;
    
            $keyboard = array(array("📝 Mornng Checkin","List_Attendance"),array("Approve_Leave","Approve"),array("Leave","Checkout_M"));
            $resp = array("keyboard" => $keyboard,"resize_keyboard" => true,"one_time_keyboard" => true);
            $reply = json_encode($resp);
            file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text= Please Press Mornng Checkin button to sign your attendance &reply_markup=".$reply);
  
        }  
function afternoonAdmin($chat_id){
            global $botAPI;
            global $con;
            global $update;
    
            $keyboard = array(array("📝 Afternoon Checkin","List_Attendance"),array("Approve_Leave","Approve"),array("Leave","Checkout_A"));
            $resp = array("keyboard" => $keyboard,"resize_keyboard" => true,"one_time_keyboard" => true);
            $reply = json_encode($resp);
            file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text= Please Press Afternoon Checkin button to sign your attendance &reply_markup=".$reply);  
        }  
        
function endsession($chat_id){
             global $botAPI;
             global $con;
             global $update;
        
             file_get_contents($botAPI."/sendMessage?text=The work hour is not started yet &chat_id=$chat_id&parse_mode=html");

         }
function disable($chat_id){
            global $botAPI;
            $keyboard = array(array(" "));
            $resp = array("keyboard" => $keyboard,"resize_keyboard" => true,"one_time_keyboard" => true);
            $reply = json_encode($resp);
            file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text= when you get notification you press start from the menu &reply_markup=".$reply);
}
/////////////////////////////////////////////////////////////
/////Adding member to user DB////////////////////////////////
/////////////////////////////////////////////////////////////
               
function setValues($id,$key,$temp){
            global $con;
            $now="UPDATE user_temp SET $key ='$temp' WHERE chat_id='$id'";
            mysqli_query($con,$now);

        }
function setleave($id,$key,$temp){
            global $con;
            $now="UPDATE leave_temp SET $key ='$temp' WHERE chat_id='$id'";
            mysqli_query($con,$now);

        }
function confirmDiscard($chat_id){
            $keyboard = array(array("Confirm","Discard"));
            global $con; 
            global $botAPI;
            $marksHTML="";  
            $hel="<b>Confirm</b>%0A";         
            $sql = "select * from user_temp where chat_id ='$chat_id'";
            $rep = mysqli_query($con, $sql);
            while ($ro = mysqli_fetch_array($rep))
            {
                
                $name=$ro['name'];
                
            }
            $marksHTML.="Name :- ".$name;
            
            $hel.=$marksHTML.'%0A';
            file_get_contents($botAPI . "/sendmessage?chat_id=".$chat_id."&text=".$hel."&parse_mode=html");
            $resp = array("keyboard" => $keyboard,"resize_keyboard" => true,"one_time_keyboard" => true);
            $reply = json_encode($resp);
            file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=Please Confirm: &reply_markup=".$reply);
        }
function confirmleave($chat_id){
           
            $keyboard = array(array("Confirm_leave","Discard!"));
            global $con; 
            global $botAPI;
           // $marksHTML="";  
            
            $resp = array("keyboard" => $keyboard,"resize_keyboard" => true,"one_time_keyboard" => true);
            $reply = json_encode($resp);
            file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=Please Confirm: &reply_markup=".$reply);
        }
function asUserFunction($id,$chat_id,$message_id){
        global $botAPI;
        global $con;
        $sql = "select * from user_temp where chat_id ='$id'";
        $query = mysqli_query($con, $sql);
        $row_num=mysqli_num_rows($query);
        if($row_num>0){
            while ($ro = mysqli_fetch_array($query)){
                $name=$ro['name'];
            
            }
           $qu="INSERT INTO user(chat_id,name,role) VALUES('$id','$name','user')";
           mysqli_query($con,$qu);
           $del="DELETE FROM user_temp WHERE chat_id='$id'";
           mysqli_query($con,$del);
           
           file_get_contents($botAPI."/sendmessage?chat_id=".$id."&text=You are registerd successfully !!!");

           $approve_as_user="u ";
           $approve_as_user.=$chat_id;
           $keyboard = json_encode(["inline_keyboard" => [[
            ["text" => "✔️ User","callback_data" => $approve_as_user],
            ],],'resize_keyboard' => true,"one_time_keyboard" => true]);
           file_get_contents($botAPI."/editMessageReplyMarkup?chat_id=".$chat_id."&message_id=".$message_id."&reply_markup={$keyboard}");
        }
    }
function asAdminFunction($id,$chat_id,$message_id){
        global $botAPI;
        global $con;
        global $msg;
        
        $sql = "select * from user_temp where chat_id ='$id'";
        $query = mysqli_query($con, $sql);
        $row_num=mysqli_num_rows($query);
        if($row_num>0){
            while ($ro = mysqli_fetch_array($query)){
                $name=$ro['name'];
            
            }
           $qu="INSERT INTO user(chat_id,name,role) VALUES('$id','$name','admin')";
           mysqli_query($con,$qu);
           $del="DELETE FROM user_temp WHERE chat_id='$id'";
           mysqli_query($con,$del);

           file_get_contents($botAPI."/sendmessage?chat_id=".$id."&text=You are registerd successfully !!!");
           $approve_as_admin="a ";
            $approve_as_admin.=$chat_id;
           $keyboard = json_encode(["inline_keyboard" => [[
          
            ["text" => "✔️ Admin","callback_data" => $approve_as_admin],
           
            ],],'resize_keyboard' => true,"one_time_keyboard" => true]);
           file_get_contents($botAPI."/editMessageReplyMarkup?chat_id=".$chat_id."&message_id=".$message_id."&reply_markup={$keyboard}");
        }
    }
function delete($id,$chat_id,$message_id){
        global $botAPI;
        global $con;
        $sql = "select * from user_temp where chat_id ='$id'";
        $query = mysqli_query($con, $sql);
        $row_num=mysqli_num_rows($query);
        if($row_num>0){
            while ($ro = mysqli_fetch_array($query)){
                $name=$ro['name'];
             }
           $del="DELETE FROM user_temp WHERE chat_id='$id'";
           mysqli_query($con,$del);

           file_get_contents($botAPI."/sendmessage?chat_id=".$id."&text=You are not allowed to register !!!");
           
           $delete="d ";
           $delete.=$chat_id;
           $keyboard = json_encode(["inline_keyboard" => [[
            ["text" => "❌ Deleted","callback_data" => $delete],
            ],],'resize_keyboard' => true,"one_time_keyboard" => true]);
           file_get_contents($botAPI."/editMessageReplyMarkup?chat_id=".$chat_id."&message_id=".$message_id."&reply_markup={$keyboard}");
        }
    }
//////////////////////////////////////////////////////////////
function accept($id,$chat_id,$message_id){
    global $botAPI;
    global $con;
    global $msg;
    
    $sql = "select * from leave_temp where chat_id ='$id'";
    $query = mysqli_query($con, $sql);
    
    $lev_num=mysqli_num_rows($query);
    if($lev_num>0){
        while ($ro = mysqli_fetch_array($query)){
            
            $name=$ro['name'];
            $number_of_days=$ro['number_of_days'];
            $starting_from=$ro['starting_from'];
            $reason=$ro['reason']; 
        
        }
       $qu="INSERT INTO leave_table (chat_id,name,number_of_days,starting_from,reason) VALUES('$id','$name','$number_of_days','$starting_from','$reason')";
       mysqli_query($con,$qu);
       $del="DELETE FROM leave_temp WHERE chat_id='$id'";
       mysqli_query($con,$del);
       file_get_contents($botAPI."/sendmessage?chat_id=".$id."&text=Your leave request is approved !!!");
       $accept="l ";
        $accept.=$chat_id;
       $keyboard = json_encode(["inline_keyboard" => [[
      
        ["text" => "✔️ Accepted","callback_data" => $accept],
       
        ],],'resize_keyboard' => true,"one_time_keyboard" => true]);
       file_get_contents($botAPI."/editMessageReplyMarkup?chat_id=".$chat_id."&message_id=".$message_id."&reply_markup={$keyboard}");
    }
}
function decline($id,$chat_id,$message_id){
    global $botAPI;
    global $con;
    $sql = "select * from leave_temp where chat_id ='$id'";
    $query = mysqli_query($con, $sql);
    $row_num=mysqli_num_rows($query);
    if($row_num>0){
        while ($ro = mysqli_fetch_array($query)){
            $chat_id=$ro['chat_id'];
         }
       $del="DELETE FROM leave_temp WHERE chat_id='$id'";
       mysqli_query($con,$del);

       file_get_contents($botAPI."/sendmessage?chat_id=".$id."&text=Your leave is not approved !!!");
       
       $decline="d ";
       $decline.=$chat_id;
       $keyboard = json_encode(["inline_keyboard" => [[
        ["text" => "❌ Deleted","callback_data" => $decline],
        ],],'resize_keyboard' => true,"one_time_keyboard" => true]);
       file_get_contents($botAPI."/editMessageReplyMarkup?chat_id=".$chat_id."&message_id=".$message_id."&reply_markup={$keyboard}");
    }
}

////////////////////////////////////////////////////////////
//////////////regigistration///////////////////////////////
//////////////////////////////////////////////////////
function register($chat_id){
    global $con;
    global $botAPI;
    global $con;
    global $update;
    
    $keyboard = array(array("Register"));
    $resp = array("keyboard" => $keyboard,"resize_keyboard" => true,"one_time_keyboard" => true);
    $reply = json_encode($resp);
    file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text= Please register here and the admin will aprove when they got it &reply_markup=".$reply);
    }
function confirmetionreplay($chat_id){
        global $botAPI;
        $id='415379196';
        $info="";
        $info.="Thank you for taking the time and filling in your details.  we will communicate with you shortly.%0A";
        $info.="All the best!";
        file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=".$info."&parse_mode=html");
        file_get_contents($botAPI."/sendmessage?chat_id=".$id."&text=Dear Admin, There are a requested registration you need to approve");
        disable($chat_id);
    }
function confirmetion_leave($chat_id){
        global $botAPI;
        $id='415379196';
        $info="";
        $info.="Thank you for taking the time and filling in your details.  we will communicate with you shortly.%0A";
        $info.="All the best!";
        file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=".$info."&parse_mode=html");
        file_get_contents($botAPI."/sendmessage?chat_id=".$id."&text=Dear Admin, There are a request for leave you need to approve");
        back($chat_id);
    }
function enterdatefunction($chat_id){
        global $botAPI;
        file_get_contents($botAPI."/sendmessage?chat_id=".$chat_id."&text=Please enter checkin date in this format mm/dd/yy ? :");
    }
function back($chat_id){ 
global $con;
    date_default_timezone_set('Africa/Addis_Ababa');
    $timenow=date ('H:i');
    $Mstarttime=date('08:00');
    $Mendtime=date('20:00');
    $Astarttime=date('8:00');
    $Aendtime=date('3:30');
$user = "SELECT * FROM user WHERE role='user' AND chat_id = '$chat_id'";
$userQ=mysqli_query($con,$user);
    
    $count = 0;   
    while ($ro = mysqli_fetch_array($userQ))
        {
            $count += 1;
        }
if ($count>=1){
        if ($Mstarttime <= $timenow && $Mendtime >=$timenow)
        {
            StartMenu($chat_id);
        
        }else if($Astarttime <= $timenow && $Aendtime >=$timenow)
        {
        AfternoonMenu($chat_id);
        }
}else{
    if ($Mstarttime <= $timenow && $Mendtime >=$timenow)
    {
        admin($chat_id);
        
    }else if($Astarttime <= $timenow && $Aendtime >=$timenow)
    {
        afternoonAdmin($chat_id);
    }
    }
}