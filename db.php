<?php
if(count(get_included_files()) ==1) exit();
function connect(){
    return mysqli_connect("localhost", "root", "", "car");
}
function clean($input){
        $input = (string) $input;
        if (get_magic_quotes_gpc())
        {
                $input = stripslashes($input);
        }
        $output = htmlentities($input, ENT_QUOTES, 'UTF-8');
        
        return $output;
}
