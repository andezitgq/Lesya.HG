<?php

    $page_title = 'Профіль';
    include 'tml/top.php';
    
    function saveToImgBB($image,$name = null){
        $API_KEY = 'ada56faf5a2545ab10970e17344ef4e4';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.imgbb.com/1/upload?key='.$API_KEY);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        $extension = pathinfo($image['name'],PATHINFO_EXTENSION);
        $file_name = ($name)? $name.'.'.$extension : $image['name'] ;
        $data = array('image' => base64_encode(file_get_contents($image['tmp_name'])), 'name' => $file_name);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return 'Error:' . curl_error($ch);
        }else{
            return json_decode($result, true);
        }
        curl_close($ch);
    }
    
    if(!isset($_SESSION['logged-user']) ||
       $_SESSION['logged-user']->login == 'root')
            echo '<script>window.location.href = "login";</script>';
            
    if(isset($_GET['id'])){
        if($_GET['id'] == 0 || $_GET['id'] == '')
            echo '<script>window.location.href = "profile";</script>';
        
        $user_is_you = false;
    } else {
        $user_is_you = true;
    }
    
    if(isset($_FILES['avatar-file'])){
        if(preg_match('/image/', $_FILES['avatar-file']['type']) && !preg_match('/svg/', $_FILES['avatar-file']['type'])){
            if($_FILES['avatar-file']['size'] <= 15728640) {
                $return = saveToImgBB($_FILES['avatar-file']);
                $avatar = R::findOne('userinfo', 'id = ?', array($_SESSION['logged-user']->userinfo));
                $avatar->avatar = $return['data']['url'];;
                R::store($avatar);
            } else
                echo "<h2 style='background: #1b466f; text-align:center; color: white; padding: 5px; border-radius: 10px'>Завантажений файл більше 15МБ!<h2>";
        } else
            echo "<h2 style='background: #1b466f; text-align:center; color: white; padding: 5px; border-radius: 10px'>Завантажений файл не є зображенням!<h2>";
    }
?>
<?php if ($user_is_you == true): ?>
    <?php
    
        $current_user = $_SESSION['logged-user'];
        $uinfo = R::findOne('userinfo', 'id = ?', array($current_user->userinfo));
    
    ?>
    <center><h1>Профіль</h1></center><br>
    <div class=profile>
        <div class=profile-preview>
            <form enctype="multipart/form-data" action=profile method=POST class=avatar id=avatar-form style="background: url('<?php echo $uinfo->avatar; ?>'); background-size: cover">
                <input type=file name=avatar-file class=avatar-file>
                <input type=submit name=avatar-submit style=display:none>
            </form>
            <div>
                <h2 class=fullname title="Імʼя та прізвище"><?php echo $current_user->fullname; ?></h3>
                <h3 class=nickname title="Нікнейм"><?php echo $current_user->login; ?></h3>
            </div>
        </div>
        <div class=profile-info>
            <div class="tab">
                <button class="tablinks" onclick="openTab(event, 'London')" id="defaultOpen">Інфо</button>
                <button class="tablinks" onclick="openTab(event, 'Paris')">Коментарі</button>
                <button class="tablinks" onclick="openTab(event, 'Tokyo')">Аккаунт</button>
            </div>
              
            <div id="London" class="tabcontent">
                <h3>London</h3>
                <p>London is the capital city of England.</p>
            </div>
              
            <div id="Paris" class="tabcontent">
                <h3>Paris</h3>
                <p>Paris is the capital of France.</p>
            </div>
              
            <div id="Tokyo" class="tabcontent">
                <h3>Tokyo</h3>
                <p>Tokyo is the capital of Japan.</p>
            </div>
        </div>
    </div>
<?php else: ?>
    SAS
<?php endif; ?>

<?php include 'tml/bottom.php'; ?>