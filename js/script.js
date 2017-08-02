jQuery(document).ready(function($){
	// uddate result.
	$('.loading').hide();
	$('#updatedb').click(function(e) {
		$today = new Date();
		y = $today.getFullYear();
		
		$.ajax({
			url : 'controllers.php',
			method: 'POST',
			data: {action:'update'},
			beforeSend: function() {
				$('.update_message').hide();
				$('.update .loading').show();
			},
			complete: function() {
				$('.update .loading').hide();
			},
			success: function(resp) {
				$('.update_message').show();
				if (resp === 'update success') {
					if ($('.update_message').hasClass('fail'))
						$('.update_message').removeClass('fail');
					$('.update_message').html(resp+'. The page will reload in <i style="color: red;">5</i> seconds');
					var s = 6;
					var refresh = setInterval(function() {
						s--;
						if (s < 0)
							location.reload(true);
						else
							$(".update_message").html(resp+'. The page will reload in <i style="color: red;">'+s+'</i> seconds');
					}, 1000);
				} else if(resp === 'uptodate') {
					if ($('.update_message').hasClass('fail'))
						$('.update_message').removeClass('fail');
					$('.update_message').html('The data is up to date.');
				} else {
					if ($('.update_message').hasClass('success'))
						$('.update_message').removeClass('success');
					$('.update_message').addClass('fail').html(resp);
				}
			}
		});
	});
	
	// Get results by the date.
	$('.datepicker').datepicker().on('changeDate', function(){
		$date = $(this).val();
		loadding = $(this).next();
		$.ajax({
			method: 'POST',
			url: 'controllers.php',
			data: {date: $date, action : "getResults"},
			beforeSend: function() {
				loadding.show();
			},
			complete: function() {
				loadding.hide();
			},
			success: function(resp) {
				$('#date_results').html(resp);
			}
		});
	});
	
	
	
	// Gest number today with db from 2017 to now.
	$("#get_best_number").click(function() {
		var obj = $('#best_number_17'), action = 'getBestNumber', loadding = $(this).next();
		getNumber(action, obj, loadding);
	});
	
	//Get best number today with db from 2003 to now.
	$('#get_best_number_bf_2017').click(function() {
		var obj = $('#best_number_bf_17'), action = 'getBestNumberBefore2017',loadding = $(this).next();
		getNumber(action, obj, loadding);
	});
	
	// Get best number today by the special number in yesterday.
	$('#get_best_number_by_sp_ys').click(function() {
		var obj = $('#best_number_by_sp_ys'), action = 'getBestNumberBySpecial', loadding = $(this).next();
		getNumber(action, obj, loadding);
	});
	
	// Get the best special.
	$('#get_best_special').click(function() {
		var obj = $('#best_special'), action = 'getBestSpecial', loadding = $(this).next();
		getNumber(action, obj, loadding);
	});
	
	// Get the best special by the first and last letter.
	$('#get_best_special_fl').click(function() {
		var obj = $('#best_special_fl'), action = 'getBestSpecial_fl', loadding = $(this).next();
		getNumber(action, obj, loadding);
	});
	
	function getNumber($action, $obj, loadding) {
		$obj.html('');
		$today = new Date();
		d = $today.getDate() < 10 ? '0'+$today.getDate() : $today.getDate(),
		m = ($today.getMonth() + 1) < 10 ? '0'+($today.getMonth() + 1) : $today.getMonth + 1,
		y = $today.getFullYear();
		
		$date = d+'-'+m+'-'+y;
		
		$.ajax({
			url: 'controllers.php',
			method: 'POST',
			data: {date: $date, action: $action},
			beforeSend: function() {
				loadding.show();
			},
			complete: function() {
				loadding.hide();
			},
			success: function(resp) {
				data = $.parseJSON(resp);
				$numbers = '';
				if (data && data.length) {
					for (i = 0; i < data.length; i++) {
						$numbers == '' ? $numbers += data[i] : $numbers += ' , ' + data[i];
					}
				}
				
				if ($numbers == '')
					$numbers += 'No numbers!';
				
				$obj.html('<span style="color: red">'+$numbers+'</span>');
			}
		});
	}
	
	$('#get_try').click(function() {
		location.href = './spin';
	});
});