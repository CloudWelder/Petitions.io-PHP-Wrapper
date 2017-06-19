
<hr>

<!-- Footer -->
<footer class="footer navbar-fixed-bottom">
	<div class="row">
		<div class="col-lg-12">
			<p>Copyright &copy; Your Website 2017</p>
		</div>
	</div>
</footer>

</div>
<!-- /.container -->

<!-- jQuery -->
<script src="js/jquery.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
	//validation
	  var v = jQuery("#create_petition").validate({
    rules: {
  	  title: {
        required: true,
        minlength: 2,
        maxlength: 16
      },
      petition_target: {
        required: true,
        minlength: 2,
        maxlength: 100,
      } 
    },
    errorElement: "span",
    errorClass: "has-error",
  });
	  
	var current = 1,current_step,next_step,steps;
	steps = $("fieldset").length;
	$(".next").click(function(){
		if (v.form()) {
			current_step = $(this).parent();
			next_step = $(this).parent().next();
			next_step.show();
			current_step.hide();
			setProgressBar(++current);
	      }

	});
	$(".previous").click(function(){
		current_step = $(this).parent();
		next_step = $(this).parent().prev();
		next_step.show();
		current_step.hide();
		setProgressBar(--current);
	});
	setProgressBar(current);
	// Change progress bar action
	function setProgressBar(curStep){
		var percent = parseFloat(100 / steps) * curStep;
		percent = percent.toFixed();
		$(".progress-bar")
			.css("width",percent+"%")
			.html(percent+"%");		
	}


    $( "#petition_target" ).autocomplete({
        source:  "search_target.php",
        minLength: 2,
        focus: function( event, ui ) {
		    if (ui.item==null)
	        {
		        $("#petition_target").val('');
		        $("#petition_target").focus();
		        $("#petition_target_id").val('');
	        }
            $( "#petition_target" ).val( ui.item.label );
            $("#petition_target_id").val(ui.item.value);
            return false;
        },
        select: function( event, ui ) {
		    if (ui.item==null)
	        {
		        $("#petition_target").val('');
		        $("#petition_target").focus();
		        $("#petition_target_id").val('');
	        }            
            $( "#petition_target" ).val( ui.item.label );
            $("#petition_target_id").val(ui.item.value);
            return false;
    	},
    	change: function(event,ui)
    	{
		    if (ui.item==null)
		        {
		        $("#petition_target").val('');
		        $("#petition_target").focus();
		        $("#petition_target_id").val('');
		        }
		    }
      } );
	
});
</script>
</body>

</html>