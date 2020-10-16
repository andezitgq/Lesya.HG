<?php

require 'include/db.php';

if (!isset($_GET['from'])) exit;
if (!isset($_GET['to'])) exit;

$from = $_GET['from'];
$to = $_GET['to'];
$diff = $from-$to;

$all = R::getAll('SELECT * FROM postdate ORDER BY date DESC LIMIT '.strval($from + 1).', '.strval($to + 1).'');
$posts = "";
for($i = -1; $i <= count($all); $i++){
    if(isset($all[$i])){
        $date = date_create($all[$i]['date']);
        $post = R::findOne('post', 'date_id = ?', array($all[$i]['id']));
        $author = R::findOne('postauthor', 'id = ?', array($post->author_id));
        $title = R::findOne('posttitle', 'id = ?', array($post->title_id));
        if($post->id != 88)
        {
            $posts .= '<div class=outpost><i class=post-date>';
            
                if(isset($date))
                    $posts .= date_format($date, 'd.m.Y G:i');
                if(isset($author->author))
                    $posts .= ' 📝 '.$author->author;
                    
            $posts .= '</i>';
            
            $posts .= '<div style=cursor:pointer onclick="window.location.href = \'showpost?postid='.$post->id.'\';" class=post>';

                    
                    if(isset($title->title))
                        $posts .= '<h1>'.$title->title.'</h1><hr><br>';
                    if(isset($post->content))
                        $posts .= $post->content;
                        
            $posts .= '</div></div>';
        }
    }
}

echo $posts;

?>  