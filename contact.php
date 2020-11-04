<?php

    $page_title = 'Звʼязок';
    include 'tml/top.php';

    $data = $_POST;

    if(isset($data['send_mail'])){
        $conf_subject = $data['topic'];
            
            $headers = "From: ".$data['name']." <".$data['email'].">\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $msg = $data['message'];
                    
            mail('freelogger@localhost', $conf_subject, $msg, $headers);
    }

?>

<div class="contact-main">
    <form action="contact" class=contact-form method="post">
        <h2 align="center">Прямий звʼязок</h2>
        <input name=name id=name type="text" placeholder="Ваше ім'я" required>
        <input name=email id=email type="email" placeholder="Ваш E-mail" required>
        <input name=topic id=topic type="text" placeholder="Тема листа" required>
        <textarea name="message" id="message" placeholder="Ваше повідомлення" style="resize: vertical" required></textarea>
        <button type="submit" name=send_mail id=send_mail>Відправити</button>
    </form>
    <div class="contact-info">
        <h2 align="center">Наші контакти:</h2>
        <center>
            <p>52210 Україна Дніпропетровська область <br>м.Жовті Води, бульвар Свободи, 18<br>&#160 тел.:(099) 607 78 74, (096) 600 77 10,&#160,&#160<br> e-mail: <a href="mailto:lesya_zhv@ukr.net">lesya_zhv@ukr.net</a></p>
            <br>
            <a href="http://dnepredu.com/" target="_blank">
                <img src="http://lesya.org/images/portal.gif" height="38">
            </a>
            <a href="http://goroozv.dnepredu.com/" target="_blank">
                <img src="http://lesya.org/images/miskvo.gif" height="38">
            </a>
        </center>   
    </div>
</div>

<?php include 'tml/bottom.php'; ?>