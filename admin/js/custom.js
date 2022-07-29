$(function(){
	 var table = $('#pagination').DataTable();

	 $('#location-state').change(function(){
		
		if($(this).val() != '-1'){
			
			$('#pa').html('<option value="-1">Loading...</option>').val(-1);
			
			$.get('pa.php',{pa_state_id:$(this).val()},function(resp){
				$('#pa').html(resp);
			});
		}
		else{
			$('#pa').html('<option value="-1">Choose a protected area</option>').val(-1);
		}
	
	});

	//location settap
	$('#pa1').css('pointer-events','none');
	$('#location-state').change(function() {
		var state_id = $(this).val().trim(),
		    selected_value = $(this).find(":selected").val(),
		    item = $(this).closest('.grid_4').find('#pa1'),
			all_items = $(this).closest('.grid_4').find('#pa1 option');
			item.prop('selectedIndex',0);
			state_id == selected_value ? item.css('pointer-events', 'all') : item.css('pointer-events','none');
			for(var i = 0; i< all_items.length; i++){
				var state_option = $(all_items[i]),
        		state_ides = state_option.data('state_id');
        if(parseInt(state_ides) == parseInt(state_id)){
        	state_option.removeClass('hide');
        }else {
        	state_option.addClass('hide');
        }
			}
	});

	$('.d_submit').click(function(){
		$('.d_submit i').removeClass('hide');
		setTimeout(function(){
			$('.d_submit i').addClass('hide');
	    }, 11000);
	});
		
});