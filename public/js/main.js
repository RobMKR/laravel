$(function(){
	$( "#datepicker" ).datepicker({
		dateFormat: 'dd/mm/yy',
		firstDay: 1
	});

	$("#datepicker").click(function(){
		$(this).blur();
		return false;
	});

	$('#addSlipForm').submit(function(e){
		e.preventDefault();
		var $this = $(this);

		var type = $this.find('input[name="type"]').val();

		var time = $this.find('select[name="hour"]').val() + ':' + $this.find('select[name="min"]').val() + ':00'; 
		var date = $this.find('input[name="date"]').val();
		var phone = $this.find('input[name="phone"]').val();
		var name = $this.find('input[name="name"]').val();
		var surname = $this.find('input[name="surname"]').val();
		var shop_id = $this.find('select[name="shop_id"]').val();
		var count = $this.find('select[name="count"]').val();

		if(phone == '' || date == '' || shop_id == ''){
			$('#error').html('Լրացրեք բոլոր դաշտերը');
			$('#error').show();
			return false;
		}

		if(phone.length !== 8){
			$('#error').html('Մուտքագրեք 8 նիշանոց հեռախոսահամար');
			$('#error').show();
			return false;

		}

		var data = {
			_token: Laravel.csrfToken,
			type : type,
			phone : phone,
			date : date,
			time : time,
			shop_id : shop_id,
			count : count	
		};

		if(type == 'register'){
			if(name == '' || surname == ''){
				$('#error').html('Լրացրեք բոլոր դաշտերը');
				$('#error').show();
				return false;
			}

			data.type = 'register';
			data.name = name;
			data.surname = surname;
		}
		
		$('body').append('<div class="loader"><img src="/img/popup_loader.gif" alt=""></div>');

		$.ajax({
            url : '/addSlip',
            type : 'POST',
            data : data,
            success : function(rsp){
				$('#error, #success').hide();
            	$('.name-surname-block').empty();            	
            	$this.find('input[name="type"]').val('find');

            	if(rsp.status == 421){        			
        			addNameSurname('.name-surname-block');
        			$this.find('input[name="type"]').val('register'); 
            	}

            	if(rsp.status == 404){
            		$('#error').html(rsp.err).show();
            	}

            	if(rsp.status == 200){
            		$('#success').html(rsp.msg).show();
            		resetForm($this);
            	}

            	$('.loader').remove();
            },
            error : function(){
            	$('.loader').remove();

				$('#error, #success').hide();
            	$('.name-surname-block').empty();            	
            	$this.find('input[name="type"]').val('find');
            }
        });
	});

	function addNameSurname(to){

		var inputs = '<div class="text-center w w-50">';
		inputs += '<input type="text" name="name" class="form-control" placeholder="Անուն"/>'
		inputs += '</div>';
		inputs += '<div class="text-center w w-50">';
		inputs += '<input type="text" name="surname" class="form-control" placeholder="Ազգանուն"/>'
		inputs += '</div>';

		$(to).append(inputs);
	}

	function resetForm(form){
		form.find('input[name="date"]').val('');
		form.find('input[name="phone"]').val('');
		form.find('select[name="hour"]').val('10');
		form.find('select[name="min"]').val('00') 
	}
});