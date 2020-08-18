        </div>
        <script src="https://cdn.plyr.io/3.6.2/plyr.js"></script>
        <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <script>
            const player = Plyr.setup('.jplayer');
            
            var mySwiper = new Swiper('.swiper-container', {
                direction: 'horizontal',
                loop: true,
              
                pagination: {
                  el: '.swiper-pagination',
                },
              
                navigation: {
                  nextEl: '.swiper-button-next',
                  prevEl: '.swiper-button-prev',
                },
                
                slidesPerView: 3,
                
                centeredSlides: true
            });
        </script>
    </body>
</html>