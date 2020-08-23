        </div>
        <script src="https://cdn.plyr.io/3.6.2/plyr.js"></script>
        <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.min.js"></script>
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
            
            var PDFViewer = {
                pdf: null,
                currentPage: 1,
                zoom: 1
            }
            
            pdfjsLib.getDocument('img/Вступ.pdf').then((pdf) => {
            
            });
        </script>
    </body>
</html>