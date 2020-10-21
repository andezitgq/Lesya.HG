<?php

require 'include/db.php';

$photos = "";
$all = R::find('photos', 'albumid = ?', array($_GET['albumid']));
for($i = -1; $i <= max(array_keys($all)); $i++){
    if(isset($all[$i])){
        $photos .= '<div class="m-photo">'.
                        '<img src="'.$all[$i]->source.'">'.
                        '<p class=m-photo-discription>'.$all[$i]->discription.'</p>'.
                   '</div>';
    }
}

echo $photos;

?>  