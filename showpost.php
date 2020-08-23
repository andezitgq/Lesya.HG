<?php include 'tml/top.php'; ?>
    <?php
        if(!isset($_GET['postid']) || $_GET['postid'] == '' || $_GET['postid'] == 0)
            echo '<script>window.location.href = "/";</script>';
            
        $post = R::findOne('post', 'id = ?', array($_GET['postid']));
        if($post){
            $date = date_create(R::findOne('postdate', 'id = ?', array($post->date_id))->date);
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
