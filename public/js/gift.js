$(function(){
	window.max_gifts = 0;

	var size = function(obj) {
	    var size = 0, key;
	    for (key in obj) {
	        if (obj.hasOwnProperty(key)) size++;
	    }
	    return size;
	};

	$('.agreement-cancel .btn').click(function(){
		$.magnificPopup.close();
	});

	$('#takeGiftForm').submit(function(e){
		e.preventDefault();
		resetForm();
		var $this = $(this);

		var phone = $this.find('input[name="phone"]').val();

		if(phone == ''){
			$('#error').html('ՆՇԵՔ ՀԵՌԱԽՈՍԱՀԱՄԱՐԸ');
			$('#error').show();
			return false;
		}

		var data = {
			_token: Laravel.csrfToken,
			phone : phone,
		};

		$('body').append('<div class="loader"><img src="/img/popup_loader.gif" alt=""></div>');

		$.ajax({
            url : '/getClient',
            type : 'POST',
            data : data,
            success : function(rsp){
				$('#error, #success').hide();        	
				drawClientInfo(rsp.client);
				drawClientGifts(rsp.data, rsp.gifts);
				$('#giveGiftForm').find('[type="submit"]').parent().show();
            	$('.loader').remove();
            },
            error : function(rsp){
            	$('.loader').remove();
            	$('#error').html(JSON.parse(rsp.responseText).msg).show();
            	$this.find('input[name="phone"]').val('');
				$('#success').hide();
            }
        });

	});

	$('.submit-form').click(function(e){
		e.preventDefault();
		$.magnificPopup.open({
			items: {
			    src: '#agreement'
		    },
		    type: 'inline'
		});
	});

	$('.agreement-ok').click(function(){
		$.magnificPopup.close();
		$('.submit-form').submit();
	});

	$('#giveGiftForm').submit(function(e){
		e.preventDefault();
		var $this = $(this);
		var _gifts = $this.find(".checkboxX input");
		var gifts = [];
		$.each(_gifts, function(){
			$_this = $(this);
			if(($_this).prop('checked')){
				gifts.push($_this.val());
			}
		});
		console.log(gifts);
		var data = {
			_token : Laravel.csrfToken,
			client : $this.find('input[name="client"]').val(),
			name : $this.find('input[name="name"]').val(),
			surname : $this.find('input[name="surname"]').val(),
			birth_date : $this.find('input[name="birth_date"]').val(),
			passport_id : $this.find('input[name="passport_id"]').val(),
			passport_given_date : $this.find('input[name="passport_given_date"]').val(),
			passport_b64 : $this.find('input[name="passport_b64"]').val(),
			gifts : JSON.stringify(gifts)
		};

		if(data.name == ''){
			alert('ՄՈՒՏՔԱԳՐԵՔ ՄԱՍՆԱԿՑԻ ԱՆՈՒՆԸ');
			return false;
		}

		if(data.surname == ''){
			alert('ՄՈՒՏՔԱԳՐԵՔ ՄԱՍՆԱԿՑԻ ԱԶԳԱՆՈՒՆԸ');
			return false;
		}

		if(data.birth_date == ''){
			alert('ՄՈՒՏՔԱԳՐԵՔ ՄԱՍՆԱԿՑԻ ԾՆՆԴՅԱՆ ԱՄՍԱԹԻՎԸ');
			return false;
		}

		if(data.passport_id == ''){
			alert('ՄՈՒՏՔԱԳՐԵՔ ՄԱՍՆԱԿՑԻ ԱՆՁՆԱԳՐԻ ՍԵՐԻԱՆ');
			return false;
		}

		if(data.passport_id.length !== 9){
			alert('ՍԽԱԼ ԱՆՁՆԱԳՐԻ ՍԵՐԻԱ');
			return false;
		}

		if(data.passport_given_date == ''){
			alert('ՄՈՒՏՔԱԳՐԵՔ ՄԱՍՆԱԿՑԻ ԱՆՁՆԱԳՐԻ ՏՐՄԱՆ ԱՄՍԱԹԻՎԸ');
			return false;
		}

		if(data.passport_b64 == ''){
			alert('ՆԵՐԲԵՌՆԵՔ ՄԱՍՆԱԿՑԻ ԱՆՁՆԱԳՐԻ ՆԿԱՐԸ');
			return false;
		}

		if($('#passport[type="file"]').length){
			if($('#passport')[0].files.length == 0){
				$('#error').html('ՎԵՐԲԵՌՆԵՔ ԱՆՁՆԱԳՐԻ ՆԿԱՐԸ');
				$('#error').show();
				return false;
			}
		}

		$('body').append('<div class="loader"><img src="/img/popup_loader.gif" alt=""></div>');

		$.ajax({
            url : '/saveClientGift',
            type : 'POST',
            data : data,
            success : function(rsp){
				$('#error').hide();
				$('#takeGiftForm').submit();
            },
            error : function(rsp){
            	$resp = JSON.parse(rsp.responseText);
            	$('#succes').hide();
            	if($resp.passport_id !== undefined){
            		alert('ՏՎՅԱԼ ԱՆՁՆԱԳՐՈՎ ՄԱՍՆԱԿԻՑ ԱՐԴԵՆ ԿԱ');
            	}
            	$('.loader').remove();
            }
        });

	});

	$(document).on('change', '.checkboxX input', function(){
		if($('.checkboxX input:checked').length > window.max_gifts){
			alert('Մասնակցի համար հասանելի նվերները արդեն նշված են');
			$(this).prop('checked', false);
		}
	});

	function drawClientInfo(client){
		var container = $('.clientFormBody');

		var client_info = '<hr>';
		client_info += '<input type="hidden" name="client" value="' + client.id + '"/>';
		client_info += '<div class="clearfix">';
			client_info += '<div class="text-center w w-50">';
				client_info += '<input class="form-control" name="name" type="text" value="' + client.name + '">';
			client_info += '</div>';
			client_info += '<div class="text-center w w-50">';
				client_info += '<input class="form-control" name="surname" type="text" value="' + client.surname + '">';
			client_info += '</div>';

			client_info += '<div class="text-center w w-50">';
				client_info += '<input id="datepicker_dob" class="form-control" name="birth_date" type="text" placeholder="Ծննդյան ամսաթիվ" value="' + (client.birth_date ? client.birth_date : "") + '">';
			client_info += '</div>';
			client_info += '<div class="text-center w w-50">';
				client_info += '<input class="form-control" name="passport_id" placeholder="Անձնագրի սերիա" type="text" value="' + (client.passport_id ? client.passport_id : "") + '">';
			client_info += '</div>';
			
		client_info += '</div>';
		client_info += '<div class="passport-screen">';

		if(client.passport_screen){
			client_info += '<img src="' + client.passport_screen + '"/>';
		}else{
			client_info += '<input id="passport" type="file" name="passport" value="" style="display:none;"/>';
			client_info += '<input type="hidden" id="b64_pass" name="passport_b64"/>';
			client_info += '<img id="passportHandler" src="/img/addPassport.png"/>';
		}
		client_info += '</div>';
		client_info += '<hr>';

		container.append(client_info);

		$('#datepicker_dob').datepicker({
			dateFormat: 'dd/mm/yy',
			firstDay: 1,
			changeYear: true,
			yearRange: '1900:2000',
			defaultDate: '01/01/1980'
		});

		$('#datepicker_pass').datepicker({
			dateFormat: 'dd/mm/yy',
			firstDay: 1,
			changeYear: true,
			yearRange: '-100:+0',
			defaultDate: '01/01/1980'
		});

		$('#datepicker_pass, #datepicker_dob').click(function(){
			return false;
		});
	}

	function drawClientGifts(data, gifts){
		var container = $('.clientGiftsBody');
		var class_name = '';
		var gift_info = '';
		var shop_id;
		var check;
		var counter = 1;
		window.max_gifts = size(data.reserved_gifts);

		$.each(gifts, function(key, value){

			checked  = false;
			reserved = false;
			class_name = 'unavailable';

			if(data.taken_gifts[value.id] !== undefined){
				class_name = 'taken';
				checked = true;
				shop_id = data.taken_gifts[value.id]["ShopId"];
			}else if(data.reserved_gifts[value.id] !== undefined){
				class_name = 'reserved';
				reserved = true;
				shop_id = data.reserved_gifts[value.id]["ShopId"];
			}
			gift_info += '<div class="gift-row ' + class_name + '">';
				gift_info += '<div class="key"><span>' + key + '</span></div>'
				gift_info += '<div class="icon"><span><i class="fa ' + value.icon_class + '"></i></span></div>';
				gift_info += '<div class="name"><span>' + value.name + '</span></div>';
				if(checked){
					gift_info += '<div class="checkboxX"><input type="checkbox" name="gifts[]" value="' + value.id + '||' + shop_id + '" disabled/></div>';
				}else{
					if(reserved){
						gift_info += '<div class="checkboxX"><input type="checkbox" name="gifts[]" value="' + value.id + '||' + shop_id +'"/></div>';
					}else{
						gift_info += '<div class="checkboxX"><input type="checkbox" name="gifts[]" value="' + value.id + '||' + shop_id +'"/ disabled></div>';
					}
				}
			gift_info += '</div>';
		});

		container.append(gift_info);
	}

	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#passportHandler').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function resetForm(){
    	$('.clientGiftsBody').empty();
    	$('.clientFormBody').empty();
    }

	$(document).on('change', '#passport', function(){
		readURL(this);

		if (this.files && this.files[0]) {
		    var FR= new FileReader();
		    FR.onload = function(e) {
			    $("#b64_pass").val(e.target.result);
		    };       
		    FR.readAsDataURL( this.files[0] );
	 	}
    });

	$(document).on('click', '#passportHandler', function(){
		$('#passport').click();
	});
});