<?php

    $page_title = 'Медіа';
    include 'tml/top.php';
    
?>

    <h1 align=center>Наша галерея</h1>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php

                $albums = R::getAll( 'SELECT * FROM albums ORDER BY id ASC' );
                for($i = -1; $i <= max(array_keys($albums)); $i++){
                    if(isset($albums[$i])){
                        echo '<div class="swiper-slide m-page-album" albumid='.$albums[$i]['albumid'].'>'.
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
    <a name=photos class="anchor"></a>
    <h1 align=center>Фото</h1>
    <div class="m-page-photos"><h3 style="margin: auto; margin-top: 20px; font-style: italic">Виберіть альбом</h3></div>
    <div class="media-viewer" style="display: none">
        <img src="https://i.ibb.co/f96vZ3D/1-jpg.jpg" class="mv-preview">
    </div>
<?php

    $add_bottom = "<script>var swiper = new Swiper('.swiper-container', {
        slidesPerView: 3,
        centeredSlides: true,
        loop: true,
        pagination: {
          el: '.swiper-pagination',
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        on: {
          init: function () {
            $('.swiper-slide-active .m-page-poster').addClass('active');
          },
          transitionStart: function() {
            $('.m-page-poster').removeClass('active');
          },
          transitionEnd: function(swiper) {
            $('.swiper-slide-active .m-page-poster').addClass('active');
          }
        }
      });</script>";
    include 'tml/bottom.php';

?>
