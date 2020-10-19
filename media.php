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
                        echo '<div class="swiper-slide m-page-album">'.
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
<?php include 'tml/bottom.php'; ?>
