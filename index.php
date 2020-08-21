<?php include 'tml/top.php'; ?>
    <?php
    
        $all = R::findAll('postdate',' ORDER BY date ASC LIMIT 10');
        for($i = count($all); $i >= 1; $i--){
            $date = date_create($all[$i]->date);
            echo date_format($date, 'd.m.Y G:i')."<br>";
            $post = R::findOne('post', 'date_id = ?', array($all[$i]->id));
            echo $post->content;
        }
    
    ?>
<?php include 'tml/bottom.php'; ?>
