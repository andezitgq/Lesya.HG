<?php

    $page_title = 'Медіа';
    include 'tml/top.php';
    
?>

    <h1 align=center>Наша галерея</h1>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php

                $albums = R::getAll( 'SELECT * FROM albums ORDER BY id ASC' );
                for($i = -1; $i <= count($albums); $i++){
                    if(isset($albums[$i])){
                        echo '<div class="swiper-slide m-page-album" albumid='.$albums[$i]['id'].'>'.
                                '<img class=m-page-poster src="'.$albums[$i]['poster'].'"/>'.
                                '<p class=m-page-discription>'.$albums[$i]['discription'].'</p>'.
                             '</div>';
                    }
                }

            ?>
        </div>
        <div class="swiper-pagination"></div>
    
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
    <br>
    <h1 align=center>Фото</h1>
    <div class="m-page-photos">
        <div class="m-photo"><img src="https://i.ibb.co/f96vZ3D/1-jpg.jpg"></div>
        <div class="m-photo"><img src="https://i.ibb.co/f96vZ3D/1-jpg.jpg"></div>
        <div class="m-photo"><img src="https://i.ibb.co/f96vZ3D/1-jpg.jpg"></div>
        <div class="m-photo"><img src="https://i.ibb.co/f96vZ3D/1-jpg.jpg"></div>
    </div>
<?php include 'tml/bottom.php'; ?>
