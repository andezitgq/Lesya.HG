<?php

    $page_title = 'Головна';
    include 'tml/top.php';

?>
    <center><h1>Новини</h1></center>
    <div class=wrap-content-box id=wrap-content-box>
    <?php
    
        $all = R::getAll( 'SELECT * FROM postdate ORDER BY date DESC LIMIT 11' );
        for($i = -1; $i <= max(array_keys($all)); $i++){
            if(isset($all[$i])){
                $date = date_create($all[$i]['date']);
                $post = R::findOne('post', 'date_id = ?', array($all[$i]['id']));
                $author = R::findOne('postauthor', 'id = ?', array($post->author_id));
                $title = R::findOne('posttitle', 'id = ?', array($post->title_id));
                if($post->id != 88)
                {
                    echo '<div class=outpost><i class=post-date>';
                    
                        if(isset($date))
                            echo date_format($date, 'd.m.Y G:i');
                        if(isset($author->author))
                            echo ' 📝 '.$author->author;
                            
                    echo '</i>';
                    
                    echo '<div style=cursor:pointer onclick="window.location.href = \'showpost?postid='.$post->id.'\';" class=post>';

                            
                            if(isset($title->title))
                                echo '<h1>'.$title->title.'</h1><hr><br>';
                            if(isset($post->content))
                                echo $post->content;
                                
                    echo '</div></div>';
                }
            }
        }
    
    if(isset($post_var)) echo $post_var;
    
    ?>
    </div>
    <button class=show-more id=loadmore num_loaded=12>Показати ще</button>
<?php include 'tml/bottom.php'; ?>
