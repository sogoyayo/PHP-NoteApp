<?php

// token generator function
function generateAlphaNumericOTP($n){
    // Take a generator string which consist of all numeric digits
    $generator="1357902468abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $result="";
    for ($i=1; $i<=$n ; $i++) { 
        $result.=substr($generator,(rand(strlen($generator))),1);
    }
    // Return result
    return $result;
}


//Signup functions

function register ($firstname, $lastname, $email, $password, $username, $timestamp, $db){
    $usertoken=generateAlphaNumericOTP(6);
    $usertoken="TH".$usertoken;
    $sql="INSERT INTO signup (usertoken, firstname, lastname, mail, password, username, timestamp) VALUES ('$usertoken', '$firstname', '$lastname', '$email', '$password', '$username', '$timestamp')";
    if ($db->query ($sql)) {
        $_SESSION['usertoken']="$usertoken";
        return true;
        exit;
    }else {
        return false;
        exit;
    }
}

// function to check if email exist in the database
function existing ($email,$username,$db){
    $sql= "SELECT * FROM signup WHERE mail='$email' and username='$username' ";
    if ($result= $db->query ($sql)) {
        if ($result->num_rows > 0) {
            if ($row= $result-> fetch_array()) {
                return false;
            }else {
                return true;
            }
        }else {
            return true;
        }
    }
}


//login function to update timestamp
function timeupdate ($email,$passsword,$timestampupdate,$db) {
    $sql= "UPDATE signup SET timestampupdate='$timestampupdate' WHERE mail='$email'" ;
    if ($db->query ($sql)) {
    return true;
    }
}


// document function (to create fodler or file)

function document ($type, $document_name, $usertoken, $folder_token, $timestamp, $db){
    $token=generateAlphaNumericOTP(6);
    $token="F".$token;
    $sql="INSERT INTO document (token, type, document_name, usertoken, folder_token, timestamp) VALUES ('$token', '$type', '$document_name', '$usertoken', '$folder_token', '$timestamp')";
    if ($db->query ($sql)) {
        $_SESSION['token']="$token";
        $_SESSION['usertoken']="$usertoken";
        return true;
        exit;
    }else {
        return false;
        exit;
    }
}


// content insert function
function content ($usertoken, $file_token, $content, $timestamp, $db){
    $content_token=generateAlphaNumericOTP(6);
    $content_token="C".$token;
    // $token=$file_token;
    $sql="INSERT INTO content_table (usertoken, content_token, file_token, content, timestamp) VALUES ('$usertoken', '$$content_token', '$file_token', '$content', '$timestamp')";
    if ($db->query ($sql)) {
        $_SESSION['content_token']="$content_token";
        $_SESSION['file_token']="$file_token";
        return true;
        exit;
    }else {
        return false;
        exit;
    }
}

// content update function
function contentupdate ($content_token, $content, $timestamp, $db){
$sql="UPDATE content_table SET content='$content' WHERE content_token='$content_token'";
if ($db->query ($sql)) {
    $_SESSION['usertoken']="$usertoken";
    return true;
    exit;
}else {
    return false;
    exit;
}
}

// function to get usertoken from document table with file_token
function getusertoken(){
    $sql="SELECT * FROM document WHERE token='$file_token'";
    if ($result=$db->query($sql)) {
        if ($result->num_rows>0) {
            if ($row=$result->fetch_array()) {
                $usertoken=$row['iusertoken'];
                return $usertoken;
                exit;
            }else {
                return false;
                exit;
            }
        }
    }

}

// document update function
function documentupdate ($token, $document_name, $timestamp, $db){
    $sql="UPDATE document SET document_name='$document_name' where token='$token' and deleted=0 ";
    if ($db->query($sql)) {
        $_SESSION['token']="$token";
        return true;
        exit;
    }else {
        return false;
        exit;
    }
}

// function to check if token exist
function checkToken($token, $db){
    $sql="SELECT * FROM document WHERE token='$token'";
    if ($result=$db->query($sql)) {
        if ($result->num_rows > 0) {
            if ($row=$result->fetch_array()) {
                return true;
                exit;       
            }else {
                return false;
                exit;
            }
        }else {
            return false;
            exit;
        }
    }
}

//moving document to trash function
function trashDocument($token, $db){
    $sql= "UPDATE document SET deleted=1 WHERE folder_token='$token'";
    if ($db->query($sql)) {
        $_SESSION['deleted']= 1;
        return true;
        exit;   
    } else {
        return false;
        exit;
    }
}


//moving file in a folder to trash function
function trashFile($token, $db){
    $sql= "UPDATE content_table SET deleted=1 WHERE token='$folder_token'";
    if ($db->query($sql)) {
        $_SESSION['deleted']= 1;
        return true;
        exit;   
    } else {
        return false;
        exit;
    }
}

//moving content to trash function
function trashContent($token, $db){
    $sql= "UPDATE content_table SET deleted=1 WHERE file_troken='$token'";
    if ($db->query($sql)) {
        $_SESSION['deleted']= 1;
        return true;
        exit;   
    } else {
        return false;
        exit;
    }
}



// to restore trash with same folder token
function restorefoldertrash($token,$db){
    $sql=" UPDATE document SET deleted=0 WHERE folder_token='$token' ";
    if ($db->query($sql)) {
        $_SESSION['deleted']= 0;
        return true;
        exit;   
    } else {
        return false;
        exit;
    }
}


//to restore trash at content table with file token
function restoretrashContent($token, $db){
    $sql= "UPDATE content_table SET deleted=0 WHERE file_troken='$token'";
    if ($db->query($sql)) {
        $_SESSION['deleted']= 1;
        return true;
        exit;   
    } else {
        return false;
        exit;
    }
}




// Time management function

// function to create reminder
function reminder ($usertoken, $title, $content, $datetime, $timestamp, $db){
    $token=generateAlphaNumericOTP(6);
    $token="TM".$token;
    $due_timestamp=strtotime($datetime);
    $sql="INSERT INTO time_management (usertoken, token, title, content, due_timestamp, timestamp) VALUES ('$token', '$title', '$content', '$alarm', '$alarmtime', '$due_timestamp', '$timestamp')";
    if ($db->query ($sql)) {
        $_SESSION['token']="$token";
        // $_SESSION['usertoken']="$usertoken";
        return true;
        exit;
    }else {
        return false;
        exit;
    }
}

// reminder update function 
function updatereminder($usertoken, $title, $content, $datetime, $timestamp, $db){
    $due_timestamp=strtotime($datetime);
    $sql= "UPDATE time_management SET title='$title', content='$content', due_timestamp='$due_timestamp' WHERE token='$token' and status=0 and deleted=0";
    if ($db->query($sql)) {
        return true;
        exit;   
    } else {
        return false;
        exit;
    }
}

// Trash function for Time management

// to move reminder to trash
function trashReminder ($usertoken,$token,$db)
{
    $sql=" UPDATE time_mgt SET deleted=1 WHERE status=1 and usertoken = '$usertoken' and token='$token' ";
    if ($db->query($sql)) {
        $_SESSION['deleted']= 0;
        return true;
        exit;   
    } else {
        return false;
        exit;
    }
}


// to restore trash for reminder 
function restoretrashReminder(%$usertoken,$token, $db){
    $sql= "UPDATE time_mgt SET deleted=0 WHERE usertoken='$token'";
    if ($db->query($sql)) {
        $_SESSION['deleted']= 1;
        return true;
        exit;   
    } else {
        return false;
        exit;
    }
}


// cron job to update status

function cron($usertoken, $token, $db)
{
    $sql= "UPDATE time_mgt SET status = 1 WHERE usertoken='$usertoken' and token = '$token'";
    if ($db->query($sql)) {
        $_SESSION['status']= 1;
        return true;
        exit;   
    } else {
        return false;
        exit;
    }
}































// //Calculator functions

// function calculator ($a, $b, $operator, $answer, $timestamp, $db){
//     $sql="INSERT INTO calculator (a, b, operator, answer, timestamp) VALUES ('$a', '$b', '$operator', '$answer', '$timestamp')";

//     if ($db->query ($sql)) {
//         exit;
//     }else {
//         //  echo "not done";
//         //  exit;
//     }
//     $db->close();
// }

// function operation($a,$b,$operator,$timestamp,$db)
// {
//     if($operator== 'sum'){
//         $answer= $a + $b;
//         calculator ($a, $b, $operator, $answer, $timestamp, $db);
//         return $answer;
//     }
//     elseif ($operator== 'subtract') {
//         $answer= $a - $b;
//         calculator ($a, $b, $operator, $answer, $timestamp, $db);
//         return $answer;
//     }
//     elseif ($operator== 'multiply') {
//         $answer= $a * $b;
//         calculator ($a, $b, $operator, $answer, $timestamp, $db);
//         return $answer;
//     }
//     elseif ($operator== 'divide') {
//         $answer= $a / $b;
//         calculator ($a, $b, $operator, $answer, $timestamp, $db);
//         return $answer;
//     }
// }


?>