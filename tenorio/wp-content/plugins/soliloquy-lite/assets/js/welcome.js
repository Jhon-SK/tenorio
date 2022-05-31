(function ($) {
	$(function () {
		$('.lionsher-partners').on(
			'click',
			'.lionsher-partners-install',
			function (e) {
				e.preventDefault();
				var $this = $(this);
				var url = $this.data('url');
				var basename = $this.data('basename');
				var message = $(this)
					.parent()
					.parent()
					.find('.lionsher-partner-status');
				var install_opts = {
					url: envira_gallery_welcome.ajax,
					type: 'post',
					async: true,
					cache: false,
					dataType: 'json',
					data: {
						action: 'envira_install_partner',
						nonce: envira_gallery_welcome.install_nonce,
						basename: basename,
						download_url: url,
					},
					success: function (response) {
						$this.text(envira_gallery_welcome.activate)
							.removeClass('lionsher-partners-install')
							.addClass('lionsher-partners-activate');

						$(message).text(envira_gallery_welcome.inactive);
						// Trick here to wrap a span around he last word of the status
						var heading = $(message),
							word_array,
							last_word,
							first_part;

						word_array = heading.html().split(/\s+/); // split on spaces
						last_word = word_array.pop(); // pop the last word
						first_part = word_array.join(' '); // rejoin the first words together

						heading.html(
							[
								first_part,
								' <span>',
								last_word,
								'</span>',
							].join(''),
						);
						// Proc
					},
					error: function (xhr, textStatus, e) {
						console.log(e);
					},
				};
				$.ajax(install_opts);
			},
		);
		$('.lionsher-partners').on(
			'click',
			'.lionsher-partners-activate',
			function (e) {
				e.preventDefault();
				var $this = $(this);
				var url = $this.data('url');
				var basename = $this.data('basename');
				var message = $(this)
					.parent()
					.parent()
					.find('.lionsher-partner-status');
				var activate_opts = {
					url: envira_gallery_welcome.ajax,
					type: 'post',
					async: true,
					cache: false,
					dataType: 'json',
					data: {
						action: 'envira_activate_partner',
						nonce: envira_gallery_welcome.activate_nonce,
						basename: basename,
						download_url: url,
					},
					success: function (response) {
						$this.text(envira_gallery_welcome.deactivate)
							.removeClass('lionsher-partners-activate')
							.addClass('lionsher-partners-deactivate');

						$(message).text(envira_gallery_welcome.active);
						// Trick here to wrap a span around he last word of the status
						var heading = $(message),
							word_array,
							last_word,
							first_part;

						word_array = heading.html().split(/\s+/); // split on spaces
						last_word = word_array.pop(); // pop the last word
						first_part = word_array.join(' '); // rejoin the first words together

						heading.html(
							[
								first_part,
								' <span>',
								last_word,
								'</span>',
							].join(''),
						);
						location.reload(true);
					},
					error: function (xhr, textStatus, e) {
						console.log(e);
					},
				};
				$.ajax(activate_opts);
			},
		);
		$('.lionsher-partners').on(
			'click',
			'.lionsher-partners-deactivate',
			function (e) {
				e.preventDefault();
				var $this = $(this);
				var url = $this.data('url');
				var basename = $this.data('basename');
				var message = $(this)
					.parent()
					.parent()
					.find('.lionsher-partner-status');
				var deactivate_opts = {
					url: envira_gallery_welcome.ajax,
					type: 'post',
					async: true,
					cache: false,
					dataType: 'json',
					data: {
						action: 'envira_deactivate_partner',
						nonce: envira_gallery_welcome.deactivate_nonce,
						basename: basename,
						download_url: url,
					},
					success: function (response) {
						$this.text(envira_gallery_welcome.activate)
							.removeClass('lionsher-partners-deactivate')
							.addClass('lionsher-partners-activate');

						$(message).text(envira_gallery_welcome.inactive);
						// Trick here to wrap a span around he last word of the status
						var heading = $(message),
							word_array,
							last_word,
							first_part;

						word_array = heading.html().split(/\s+/); // split on spaces
						last_word = word_array.pop(); // pop the last word
						first_part = word_array.join(' '); // rejoin the first words together

						heading.html(
							[
								first_part,
								' <span>',
								last_word,
								'</span>',
							].join(''),
						);
						location.reload(true);
					},
					error: function (xhr, textStatus, e) {
						console.log(e);
					},
				};
				$.ajax(deactivate_opts);
			},
		);
	});
})(jQuery);
