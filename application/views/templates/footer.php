    <!--Footer-->
    <footer class="page-footer unique-color-dark   center-on-small-only pt-0">

        <!--Copyright-->
        <div class="footer-copyright">
            <div class="container-fluid">
              &copy; 2019 <a href=""> Mahindra AFS </a>
            </div>
        </div>
        <!--/Copyright-->

    </footer>
    <!--/Footer-->
  <!-- SCRIPTS -->
    <!-- JQuery -->
	<!-- JQuery -->
   
  
  <!-- MDB core JavaScript -->
    <script type="text/javascript" src="<?php echo base_url()?>js/mdb.min.js"></script>




	<script type="text/javascript">
	// $(function() {
	    // $("#dt").datepicker();
	// });
	</script>

 	<script src="<?php echo base_url()?>js/editor.js"></script>
    <script type="text/javascript">
        /*new WOW().init();
        $(document).ready(function () {

            $("#txtEditor").Editor({
                'texteffects': false,
                'aligneffects': false,
                'fonteffects': false,
                'actions': false,
                'insertoptions': false,
                'extraeffects': false,
                'advancedoptions': false,
                'screeneffects': false,
                'source': false,
                'strikeout': true,
                'indent': false,
                'outdent': false,
                'rm_format': false,
                'status_bar': false,
                'togglescreen': false
            });

        });
        */
        
	/*ClassicEditor
        .create( document.querySelector( '#txtEditor' ), 
        {toolbar: [ 'Heading', 'Link','|','bold', 'italic','|', 'undo', 'redo' ,'|', 'bulletedList', 'numberedList' ]})
        .catch( error => {
        console.error( error );
    } );*/
    
    CKEDITOR.editorConfig = function(config) {
	    config.autoParagraph = false;
	    config.enterMode = CKEDITOR.ENTER_BR; 
	    config.shiftEnterMode = CKEDITOR.ENTER_BR;
	}
	
</script>
<script>
$(function($) {

    // this script needs to be loaded on every page where an ajax POST may happen
    $.ajaxSetup({
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        }
    });
 
 
  // now write your ajax script 
 
});
//////////////////////////////////////////////// Browser session close code //////////////////////////////////////////////

</script>

 	<script type="text/javascript">
     $(document).ready(function() {
    // Configure/customize these variables.
    var showChar = 200;  // How many characters are shown by default
    var ellipsestext = "...";
    var moretext = "Show more >";
    var lesstext = "Show less";
    

    $('.more').each(function() {
        var content = $(this).html();
 
        if(content.length > showChar) {
 
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);
 
            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink pink-text">' + moretext + '</a></span>';
 
            $(this).html(html);
        }
 
    });
 
    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
});
 </script>
</body>


</html>