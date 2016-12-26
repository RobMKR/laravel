$(function(){
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

	$('#giveGiftForm').submit(function(e){
		e.preventDefault();
		var $this = $(this);

		if($this.find('[name="shop"]').length == 0 || $this.find('[name="gift"]').length == 0){
			$('#error').html('ՄԱՍՆԱԿԻՑԸ ՉԻ ԱՐԺԱՆԱՑԵԼ ՀԱՄԱՊԱՏՍԽԱՆ ՆՎԵՐԻ');
			$('#error').show();
			return false;
		}

		if($('#passport[type="file"]').length){
			if($('#passport')[0].files.length == 0){
				$('#error').html('ՎԵՐԲԵՌՆԵՔ ԱՆՁՆԱԳՐԻ ՆԿԱՐԸ');
				$('#error').show();
				return false;
			}
		}
		/****
		*
		*  Using Form Data, need to do AJAX File Upload to Server....	
		*  
		*
		*/

	});

	function drawClientInfo(client){
		var container = $('.clientFormBody');

		var client_info = '<hr>';
		client_info += '<input type="hidden" name="client" value="' + client.id + '"/>';
		client_info += '<div class="clearfix">';
			client_info += '<div class="text-center w w-50">';
				client_info += '<input class="form-control" name="name" type="text" value="' + client.name + '" disabled>';
			client_info += '</div>';
			client_info += '<div class="text-center w w-50">';
				client_info += '<input class="form-control" name="surname" type="text" value="' + client.surname + '" disabled>';
			client_info += '</div>';
		client_info += '</div>';
		client_info += '<div class="passport-screen">';

		if(client.passport_screen){
			client_info += '<input type="hidden" name="passport" value="' + client.passport_screen + '"/>';
			client_info += '<img src="' + client.passport_screen + '"/>';
		}else{
			client_info += '<input id="passport" type="file" name="passport" value="" style="display:none;"/>';
			client_info += '<img id="passportHandler" src="/img/addPassport.png"/>';
		}
		client_info += '</div>';
		client_info += '<hr>';

		container.append(client_info);
	}

	function drawClientGifts(data, gifts){
		var container = $('.clientGiftsBody');
		var class_name = '';
		var next = false;
		var gift_info = '';
		var shop_info;

		$.each(gifts, function(key, value){
			if(data.taken[value.id] !== undefined){
				class_name = 'taken';
			}else{
				if(data.available && !next){
					next = true;
					gift_info += '<input type="hidden" name="shop" value="' + data.available.ShopId + '" >';
					gift_info += '<input type="hidden" name="gift" value="' + value.id + '" >';
					class_name = 'next';
				}else{
					class_name = 'unavailable';
				}
			}
			gift_info += '<div class="gift-row ' + class_name + '">';
				gift_info += '<div class="key"><span>' + key + '</span></div>'
				gift_info += '<div class="icon"><span><i class="fa ' + value.icon_class + '"></i></span></div>';
				gift_info += '<div class="name"><span>' + value.name + '</span></div>';
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
    });

	$(document).on('click', '#passportHandler', function(){
		$('#passport').click();
	});
});