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
	$('.lq_block_cl').click(function(){
		if ($(this).parents('.lq_block').hasClass('lq_clicked')) {
			$(this).css('background-position', 'inherit');
			$(this).parents('.lq_block').find('.lq_block_content').slideUp('fast');
			$(this).find('i').removeClass('fa-caret-down');
			$(this).find('i').addClass('fa-caret-right');
			$(this).parents('.lq_block').removeClass('lq_clicked');
		}
		else {
			$(this).css('background-position', 'bottom');
			$(this).parents('.lq_block').find('.lq_block_content').slideDown('fast');
			$(this).find('i').removeClass('fa-caret-right');
			$(this).find('i').addClass('fa-caret-down');
			$(this).parents('.lq_block').addClass('lq_clicked');
		}
	});
	$('.lq_block_test').click(function(){
		if($(this).hasClass('lq_clicked')) {
			$('.lq_block_audio .lq_block_content').slideUp('fast');
			$('.lq_block_audio .lq_block_cl').css('background-position', 'inherit');
			$('.lq_block_audio').find('i').removeClass('fa-caret-down');
			$('.lq_block_audio').find('i').addClass('fa-caret-right');
			$('.lq_block_audio').removeClass('lq_clicked');
		}
	});
});