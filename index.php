<?php include 'tml/top.php'; ?>
    <div class=wrap-content-box>
    <?php
    
        $all = R::getAll( 'SELECT * FROM postdate ORDER BY date DESC LIMIT 15' );
        for($i = -1; $i <= count($all); $i++){
            if(isset($all[$i])){
                $date = date_create($all[$i]['date']);
                $post = R::findOne('post', 'date_id = ?', array($all[$i]['id']));
                $author = R::findOne('postauthor', 'id = ?', array($post->author_id));
                $title = R::findOne('posttitle', 'id = ?', array($post->title_id));
                if($post->id != 88)
                    echo '<div class=post>
                              <i class=post-date>'.date_format($date, 'd.m.Y G:i').' 📝 '.$author->author.'</i>
                              <h1>'.$title->title.'</h1><hr><br>'.
                              $post->content.
                          '</div>';
            }
        }
    
    ?>
    </div>
<?php include 'tml/bottom.php'; ?>
