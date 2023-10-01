<?php

// strlen() function
// the strlen() function returns the length of a string

// $generator="1357902468abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

// echo rand(strlen($generator));
// echo mt_rand(10,100);

// function generateAlphaNumericOTP($n){
//     // Take a generator string which consist of all numeric digits
//     $generator="1357902468abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
//     $result="";
//     for ($i=1; $i<=$n ; $i++) { 
//         $result.=substr($generator,(rand(strlen($generator))),1);
//     }
//     // Return result
//     return $result;
// }

// generateAlphaNumericOTP(7);


// PHP Random string using Brute Force
$n=10;

function getName($n)
{
    $characters ="013579024689abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $result="";
    for ($i=1; $i<=$n ; $i++) {
        $index = rand(0,strlen($characters)-1);

        $result .= $characters[$index];
    }

    return $result;
    

}
$characters ="13579024689abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
echo getName($n);
echo '<br>'. strlen($characters). '<br>' . $characters[0];


// // PHP Random string using Hashing function

// $str = rand();

// $result=md5($str);

// echo $result;

// // PHP Random strings using the in-built uniqid() function

// $result = uniqid();

// echo $result;

// // PHP Random strings using random_bytes() and bin2hex()

// $n =20;

// $result = bin2hex(random_bytes($n));

// echo $result;

?>