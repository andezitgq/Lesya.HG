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
       $_SESSION['logged-user']->login == 'root' && !isset($_GET['id']))
            echo '<script>window.location.href = "/";</script>';
            
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
                echo "<h2 style='background: #1b466f; text-align:center; color: white; padding: 5px; border-radius: 10px'>Завантажений файл більше 15МБ!</h2>";
        } else
            echo "<h2 style='background: #1b466f; text-align:center; color: white; padding: 5px; border-radius: 10px'>Завантажений файл не є зображенням!</h2>";
    }
    
    if(isset($_POST['change-uinfo'])){
        $udiscr = R::findOne('userinfo', 'id = ?', array($_SESSION['logged-user']->userinfo));
        $udiscr->aboutme = $_POST['change-uinfo'];
        R::store($udiscr);
    }
    
    if(isset($_GET['unset-session'])){
        unset($_SESSION['logged-user']);
        echo '<script>window.location.href = "/";</script>';
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
                <button class="tablinks" onclick="openTab(event, 'Info')" id="defaultOpen">Інфо</button>
                <button class="tablinks" onclick="openTab(event, 'Comments')">Коментарі</button>
                <button class="tablinks" onclick="openTab(event, 'Account')">Аккаунт</button>
            </div>
              
            <form method=POST action=profile id="Info" class="tabcontent">
                <h2>Інфо</h2><hr><br>
                <p class=icon-statusnet>Статус: <?php echo $uinfo->actype; ?></p>
                <p class=icon-commenting>Коментарі: <?php echo $uinfo->comments; ?></p>
                <p class=icon-rocket>Дата реєстрації: <?php echo $uinfo->regdate; ?></p>
                <p class=icon-text-width>Про мене: <br><br>
                    <span><i class=icon-quote-left></i><input style="border:none; border-bottom: 2px solid #1b466f" required name=change-uinfo type=text value="<?php echo $uinfo->aboutme; ?>"><i class=icon-quote-right></i></span>
                </p>
            </form>
              
            <div id="Comments" class="tabcontent">
                <h2>Коментарі</h2><hr><br>
            </div>
              
            <div id="Account" class="tabcontent">
                <h2>Управління аккаунтом</h2><hr><br>
                <a href="?unset-session">Вийти з аккаунту</a>
            </div>
        </div>
    </div>
<?php else: ?>
    <?php
    
        $current_user = R::findOne('users', 'id = ?', array($_GET['id']));
        $uinfo = R::findOne('userinfo', 'id = ?', array($current_user->userinfo));
    
    ?>
    <?php if($current_user): ?>
        <?php if($current_user->id != $_SESSION['logged-user']->id): ?>
            <?php if($current_user->login != 'root'): ?>
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
                            <button class="tablinks" onclick="openTab(event, 'Info')" id="defaultOpen">Інфо</button>
                        </div>
                          
                        <div id="Info" class="tabcontent">
                            <h2>Інфо</h2><hr><br>
                            <p class=icon-statusnet>Статус: <?php echo $uinfo->actype; ?></p>
                            <p class=icon-commenting>Коментарі: <?php echo $uinfo->comments; ?></p>
                            <p class=icon-rocket>Дата реєстрації: <?php echo $uinfo->regdate; ?></p>
                            <p class=icon-text-width>Про мене: <br><br>
                                <span><i class=icon-quote-left></i><?php echo $uinfo->aboutme; ?><i class=icon-quote-right></i></span>
                            </p>
                        </div>
                          
                        <div id="Comments" class="tabcontent">
                            <h2>Коментарі</h2><hr><br>
                        </div>
                          
                        <div id="Account" class="tabcontent">
                            <h2>Управління аккаунтом</h2><hr><br>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                    <center><h1 style=font-size:25px>НЕМА ДОСТУПУ</h1></center>
                <?php endif; ?>
        <?php else: ?>
            <?php
    
                $current_user = $_SESSION['logged-user'];
                $uinfo = R::findOne('userinfo', 'id = ?', array($current_user->userinfo));
            
            ?>
            <?php if($current_user->login != 'root'): ?>
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
                        <button class="tablinks" onclick="openTab(event, 'Info')" id="defaultOpen">Інфо</button>
                        <button class="tablinks" onclick="openTab(event, 'Comments')">Коментарі</button>
                        <button class="tablinks" onclick="openTab(event, 'Account')">Аккаунт</button>
                    </div>
                      
                    <form method=POST action=profile id="Info" class="tabcontent">
                        <h2>Інфо</h2><hr><br>
                        <p class=icon-statusnet>Статус: <?php echo $uinfo->actype; ?></p>
                        <p class=icon-commenting>Коментарі: <?php echo $uinfo->comments; ?></p>
                        <p class=icon-rocket>Дата реєстрації: <?php echo $uinfo->regdate; ?></p>
                        <p class=icon-text-width>Про мене: <br><br>
                            <span><i class=icon-quote-left></i><input style="border:none; border-bottom: 2px solid #1b466f" required name=change-uinfo type=text value="<?php echo $uinfo->aboutme; ?>"><i class=icon-quote-right></i></span>
                        </p>
                    </form>
                      
                    <div id="Comments" class="tabcontent">
                        <h2>Коментарі</h2><hr><br>
                    </div>
                      
                    <div id="Account" class="tabcontent">
                        <h2>Управління аккаунтом</h2><hr><br>
                    </div>
                </div>
            </div>
            <?php else: ?>
                <center><h1 style=font-size:25px>НЕМА ДОСТУПУ</h1></center>
            <?php endif; ?>
        <?php endif; ?>
    <?php else: ?>
        <?php echo '<center><h1 style=font-size:25px>Користувач за таким ID не знайдений!</h1></center>'; ?>
    <?php endif; ?>
    
    
<?php endif; ?>

<?php include 'tml/bottom.php'; ?>