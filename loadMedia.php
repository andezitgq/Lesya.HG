<?php

require 'include/db.php';

$all = R::getAll('SELECT * FROM postdate ORDER BY date DESC LIMIT 10');
$posts = $_GET['albumid'];
/*for($i = -1; $i <= count($all); $i++){
    if(isset($all[$i])){
        $date = date_create($all[$i]['date']);
        $post = R::findOne('post', 'date_id = ?', array($all[$i]['id']));
        $author = R::findOne('postauthor', 'id = ?', array($post->author_id));
        $title = R::findOne('posttitle', 'id = ?', array($post->title_id));
        $posts .= '</div></div>';
    }
}*/

echo $posts;

?>  