<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Headers:*");


require('db.php');
require('constant.php');
require('function.php');

$action=$_REQUEST['action'];

//Signup
if ($action=="01") {

$firstname=$_REQUEST["firstname"];
$lastname=$_REQUEST["lastname"];
$email=$_REQUEST["email"];
$password=md5($_REQUEST["password"]);
$confirmpassword=md5($_REQUEST['confirmpassword']);
$username=$_REQUEST["username"];


if (($firstname!=='') or ($lastname!=='') or ($email!=='') or ($password!=='') or ($confirmpassword!=='') or ($username!=='') or ($db!=='') ) {
    if ($password == $confirmpassword) {
        if (existing($email,$username,$db)==true) {
           if (register ($firstname, $lastname, $email, $password, $username, $gender, $birthday, $phonenumber, $timestamp, $db)==true) {
                $array = [
                'response' => "01",
                'message' => "signup successful",
                'usertoken'=>"".$_SESSION['usertoken'].""
                ];
                $array = json_encode($array);
                echo "$array";
                exit;
            }else {
                $array = [
                'response' => "00",
                'message' => "signup unsuccessful",
                ];
                $array = json_encode($array);
                echo "$array";
                exit;
            }

        }else {
            $array = [
            'response' => "03",
            'message' => "email or username exist"
            ];
            $array = json_encode($array);
            echo "$array";
            exit;
        }
    }else {
        $array = [
            'response' => "00",
            'message' => "password do not match"
        ];
        $array = json_encode($array);
        echo "$array";
        exit;
    }
}else {
    $array = [
        'response' => "03",
        'message' => "Enter empty fields"
    ];
    $array = json_encode($array);
    echo "$array";
    exit;
}
}

// login module
if ($action=="01") {

    $email=$_REQUEST["email"];
    $password=md5($_REQUEST["password"]);
    $sql= "SELECT * FROM signup WHERE mail='$email' and password='$password'";
    
    if ($result=$db->query($sql)) {
        if ($result->num_rows>0) {
            if ($row=$result->fetch_array()) {
               if (timeupdate ($email,$passsword,$timestampupdate,$db)==true) {
                    $array = [
                    'response' => "02",
                    'message' => "login successful",
                    'usertoken'=>"".$_SESSION['usertoken'].""
                    ];
                    $array = json_encode($array);
                    echo "$array";
                    exit;
                }else {
                    $array = [
                    'response' => "00",
                    'message' => "login unsuccessful",
                    ];
                    $array = json_encode($array);
                    echo "$array";
                    exit;
                }
            }
        }
    }
}

// document function

if ($action=="03") {

    $type=$_REQUEST["type"];
    $document_name=$_REQUEST["document_name"];
    $usertoken=$_REQUEST["usertoken"];
    $folder_token=$_REQUEST["folder_token"];


    
    if (document ($type, $document_name, $usertoken, $folder_token, $timestamp, $db)==true) {
        if (generateAlphaNumericOTP(6)==true) {
            $array = [
            'response' => "03",
            'message' => "document created",
            'token'=>"".$_SESSION['token'].""
            ];
            $array = json_encode($array);
            echo "$array";
            exit;
        }else {
            $array = [
            'response' => "00",
            'message' => "Document not created, something went wrong"
            ];
            $array = json_encode($array);
            echo "$array";
            exit;
        }    
    }
}


// content function

if ($action=="04") {

    $usertoken=$_REQUEST["usertoken"];
    $file_token=$_REQUEST["file_token"];
    $content=$_REQUEST["content"];

    
    if (content ($usertoken, $file_token, $content, $timestamp, $db)==true) {
        $array = [
        'response' => "04",
        'message' => "Content created",
        'file_token'=>"".$_SESSION['file_token']."",
        'content_token'=>"".$_SESSION['content_token'].""
        ];
        $array = json_encode($array);
        echo "$array";
        exit;
    }else {
        $array = [
        'response' => "00",
        'message' => "Something went wrong"
        ];
        $array = json_encode($array);
        echo "$array";
        exit;
    }
}    

// content update function

if ($action=="05") {

    $content=$_REQUEST["content"];
    $content_token=$_REQUEST['content_token'];

    if (contentupdate ($file_token, $content, $timestamp, $db)==true) {
        $array = [
        'response' => "05",
        'message' => "Content updated",
        'usertoken'=>"".$_SESSION['usertoken'].""
        ];
        $array = json_encode($array);
        echo "$array";
        exit;
    }else {
        $array = [
        'response' => "00",
        'message' => "Something went wrong"
        ];
        $array = json_encode($array);
        echo "$array";
        exit;
    }
}


// select all documents
if ($action=="06") {

    $usertoken=$_REQUEST['usertoken'];
    $sql="SELECT * FROM document WHERE usertoken='$usertoken' and deleted=0 ";
    if ($result=$db->query($sql)) {
        if ($result->num_rows>0) {
            echo "[";
            $count=0;
            while ($row=$result->fetch_array()) {
                $count= ++$count;
                $num=$result->num_rows;
                $array=[
                    'usertoken' => "".$row['usertoken']."",
                    'document_name' => "".$row['document_name']."",
                    'type' => "".$row['type']."",
                    'token' => "".$row['token']."",
                    'folder_token' => "".$row['folder_token']."",
                    'timerstamp' => "".$row['timerstamp']."",
                    'num' => "$num",
                    'count' => "$count"
                ];
                $array=json_encode($array);
                if ($num > $count) {
                    echo "$array,";
                }else {
                    echo "$array";
                }
            }
            echo "]";
        }else {
            $array =[
                'response'=>"00",
                'message'=>"No results"
            ];
            $array=json_encode($array);
            echo "$array";
            exit;
        }
    }
}


// select files from folders
if ($action=="07") {

    $usertoken=$_REQUEST['usertoken'];
    $folder_token=$_REQUEST['folder_token'];
    $sql="SELECT * FROM document WHERE folder_token='$folder_token' and usertoken='$usertoken' and deleted=0  ";
    if ($result=$db->query($sql)) {
        if ($result->num_rows>0) {
            echo "]";
            $count=0;
            while ($row=$result->fetch_array()) {
                $count= ++$count;
                $num=$result->num_rows;
                $array=[
                    'usertoken' => "".$row['usertoken']."",
                    'document_name' => "".$row['document_name']."",
                    'token' => "".$row['token']."",
                    'folder_token' => "".$row['folder_token']."",
                    'timerstamp' => "".$row['timerstamp'].""
                ];
                $array=json_encode($array);
                if ($num > $count) {
                    echo "$array,";
                }else {
                    echo "$array";
                }
            }
            echo "]";
        }else {
            $array =[
                'response'=>"00",
                'message'=>"No results"
            ];
            $array=json_encode($array);
            echo "$array";
            exit;
        }
    }
}


// select content from files
if ($action=="08") {
    
    $file_token=$_REQUEST['file_token'];
    $usertoken= getusertoken($file_token, $db);

    $sql="SELECT * FROM document WHERE file_token='$file_token' ";
    if ($result=$db->query($sql)) {
        if ($result->num_rows>0) {
            echo "[";
            $count=0;
            while ($row=$result->fetch_array()) {
                $count= ++$count;
                $num=$result->num_rows;
                $array=[
                    'usertoken' => "".$row['usertoken']."",
                    'content' => "".$row['content']."",
                    'content_token' => "".$row['content_token']."",
                    'file_token' => "".$row['file_token']."",
                    'timerstamp' => "".$row['timerstamp']."",
                    'num' => "$num",
                    'count' => "$count"
                ];
                $array=json_encode($array);
                if ($num > $count) {
                    echo "$array,";
                }else {
                    echo "$array";
                }
            }
            echo "]";
        }else {
            $array =[
                'response'=>"00",
                'message'=>"No results"
            ];
            $array=json_encode($array);
            echo "$array";
            exit;
        }
    }
}


// document update module
if ($action=="09") {

    $document_name=$_REQUEST['document_name'];
    $token=$_REQUEST['token'];

    if (checkToken($token, $db)==true) {
        if (documentupdate ($token, $document_name, $timestamp, $db)==true) {
            $array = [
            'response' => "09",
            'message' => "document updated",
            'token'=>"".$_SESSION['token'].""
            ];
            $array = json_encode($array);
            echo "$array";
            exit;
        }else {
            $array = [
            'response' => "00",
            'message' => "Something went wrong"
            ];
            $array = json_encode($array);
            echo "$array";
            exit;
        }
    }else {
        $array = [
            'response' => "00",
            'message' => "token doesn't exist"
        ];
        $array = json_encode($array);
        echo "$array";
        exit;
    }
}

// Trash update module
if ($action=='10') {
    $usertoken=$_REQUEST['usertoken'];
    $token=$_REQUEST['token'];
    if (checkToken($token, $db)==true) {
        if (trashDocument($token, $db)==true) {
            if (trashContent($token, $db)==true) {
                $array=[
                    'response'=>"10",
                    'message'=>"document moved to trash",
                    'deleted status'=>"".$_SESSION['deleted'].""
                ];
                $array= json_encode($array);
                echo "$array";
                exit;
            }else {
                $array=[
                    'response'=>"00",
                    'message'=>"something went wrong"
                ];
                $array= json_encode($array);
                echo "$array";
                exit;
            }
        }else {
            $array=[
                'response'=>"00",
                'message'=>"something went wrong"
            ];
            $array= json_encode($array);
            echo "$array";
            exit;
        }
    }else {
        $array=[
            'response'=>"00",
            'message'=>"Token doesn't exist"
        ];
        $array= json_encode($array);
        echo "$array";
        exit;
    }
}

// Read Trash module
if ($action=='11') {
    
    $usertoken=$_REQUEST['usertoken'];
    $sql="SELECT * FROM document WHERE usertoken='$usertoken' and deleted=1 ";
    if ($result=$db->query($sql)) {
        if ($result->num_rows>0) {
            echo "[";
            $count=0;
            while ($row=$result->fetch_array()) {
                $count= ++$count;
                $num=$result->num_rows;
                $array=[
                'usertoken' => "".$row['usertoken']."",
                'document_name' => "".$row['document_name']."",
                'type' => "".$row['type']."",
                'token' => "".$row['token']."",
                'folder_token' => "".$row['folder_token']."",
                'timerstamp' => "".$row['timerstamp']."",
                'num' => "$num",
                'count' => "$count"
                ];
                $array=json_encode($array);
                if ($num > $count) {
                    echo "$array,";
                }else {
                    echo "$array";
                }
            }
            echo "]";
        }else {
            $array =[
                'response'=>"00",
                'message'=>"No results"
            ];
            $array=json_encode($array);
            echo "$array";
            exit;
        }
    }
}


// Restore Trash module
if ($action=='12') {
    $usertoken=$_REQUEST['usertoken'];
    $token=$_REQUEST['token'];
    if (checkToken($token, $db)==true) {
        if (restoretrash()==true) {
            if (restoretrashContent($token, $db)==true) {
                $array=[
                    'response'=>"12",
                    'message'=>"document has been successfully restored",
                    'deleted status'=>"".$_SESSION['deleted'].""
                ];
                $array= json_encode($array);
                echo "$array";
                exit;
            }else {
                $array=[
                    'response'=>"00",
                    'message'=>"something went wrong"
                ];
                $array= json_encode($array);
                echo "$array";
                exit;
            }
        }
        
    }else {
        $array=[
            'response'=>"00",
            'message'=>"Token doesn't exist"
        ];
        $array= json_encode($array);
        echo "$array";
        exit;
    }
}


// Empty Trash module
if ($action=='13') {
    $usertoken=$_REQUEST['usertoken'];
    $sql="DELETE FROM document WHERE usertoken='$usertoken' and deleted=1 ";
    if ($db->query($sql)==true) {
        $array=[
            'response'=>"13",
            'message'=>"Trash successfully cleared",
            'deleted status'=>"".$_SESSION['deleted'].""
        ];
        $array= json_encode($array);
        echo "$array";
        exit;
    }else {
        $array=[
            'response'=>"00",
            'message'=>"Not deleted"
        ];
        $array= json_encode($array);
        echo "$array";
        exit;
    }
}


// Time management function

// create reminder module

if ($action=="14") {
    $usertoken=$_REQUEST['usertoken'];
    $title=$_REQUEST['title'];
    $content=$_REQUEST['content'];
    $date=$_REQUEST["date"];
    $time=$_REQUEST["time"];
    $datetime=$date. " " .$time;
    if (reminder ($usertoken, $title, $content, $datetime, $timestamp, $db)==true) {
        $array = [
            'response' => "14",
            'message' => "Done",
            'token'=>"".$_SESSION['token'].""
            ];
            $array = json_encode($array);
            echo "$array";
            exit;
    }else {
            $array = [
            'response' => "00",
            'message' => "something went wrong"
            ];
            $array = json_encode($array);
            echo "$array";
            exit;
        
    }    
}



// to read schedule module

if ($action=='15') {
    $usertoken=$_REQUEST['usertoken'];
    $sql="SELECT * FROM time_mgt WHERE usertoken='$usertoken' and status=0 and deleted=0 ";
    if ($result=$db->query($sql)) {
        if ($result->num_rows>0) {
            echo "[";
            $count=0;
            while ($row=$result->fetch_array()) {
                $count=++$count;
                $num=$result->num_rows;
                $due_datetime=date("d-m-y H:i:s", $row['due_timestamp']);
                $array=[
                    'usertoken'=>"".$row['usertoken']."",
                    'token'=>"".$row['token']."",
                    'title'=>"".$row['title']."",
                    'content'=>"".$row['content']."",
                    'due_timestamp'=>"".$row['due_timestamp']."",
                    'due_datetime'=>"".$row['due_datetime']."",
                    'timestamp'=>"".$row['timestamp'].""
                ];
                $array=json_encode($array);
                if($num>$count){
                    echo "$array,";
                }else {
                    echo "$array";
                }
            }
            echo "]";
        }else {
            $array=[
                'response'=>"00",
                'message'=>"No result"
            ];
            $array=json_encode($array);
            echo "$array";
            exit;
        }
    }
}


// reminder update module

if ($action=='17') {
    $usertoken=$_REQUEST['usertoken'];
    $token=$_REQUEST['token'];
    $title=$_REQUEST['title'];
    $content=$_REQUEST["content"];
    $date=$_REQUEST["date"];
    $time=$_REQUEST["time"];
    $datetime=$date. " " .$time;
    if (updatereminder($usertoken, $title, $content, $datetime, $timestamp, $db)==true) {
        $array=[
            'response' => "17",
            'message' => "Updated",
            'token'=>"".$_SESSION['token'].""
        ];
        $array=json_encode($array);
        echo "$array";
        exit;
    }else {
        $array=[
            'response' => "00",
            'message' => "something went wrong",
            'token'=>"".$_SESSION['token']."" 
        ];
        $array=json_encode($array);
        echo "$array";
        exit;
    }
    
}


if ($action=='17') {
    $token=$_REQUEST['token'];
    $sql="DELETE FROM document WHERE usertoken='$usertoken' and deleted=1 ";
    if ($db->query($sql)==true) {
        $array=[
            'response'=>"17",
            'message'=>"Trash successfully cleared",
            'deleted status'=>"".$_SESSION['deleted'].""
        ];
        $array= json_encode($array);
        echo "$array";
        exit;
    }else {
        $array=[
            'response'=>"00",
            'message'=>"Not deleted"
        ];
        $array= json_encode($array);
        echo "$array";
        exit;
    }
}


// moving reminder to trash

// Trash update module for reminder
if ($action=='') {
    $usertoken=$_REQUEST['usertoken'];
    $token=$_REQUEST['token'];
    if (trashReminder ($usertoken,$token,$db)==true) {
        
        $array=[
            'response'=>"",
            'message'=>"Reminder moved to trash",
            'deleted status'=>"".$_SESSION['deleted'].""
        ];
        $array= json_encode($array);
        echo "$array";
        exit;
    }else {
        $array=[
            'response'=>"00",
            'message'=>"Token doesn't exist"
        ];
        $array= json_encode($array);
        echo "$array";
        exit;
    }
}

// Read Trash module for reminder
if ($action=='') {
    
    $usertoken=$_REQUEST['usertoken'];
    $sql="SELECT * FROM time_mgt WHERE usertoken='$usertoken' and status =1 and deleted = 1 ";
    if ($result=$db->query($sql)) {
        if ($result->num_rows>0) {
            echo "[";
            $count=0;
            while ($row=$result->fetch_array()) {
                $count= ++$count;
                $num=$result->num_rows;
                $array=[
                'usertoken'=>"".$row['usertoken']."",
                'token'=>"".$row['token']."",
                'title'=>"".$row['title']."",
                'content'=>"".$row['content']."",
                'due_timestamp'=>"".$row['due_timestamp']."",
                'due_datetime'=>"".$row['due_datetime']."",
                'timestamp'=>"".$row['timestamp']."",
                'num' => "$num",
                'count' => "$count"
                ];
                $array=json_encode($array);
                if ($num > $count) {
                    echo "$array,";
                }else {
                    echo "$array";
                }
            }
            echo "]";
        }else {
            $array =[
                'response'=>"00",
                'message'=>"No results"
            ];
            $array=json_encode($array);
            echo "$array";
            exit;
        }
    }
}


// Restore Trash module
if ($action=='') {
    $usertoken=$_REQUEST['usertoken'];
    $token=$_REQUEST['token'];
    if (restoretrashReminder($usertoken,$token, $db)==true) {
        $array=[
            'response'=>"",
            'message'=>"reminder has been successfully restored",
            'deleted status'=>"".$_SESSION['deleted'].""
        ];
        $array= json_encode($array);
        echo "$array";
        exit;
    }else {
        $array=[
            'response'=>"00",
            'message'=>"Token doesn't exist"
        ];
        $array= json_encode($array);
        echo "$array";
        exit;
    }
}


// Empty Trash module for reminder
if ($action=='') {
    $usertoken=$_REQUEST['usertoken'];
    $sql="DELETE FROM time_mgt WHERE usertoken='$usertoken' and status = 1 and deleted=1 ";
    if ($db->query($sql)==true) {
        $array=[
            'response'=>"",
            'message'=>"Trash successfully cleared",
            'deleted status'=>"".$_SESSION['deleted'].""
        ];
        $array= json_encode($array);
        echo "$array";
        exit;
    }else {
        $array=[
            'response'=>"00",
            'message'=>"Not deleted"
        ];
        $array= json_encode($array);
        echo "$array";
        exit;
    }
}

?>