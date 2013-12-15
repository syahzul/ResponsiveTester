/**
 * @package		ResponsiveTester
 * @copyright	Copyright (C) 2013 Syahril Zulkefli. All rights reserved.
 * @license		http://opensource.org/licenses/MIT
 */

var setSectionHeights = function() {
	setTimeout( function() {
		var innerHeight = $(window).innerHeight();
		$('#site').css({
			'min-height': innerHeight - 55
		});
	}, 500);
};


$(document).ready(function(){

	// when user press CMD+R or CTRL+F5 or F5, reload the iframe content,
	// not the main app
	jwerty.key('cmd+r/ctrl+f5/f5', function(event) {
		event.preventDefault();
		$('#site').attr('src', $('#site').attr('src'));
	});

	// trigger tooltips
	$('a[data-toggle="tooltip"]').tooltip({
		placement: 'bottom',
		container: 'body'
	});

	// automatically set frame height based on viewport
	setSectionHeights();
	$(window).resize( function() {
		setSectionHeights();
	});

	// save configuration to config.ini
	$('#btn-save-config').click( function() {
		$.ajax({
			url: live_site + '/task.php',
			data: {
				task: 'save',
				base_path: $('#input_base_path').val(),
				base_url: $('#input_base_url').val(),
				excludes: $('#input_excludes').val()
			},
			type: 'get',
			success: function() {
				$('#config-modal').modal('hide');
				$('.close-modal').trigger('click');
			}
		});
		return false;
	});

	
	$('#config-modal').on('shown.bs.modal', function (e) {
		$.ajax({
			url: live_site + '/task.php',
			data: {
				task: 'config'
			},
			dataType: 'json',
			type: 'get',
			success: function(r) {
				$('#input_excludes').val(r.excludes);
				$('#input_base_url').val(r.base_url);
				$('#input_base_path').val(r.base_path);
			}
		});
	});


	// call build script
	$('#btn-compile').click( function() {

		$('#build-container').remove();

		// get the info
		$.ajax({
			url: live_site + '/task.php',
			data: {
				id: cur_theme,
				task: 'build'
			},
			dataType: 'json',
			type: 'get',
			success: function(r) {
				$('body').append('<iframe id="build-container" src="' + r.url + '/build.php" style="display: none;"></iframe>');
				$('#build-container').css({
					'min-height': $(window).innerHeight() - 55
				}).slideDown('fast');

				$('#site').hide();

				$('#btn-compile').hide();
				$('#btn-close-compile').show();
			},
		});

		return false;
	});


	// close compiler iframe
	$('#btn-close-compile').click( function() {
		$('#site').slideDown('500', function() {
			$('#build-container').slideUp('fast');	
		});
		$('#btn-compile').show();
		$('#btn-close-compile').hide();
		return false;
	});


	// show build history
	$('#btn-history').click( function() {
		
		// get the info
		$.ajax({
			url: live_site + '/task.php',
			data: {
				id: cur_theme,
				task: 'detail'
			},
			dataType: 'json',
			type: 'get',
			success: function(r) {

				var html = '';
				html += '<ul class="list-unstyled">';
				r.forEach( function(item) {
					html += '<li><a href="'+item.url+'">' + item.name + '</a></li>';
				});
				html += '</ul>';

				$('#compile-modal .modal-body').html(html).modal('show');
				$('#compile-modal').modal('show');
			}
		});

		return false;
	});


	// workaround for modal backdrop not closing
	$('.close-modal').click( function() {
		$('.modal-backdrop').remove();
	});


	// form view site
	$('#btn-viewsite').click( function() {

		// get the url entered by user
		var url = $('#site-url').val();

		// regular expression to check if the protocol is present
		var regex = /(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/gi;
		
		if ( ! regex.test(url))
		{
			url = 'http://' + url;
			$('#site-url').val(url);
		}

		$('#site').attr('src', url);
		return false;
	});

	// form view site
	$('#btn-install').click( function() {
		$('#site').attr('src', live_site + '/install.php');
		return false;
	});


	// switch theme
	$('.theme-item').click( function() {

		// get the name
		var name = $(this).text();

		// set the iframe src
		$('#site').attr('src', $(this).attr('href'));

		// set the dropdown text to current theme name
		$('#theme-name').text(name);

		// make sure all the item is visible
		$('.theme-item:hidden').each( function() {
			if ($(this).parent('li:hidden')) {
				$(this).parent().show();
			}
		});

		// hide current item from view
		$(this).parent().hide();

		// remove dropdown open class so the menu will be hidden
		$(this).parents('.dropdown').removeClass('open');

		// set the current theme to compile button
		$('#compile').attr('href', '#' + name.toLowerCase());

		// set current theme to config file
		$.ajax({
			url: live_site + '/task.php',
			data: {
				id: name.toLowerCase(),
				task: 'set'
			},
			type: 'get'
		});

		// set current theme var
		cur_theme = name;

		return false;
	});


	// responsive view changer
	$('.device-button').click( function() {

		var id = $(this).attr('id');
		$('.device-button').each( function() {
			$(this).parent().removeClass('active');
		});

		$(this).parent('li').addClass('active');

		switch (id) {
			case 'lg':
				$('#site').animate({
					width: '100%'
				}, 500);
				break;

			case 'md':
				$('#site').animate({
					width: 992
				}, 500);
				break;

			case 'sm':
				$('#site').animate({
					width: 768
				}, 500);
				break;

			case 'xs':
				$('#site').animate({
					width: 480
				}, 500);
				break;

			case 'xxs':
				$('#site').animate({
					width: 320
				}, 500);
				break;
		}

		return false;
	});
});