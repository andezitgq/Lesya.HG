<?php

    $page_title = 'Редактор постів';
    include 'tml/top.php';

    $test_post = R::findOne('post', 'id = ?', array($_GET['postid']));
    if(!isset($_GET['postid']) ||
       !isset($test_post)      ||
       $_GET['postid'] == ''   ||
       $_GET['postid'] == 0    ||
       $_SESSION['logged-user']->login != 'root')
            echo '<script>window.location.href = "/";</script>';

    if(isset($_GET['postid']) &&
        isset($test_post)      &&
        $_GET['postid'] != ''   &&
        $_GET['postid'] != 0    &&
        $_SESSION['logged-user']->login == 'root' &&
        isset($_POST['save-post']) &&
        isset($_POST['post-field']))
    {
        $test_post->content = $_POST['post-field'];
        R::store($test_post);
        echo 'sas';
    }

?>

<form method=POST action="editpost?postid=<?php echo $_GET['postid']; ?>" class="postedit">
    <h1>Редактор постів</h1>
    <button id="save-post" name="save-post" class="save-post">Зберегти</button>
    <textarea name="post-field" id="post-field" class="post-edit-field">
        <?php echo $test_post->content; ?>
    </textarea>
</form>

<?php include 'tml/bottom.php'; ?>