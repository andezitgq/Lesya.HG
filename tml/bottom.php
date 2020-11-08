        </div>
        <script src="https://cdn.plyr.io/3.6.2/plyr.js"></script>
        <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <script src="https://unpkg.com/pdfjs-dist@2.0.489/build/pdf.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"> </script> 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.58.2/codemirror.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.58.2/mode/php/php.min.js"></script>
        <script src="js/pdflesya.js"></script>
        <?php if(isset($add_bottom)) echo $add_bottom; ?>
        <script>            
            function openTab(evt, tabID) {
                var i, tabcontent, tablinks;
              
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                  tabcontent[i].style.display = "none";
                }
              
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                  tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
              
                document.getElementById(tabID).style.display = "block";
                evt.currentTarget.className += " active";
            } 
            
            document.getElementById("defaultOpen").click();

            document.getElementById("avatar-form").onchange = function() {
                document.getElementById("avatar-form").submit();
            };
        </script>
        <script>
            const player = Plyr.setup('.jplayer');
            
            function download(download_url, name){ 
                axios({ 
                    url:download_url, 
                    method:'GET', 
                    responseType: 'blob' 
                }).then((response) => { 
                      const url = window.URL 
                      .createObjectURL(new Blob([response.data])); 
                              const link = document.createElement('a'); 
                              link.href = url; 
                              link.setAttribute('download', name); 
                              document.body.appendChild(link); 
                              link.click(); 
                }) 
            } 

            function commentReply(comid){
              var txtarea = document.getElementById("comment-field");          
              var newText = "#" + comid + "# " + txtarea.value;
              txtarea.value = newText;
            }

        </script>
        <script>
          
          var myTextarea = document.getElementById("post-field");
          var editor = CodeMirror.fromTextArea(myTextarea, {
            lineNumbers: true,
            mode:  "php"
          });
        </script>
        <!-- <<< Валечка, я тебя люблю! >>> -->
    </body>
</html>