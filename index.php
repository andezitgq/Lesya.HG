<?php include 'tml/top.php'; ?>
    <?php
    
        $all = R::findAll('postdate',' ORDER BY date ASC LIMIT 10');
        for($i = count($all); $i >= 1; $i--){
            $date = date_create($all[$i]->date);
            $post = R::findOne('post', 'date_id = ?', array($all[$i]->id));
            $author = R::findOne('postauthor', 'id = ?', array($post->author_id));
            $title = R::findOne('posttitle', 'id = ?', array($post->title_id));
            echo '<div class=post>
                      <i class=post-date>'.date_format($date, 'd.m.Y G:i').' 📝 '.$author->author.'</i>
                      <h1>'.$title->title.'</h1><hr><br>'.
                      $post->content.
                  '</div>';
        }
    
    ?>
<?php include 'tml/bottom.php'; ?>
