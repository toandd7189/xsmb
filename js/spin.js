function spin() {
	// Status of title.
	var titleObj = jQuery('g-title');
	
	//Hide btn to avoid spam click.
	jQuery('#btnq').hide();
	
	var i, g, started, duration, titleArr = ['Đặc Biệt','Nhất','Nhì','Ba','Tư','Năm','Sáu','Bảy','Đặc Biệt'];
	g 	= jQuery('.g-title').data('g');
	i  	= g.substr(-1,1);
	_n 	= jQuery('.g-title').data('n');
	
	// Reload page if all title were finished.
	if (i > 8)
		location.reload(true);
	
	// Time duration for spinning.
	duration = 5000;
	
	// Time for start spin.
	started = new Date().getTime();
	
	// Clear old result if already exists
	spins 	= jQuery('.square .spin');
	spins.html('');
	
	// Numbers of spinning over the title.
	switch (i) {
		case '8':
		case '1':
			var v = 1;
			break;
		case '2':
			var v = 2;
			break;
		case '3':
		case '5':
			var v = 6;
			break;
		case '4':
		case '7':
			var v = 4;
			break;
		case '6':
			var v = 3;
			break;
	}
	
	// Status of title.
	if (g.substr(-1,1) != 0 && i <= 8)
		if (v > 1)
			$('h1.g-title').html('Đang quay Giải '+titleArr[i]+' lần '+_n);
		else 
			$('h1.g-title').html('Đang quay Giải '+titleArr[i]);
	
	//Spin for find result.
	animationTimer = setInterval(function() {
		if (new Date().getTime() - started > duration) {
			clearInterval();
			var spin_rs = spins.text();
		} else {
			spins.each(function(value, sp) {
			if (i <= 3 || i == 8) {
				$(sp).text(
					Math.floor(Math.random() * 10)
				);
			} else if (3 < i && i <= 5) {
				if (value > 0)
					$(sp).text(
						Math.floor(Math.random() * 10)
					);
			} else if (i ==  6){
				if (value >= 2 )
					$(sp).text(
						Math.floor(Math.random() * 10)
					);
			} else {
				if (value >= 3)
					$(sp).text(
						Math.floor(Math.random() * 10)
					);
			}
			});
		}
	},80);

	// Update result.
	setTimeout(function() {
		var result  = spins.text();
		var lo 		= result.substr(-2,2);
		
		if (i == 8){
			jQuery('td#g0-'+_n).html('<b style="color: red">'+result+'</b>');
			i++;
		} else {
			jQuery('td#g'+i+'-'+_n).html(result);
			if (_n < v) {
				_n++;
			} else {
				_n = 1;
				i++;
			}
			
		}
		
		// Title.
		if (i > 8) {
			var title = 'Kết thúc quay số';
		} else { 
			if (i < 8)
				var title = 'Chuẩn bị quay Giải '+titleArr[i];
			else 
				var title = 'Chuẩn bị quay Giải Đặc Biệt';
		}
		var content = '<h1 class="g-title" data-g="g'+i+'" data-n="'+_n+'">'+title;
		(_n > 1 && i != 0) ? content += ' lần '+_n+'</h1>' : content += '</h1>';
		
		// Update title and round for the next spin.
		jQuery('h1.g-title').remove();
		jQuery('#t-wrap').prepend(content);
		
		// Update the lo by the first number.
		var dau = jQuery(jQuery('tr#d'+lo.substr(0,1)).children()[1]).text();
		dau == '' ? dau += lo : dau += ' , '+lo;
		jQuery(jQuery('tr#d'+lo.substr(0,1)).children()[1]).html(dau);
		
		// Show the spin button again;
		jQuery('#btnq').show();
		
		// Show the save button after spin all title.
		if (jQuery('#g0-1').text() != '')
			jQuery('#saveBtn').removeClass('hide');
	},5005);
}
