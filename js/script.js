$('document').ready(function(){

    $(document).on("click", ".m-photo img", function(){
        $('.mv-preview').attr('src', $(this).attr('src'));
        $('.media-viewer').toggle('slide');
    });

    $('.media-viewer').click(function(){
        $('.media-viewer').toggle('slide');
    });

    //$( "li:has(> .caret)" ).css( "background", "#13324f" );

    var toggler = document.getElementsByClassName("caret");
    var i;

    for (i = 0; i < toggler.length; i++) {
        toggler[i].addEventListener("click", function() {
            this.parentElement.querySelector(".nested").classList.toggle("active-ul");
            this.classList.toggle("caret-down");
        });
    } 

    $('#loadmore').click(function(){
        var loaded = $(this).attr('num_loaded');
        $('#loadmore').attr('disabled', true);
        $.ajax({
            url:'loadPosts.php',
            type:'get',
            data:{'from':loaded,'to':+loaded+10},
            success: function (res) {
                $('#wrap-content-box').append(res);
                $('#loadmore').attr('num_loaded',+loaded+10);
                $('#loadmore').attr('disabled', false);
            }
        });
    });

    $('.m-page-album').click(function(){
        var albumid = $(this).attr('albumid');
        $.ajax({
            url:'loadMedia.php',
            type:'get',
            data:{'albumid':albumid},
            success: function (res) {
                $('.m-page-photos').html(res);
                window.location.href = "#photos";
            }
        });
    });

    $('.nav-mob').click(function(){
        $('.full-nav').toggle('slow'); 
    });
    $('.full-nav').click(function(){
        $('.full-nav').toggle('slow'); 
    });
    
    $('.soc-nav-button').click(function(){
        $('.social-nav').toggle('slow');    
    });
    
    $('.edit-btn').on('click', function() {
        var cursorPos = $('.post-editor').prop('selectionStart');
        var v = $('.post-editor').val();
        var textBefore = v.substring(0,  cursorPos);
        var textAfter  = v.substring(cursorPos, v.length);
    
        $('.post-editor').val(textBefore + $(this).val() + textAfter);
    });

    function setSel(sep1, sep2){
        var txtarea = document.getElementById("post-editor");
        
        var start = txtarea.selectionStart;
        var finish = txtarea.selectionEnd;
        var allText = txtarea.value;
    
        var sel = allText.substring(start, finish);
        var newText=allText.substring(0, start)+sep1+sel+sep2+allText.substring(finish, allText.length);
    
        txtarea.value=newText;    
    }
    
    function setList(){
        var txtarea = document.getElementById("post-editor");
        
        var start = txtarea.selectionStart;
        var finish = txtarea.selectionEnd;
        var allText = txtarea.value;
    
        var sel = allText.substring(start, finish).split('\n').join('\n* ');
        var newText=allText.substring(0, start)+"* "+sel+"\n"+allText.substring(finish, allText.length);
    
        txtarea.value=newText;    
    }
    
    $('#bold').on('click', function(){
        setSel("**", "**");
    });
    
    $('#italic').on('click', function(){
        setSel("_", "_");
    });
    
    $('#underlined').on('click', function(){
        setSel("<ins>", "</ins>");
    });
    
    $('#intersected').on('click', function(){
        setSel("~~", "~~");
    });
    
    $('#list-btn').on('click', function(){
        setList();
    });
    
    $('.icon-cancel-circled').on('click', function(){
        document.getElementById("post-editor").value = null;
    });

    $( ".post-headers" ).change(function(){
        if($(this).val() == 'Заголовок 1') {
            setSel("# ", "");
        } else if($(this).val() == 'Заголовок 2') {
            setSel("## ", "");
        } else if($(this).val() == 'Заголовок 3') {
            setSel("### ", "");
        } else if($(this).val() == 'Заголовок 4') {
            setSel("#### ", "");
        } else if($(this).val() == 'Заголовок 5') {
            setSel("##### ", "");
        } else if($(this).val() == 'Заголовок 6') {
            setSel("###### ", "");
        } else {
            setSel("", "");
        }
        $(this).val(0);
    });
    
    function removeLocationHash(){
        var noHashURL = window.location.href.replace(/#.*$/, '');
        window.history.replaceState('', document.title, noHashURL); 
    }
    window.addEventListener("popstate", function(event){
        removeLocationHash();
    });
    window.addEventListener("hashchange", function(event){
        event.preventDefault();
        removeLocationHash();
    });
    window.addEventListener("load", function(){
        removeLocationHash();
    });
        
});