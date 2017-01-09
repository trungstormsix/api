jQuery(document).ready(function($){
	$('.check_result').click(function(){
		$(".correct i").css({'display':'inline-block', 'font-size':'18px', 'font-weight':'bold'});
	});
	$('.categories_listen').click(function(){
		if($(this).hasClass('show_menu')) {
			$(this).find('span').css('border-bottom','1px solid');
			$(this).find('ul').slideUp();
			$(this).find('i').removeClass('fa-chevron-up');
			$(this).find('i').addClass('fa-chevron-down');
			$(this).removeClass('show_menu');
		}
		else {
			$(this).find('span').css('border-bottom','0');
			$(this).find('ul').slideDown();
			$(this).find('i').removeClass('fa-chevron-down');
			$(this).find('i').addClass('fa-chevron-up');
			$(this).addClass('show_menu');
		}
	});
});