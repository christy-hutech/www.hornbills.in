$(function(){
	
	if(document.getElementById('map')){
		/* Google Maps */
    var map = new google.maps.Map( document.getElementById('map'), {
        zoom: 4,
        mapTypeControl: true,
        scaleControl: true,
        mapTypeId: 'roadmap'
    });
    
    var geocoder = new google.maps.Geocoder();
		
	var marker_array = [];
    
    function mapZoom(location,zoom){
			if(!zoom) zoom = 7;
      geocoder.geocode( { 'address': location}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          map.setCenter(results[0].geometry.location);
          if(location != 'India'){
            map.setZoom(zoom);
          }
        } 
      });
    }
    
    function placeMarker(position){
			
			for (var i = 0; i < marker_array.length; i++) {
				marker_array[i].setMap(null);
			}
			
			marker_array = [];
			
      var marker = new google.maps.Marker({
        position: position,
        map: map
      });
      $('#latlong').val(position.lat()+','+position.lng());
      map.panTo(position);
			marker_array.push(marker);
			
			// Reverse geo-coding
			/*geocoder.geocode({'latLng': new google.maps.LatLng(parseFloat(position.lat()), parseFloat(position.lng()))}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if (results[1]) {
						
						$('#state, #district').val('');
						
						for(var i= 0; i< results[1].address_components.length; i++){
							if(results[1].address_components[i].types[0] == "administrative_area_level_2"){
								$('#district').val(results[1].address_components[i].long_name);
							}
							if(results[1].address_components[i].types[0] == "administrative_area_level_1"){
								$('#state').val(results[1].address_components[i].long_name);
							}
						}
						
						if($('#district').val() == ''){
							$('#district').val('Unknown District');	
						}
						if($('#state').val() == ''){
							$('#state').val('Unknown State');	
						}
						
					} else {
						alert('No results found');
					}
				} else {
					alert('Geocoder failed due to: ' + status);
				}
			});*/

    }
  
    google.maps.event.addListener(map,'click',function(e){
      placeMarker(e.latLng);
    });
	}
	
	/* General */
	
	$(".fancybox").fancybox({
		afterShow:function(){
			google.maps.event.trigger(map,'resize');
			mapZoom('India');
		}
	});
	
	function setLocationType(){
		var loc_type = $('#location-type').val();
		
		$('#town, #latlong').val('');
		$('.location-text').html('Click on the map to pin the location.').removeClass('selected');
		$('#state, #location-state').val(-1);
		$('#location-pa').html('<option value="-1">Choose a state first</option>').val(-1);
		
		if(document.getElementById('map')){
			for (var i = 0; i < marker_array.length; i++) {
				marker_array[i].setMap(null);
			}
			marker_array = [];
		}
		
		$('.location-type-option').removeClass('active');
		$('#location-type-'+loc_type).addClass('active');
		
	}
	
	//setLocationType();
	$('#location-type').change(function(){
		//setLocationType();
	});
	
	
	$('#location-state').change(function(){
		if($(this).val() != '-1'){
			
			$('#location-pa').html('<option value="-1">Loading...</option>').val(-1);
			
			$.get('pa.php',{pa_state_id:$(this).val()},function(resp){
				$('#location-pa').html(resp);
				console.log(resp);
			});
		}
		else{
			$('#location-pa').html('<option value="-1">Choose a state first</option>').val(-1);
		}
	
	});
	
	$('#state').change(function(){
		/*if($(this).val() == -1){
			$('#district').html('<option value="-1">Choose a state first</option>');
		}
		else{
			$.post('district.php',{state_id:$(this).val()},function(data){
				var district_html = '<option value="-1">Choose one</option>'+data;
				$('#district').html(district_html);
			});
		}*/
		$('#town').val('');
		mapZoom($('#state option:selected').attr('data-name'));
	});
	
	/*$('#district').change(function(){
		mapZoom($('#district option:selected').attr('data-name'));
	});*/
	
	$('#town').keyup(function(){
		if($(this).val().length > 3) {
			clearTimeout(window.townTimer);
			var state = $('#state option:selected').attr('data-name');
			var zoomTo = $(this).val();
			if(state){ zoomTo += ', '+state}
			window.townTimer = setTimeout(function(){mapZoom(zoomTo,12)},1000);
		}
	});
	
	$('.close-modal').click(function(){
		if($('#state').val() == -1 || $('#latlong').val() == ''){
			alert('Please choose a state and point the location of the sighting on the map so we can get the latitude-longitude.');
			$('.location-text').html('Click on the map to pin the location.').removeClass('selected');
			return;
		}
		else{
			var location_string = '';
			if($('#town').val() != ''){
				location_string += $('#town').val()+', ';
			}
			//location_string += $('#district option:selected').attr('data-name')+', '+$('#state option:selected').attr('data-name');
			location_string += $('#state option:selected').attr('data-name');

			$('.location-text').html(location_string).addClass('selected');
			$.fancybox.close();
		}
	});
	
	$('.datepicker').datepicker({"dateFormat": 'd MM, yy'});
	
	
	$('#couponForm').on('change', '#upload-image', function(event) {
		var x = document.getElementById('upload-image'); // get the file input element in your form
		var f = x.files.item(0); // get only the first file from the list of files
		var filesize = f.size;
		if(filesize > 307200){
			alert('The image you are trying to upload is larger than 300kb. Please reduce the file size and upload again.');
			$('#upload-image').val('');
			return;
		}
		var file, _fn, _i, _len, _ref;
		_ref = event.target.files;
		_fn = function(file) {
			var reader = new FileReader();
			reader.onload = (function(f) {
				return function() {
					var i = new Image();
					i.onload = (function(e) {
						var height, width;
						width = e.target.width;
						height = e.target.height;
						if(width > 1024){
							alert('The image you are trying to upload is wider than 1024 pixels. Please reduce the resolution and upload again.');
							$('#upload-image').val('');
							return;
						}
					});
					return i.src = reader.result;
				};
			})(file);
			return reader.readAsDataURL(file);
		};
		for (_i = 0, _len = _ref.length; _i < _len; _i++) {
			file = _ref[_i];
			_fn(file);
		}
	});
	
	
	
	$('.submit').click(function(){
		//var name = $('#name').val();
		var email = $('#email').val();
		var mobile = $('#mobile_no').val();
		var species = $('#species').val();
		var date = $('#dateofincident').val();
		var loc_type = $('#location-type').val();
		var loc_state = $('#location-state').val();
		var loc_pa = $('#location-pa').val();
		var state = $('#state').val();
		//var district = $('#district').val();
		var latlong = $('#latlong').val();
		var image = $('#upload-image').val();
		var tnc = $('#tnc').attr('checked');
        var captcha = $('#captcha-form').val();
        var dataString = 'captcha=' + captcha;
  
		
		var name_regex = /^[a-zA-Z_&amp;()\s- ]*$/;
		var email_regex = /\S+@\S+\.\S+/;
		var number_regex = /[0-9]{1,15}/;
		
		var error = '';
		//if(name == '' || !name_regex.test(name)){ error += '* Enter a valid name.\n'}		
		if(email == '' || !email_regex.test(email)){ error += '* Enter a valid email address.\n' }
		if(mobile != '' && !number_regex.test(mobile)){ error += '* Enter a valid phone number. Use numbers only.\n' }
		if(species == -1){ error += '* Choose the species of the hornbill sighted.\n' }
        if(captcha == ''){ error += '* Enter the text found below.\n' }

		//if(date == ''){ error += '* Choose the date of the sighting.\n' }
		
		if(loc_type == 'latlng'){
			if(state == -1|| latlong == ''){ error += '* Pinpoint the location of the sighting by clicking on the exact point on the map.\n' }
		}
		else{
			if(loc_state == -1){ error += '* Choose a State.\n' }
			if(loc_pa == -1){ error += '* Choose a protected area.\n' }
		}
		
		if($('#typeofincident').val() != 1 && !image){
			if(!confirm('Are you sure you want to submit this sighting without an image?')){
				return false;
			}	
		}
		if(!tnc){ error += '* Agree to the terms and conditions by clicking on the checkbox.\n' }
		
		if(error != ''){
			alert('Please address the following issues in your submission:\n\n'+error);
			return false;
		}
       
        
	});
    
   
	$('.tabs-nav a').click(function(){
			var target = $(this).attr('href');
			$('.tabs-content.active').hide(1,function(){
					$(this).removeClass('active');
					$(target).show().addClass('active');
			});
			$('.tabs-nav a.active').removeClass('active');
			$(this).addClass('active');
			return false;
	});

  //search species
	$('.search-buttonnew').keyup(function() {
			var value = $(this).val().trim().toLowerCase();
			var search_items = $(this).closest('.content').find('.gallery.display-gallery .grid_4');
			var allSearch_items = $(this).closest('.content').find('.gallery.gallery-copy .grid_4');
			for(var i = 0; i< allSearch_items.length; i++){
        var each_item =  $(allSearch_items[i]),
        	species_name = $(each_item).find('.gallery-caption').text().trim(),
        	sighting_date  = $(each_item).find('.sighting-date').val().trim(),
        	state_name = $(each_item).find('.state-name').val().trim(),
        	c_name = $(each_item).find('.c-name').val().trim();
        	(sighting_date.toString().toLowerCase().indexOf(value) > -1 || species_name.toString().toLowerCase().indexOf(value) > -1 || state_name.toString().toLowerCase().indexOf(value) > -1 || c_name.toString().toLowerCase().indexOf(value) > -1) ? $(each_item).removeClass('hidden') : $(each_item).addClass('hidden');
        	if(value == '') {
        		$(allSearch_items).addClass('hidden');
        		$(search_items).show();
        		$('.display-pagination').show();
        	}else {
        		$(search_items).hide();
        		$('.display-pagination').hide();
        	}
      }
	})

	//location settap
	$('#location-state').change(function() {
		var state_id = $(this).val().trim(),
			item = $(this).closest('#location-type-pa').find('#location-pa1');
			all_items = $(this).closest('#location-type-pa').find('#location-pa1 option');
			item.prop('selectedIndex',1);
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
    
  $("#slides").slidesjs({ width:940, height:340, play:{active:true, interval:6000, auto:true}, navigation: {active:false}}).fadeIn();
 
	$('.tooltip').tipsy({gravity:'s'});
	
});