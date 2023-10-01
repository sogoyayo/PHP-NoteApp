<?php

if (action=='') {
    $sql="SELECT * FROM time_mgt WHERE deleted=0 and status=0 and due_timestamp='$timestamp'
    UNION ALL
    SELECT * FROM time_mgt WHERE deleted=0 and status=0 and due_timestamp < '$timestamp'";
    if ($result=$db->query($sql)) {
        if ($result->num_rows>0) {
            if (function cron($usertoken, $token, $db)==true) {
                echo "[";
                $count=0;
                while ($row=$result->fetch_array()) {
                    $count=++$count;
                    $num=$result->num_rows;
                    $array[
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
            }
        }else {
            $array[
                'response'=>"00",
                'message'=>"No result"
            ];
            $array=json_encode($array);
            echo "$array";
            exit;
        }
    }
}




?>