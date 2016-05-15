(function( $ ) {

	'use strict';

	 $( window ).load(function() {

			var $zipCodeWrapper    = $("#zipCodeWrapper");
			var $zipCodeSubmitter  = $("#zipCodeSubmitter");
			var $zipCodeInput      = $("#zipCode");
			var $zipCodeDismissBtn = $("#zipCodeDismiss");

      if ( $zipCodeWrapper.length ){

        if ( !Cookies.get("zipCode") && !Cookies.get("zipCodeDismissed") ){

          $zipCodeWrapper.css("display","block");
          $zipCodeSubmitter.click(function(e){
            e.preventDefault();
            Cookies.set("zipCode", $zipCodeInput.val().replace(/ /g,""));
            location.reload();
          });

          $zipCodeDismissBtn.click(function(e){
            e.preventDefault();
            Cookies.set("zipCodeDismissed","true");
            $zipCodeWrapper.css( "display", "none");
          });

        }

      }

      $('.delete_zip_code_cookie').click(function(e){
        e.preventDefault();
        e.stopPropagation();
        Cookies.remove('zipCode');
        location.reload();
      });

	 });

})( jQuery );
