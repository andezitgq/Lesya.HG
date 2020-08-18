<?php include 'tml/top.php'; ?>

    <h1 align=center>Наша галерея</h1>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php
        
            $gallery = json_decode(file_get_contents('./gallery.json'), true);
            
            var_dump($gallery);
            
            for($i = 0; $i < count($gallery); $i++){
                echo '<div class="swiper-slide">'.
                array_keys($gallery)[$i].
                '<img src="'.$gallery[array_keys($gallery)[$i]]['poster'].'">'.'</div>';
            }
            
            ?>
        </div>
        <div class="swiper-pagination"></div>
    
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
<?php include 'tml/bottom.php'; ?>
