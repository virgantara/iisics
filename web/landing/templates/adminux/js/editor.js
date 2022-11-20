var KapEditor = function() {
    var t = function() {},
        e = function() {
            $('.moredetail .showhide').off('click').on('click', function() {
                $(this).hasClass('active') ? $(this).hasClass('active') && ($(this).parent().find('.detailhide').slideUp('fast'), 
                $(this).removeClass('active'), 
                $(this).find('.up').hide(), 
                $(this).find('.down').show()) : ($(this).parent().find('.detailhide').slideDown('fast'), 
                $(this).addClass('active'), 
                $(this).find('.up').show(), 
                $(this).find('.down').hide())
            }),
            $('.move-entry').on('click', function(t) {
                var e = $(this), i = e.parents('.entry');
                if (e.hasClass('up-entry')) 
                { 
					var n = i.prev(); i.insertBefore(n);
				} 
				else if (e.hasClass('down-entry'))
				{
					var o = i.next(); i.insertAfter(o);
				}
                ordernumber();
            }),
            $('.add-entry').off('click').on('click', function(t)
			{
				var e = $(this), url = e.attr('data-href'), count = $('#entry_count').val();
				$.ajax({
					type: 'POST',
					url: url,
					dataType: 'html',
					data: $.param({count: count}),
					beforeSend: function() {
						$('.preloader').show();
						$('#btn-save').prop('disabled', true);
					},
					error: function(xhr, ajaxOptions, thrownError) {
						$('.preloader').hide(); 
						alert(xhr.statusText + '\r\n' + xhr.responseText);
					},
					success: function(data) {  
						$('.preloader').hide(); 
						$('#ajax-items').append(data);
						$('#entry_count').val((parseInt(count) + 1))
					},
					complete: function(){
						$('#btn-save').prop('disabled', false);	
					},
				});
				ordernumber();
			}),
            $('.delete-item').on('click', function(t) {
				var e = $(this), m = e.attr('data-block');
				if (m == 'entry')
				{
					swal({
						title: 'Hapus Entri?',
						text: 'Setelah disimpan, entri yang telah dihapus tidak bisa dikembalikan.',
						type: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#DD6B55',
						confirmButtonText: 'Ya, Hapus!',
						cancelButtonText: 'Tidak',
						closeOnConfirm: true,
						animation: false
					},
					function(isConfirm){
						if (isConfirm) {
							'entry' == e.attr('data-block') ? e.parents('.entry').first().remove() : '', ordernumber();
						}
					})
				}
            }),
            $('#upload-photo').off("click").on("click", function(t) {
				var e = $(this);
				$('#form-upload-photo').remove();
				$('body').prepend('<form enctype="multipart/form-data" id="form-upload-photo" style="display: none;"><input type="file" name="userfoto" value="" /></form>');
				$('#form-upload-photo input[name="userfoto"]').trigger('click');
				if (typeof timer != 'undefined') {
					clearInterval(timer);
				}
				timer = setInterval(function() {
					if ($('#form-upload-photo input[name="userfoto"]').val() != '') {
						
						clearInterval(timer);
						
						$.ajax({
							url: $('#upload-photo').attr('data-href'),
							type: 'post',
							data: new FormData($('#form-upload-photo')[0]),
							dataType: 'json',
							cache: false,
							contentType: false,
							processData: false,
							beforeSend: function() {
								$('.preloader').show(); 
								$('#btn-update').prop('disabled', true);
								$('#btn-save').prop('disabled', true);
							},
							success: function(data) {
								
								if (data.error == 0)
								{
									$('#preview-photo').html('<img class="img-fluid" src="'+ data.content +'">');
									$('#input-photo').html('<input type="hidden" name="pasphoto" value="'+data.image_name+'" class="form-control" readonly="readonly">');
									$('#btn-update').prop('disabled', false);
								}
								else
								{
									swal({
										title: '',
										text: data.message,
										type: 'error',
										showCancelButton: false,
										closeOnConfirm: true,
										html: true
									});  
								}
							},
							complete: function(){
								$('.preloader').hide(); 					
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});
					}
				}, 500);
			}),
			$('#upload-slider').off("click").on("click", function(t) {
				var e = $(this);
				$('#form-upload-slider').remove();
				$('body').prepend('<form enctype="multipart/form-data" id="form-upload-slider" style="display: none;"><input type="file" name="userfoto" value="" /></form>');
				$('#form-upload-slider input[name="userfoto"]').trigger('click');
				if (typeof timer != 'undefined') {
					clearInterval(timer);
				}
				timer = setInterval(function() {
					if ($('#form-upload-slider input[name="userfoto"]').val() != '') {
						
						clearInterval(timer);
						
						$.ajax({
							url: $('#upload-slider').attr('data-href'),
							type: 'post',
							data: new FormData($('#form-upload-slider')[0]),
							dataType: 'json',
							cache: false,
							contentType: false,
							processData: false,
							beforeSend: function() {
								$('.preloader').show(); 
								$('#btn-update').prop('disabled', true);
								$('#btn-save').prop('disabled', true);
							},
							success: function(data) {
								
								if (data.error == 0)
								{
									$('#preview-slider').html('<img class="img-fluid" src="'+ data.content +'">');
									$('#input-slider').html('<input type="hidden" name="slider_image" value="'+data.image_name+'" class="form-control" readonly="readonly">');
									$('#btn-update').prop('disabled', false);
								}
								else
								{
									swal({
										title: '',
										text: data.message,
										type: 'error',
										showCancelButton: false,
										closeOnConfirm: true,
										html: true
									});  
								}
							},
							complete: function(){
								$('.preloader').hide(); 					
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});
					}
				}, 500);
			}),
            $('.userfile').off("click").on("click", function(t) {
				var e = $(this);
				var item_arr = e.attr('data-count');
				
				$('#form-upload').remove();
				$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="userimage" value="" /></form>');
				$('#form-upload input[name="userimage"]').trigger('click');
				if (typeof timer != 'undefined') {
					clearInterval(timer);
				}
				
				timer = setInterval(function() {
					if ($('#form-upload input[name="userimage"]').val() != '') {
						
						clearInterval(timer);
						
						$.ajax({
							url: $('.userfile').attr('data-href'),
							type: 'post',
							data: new FormData($('#form-upload')[0]),
							dataType: 'json',
							cache: false,
							contentType: false,
							processData: false,
							beforeSend: function() {
								$('.preloader').show();
								$('#btn-save').prop('disabled', true);	
							},
							success: function(data) {
								$('.preloader').hide();
								if (data.error == 0)
								{
									$('#item-media-placeholder_'+ item_arr +'').hide();
									$('#item-media-content'+ item_arr +'').html('<input type="hidden" class="form-control" name="item['+ item_arr +'][content]"  class="form-control" value="'+ data.image_name +'" readonly="readonly" >');
									$('#item-media-preview_'+ item_arr +'').css('background-image', 'url("' + data.content + '")');
									$('#item-media-preview_'+ item_arr +'').append('<div class="thumbnail"><img class="img-responsive" src="'+ data.content +'"></div>');
								} 
							},
							complete: function(){
								$('#btn-save').prop('disabled', false);	
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(xhr.statusText + '\r\n' + xhr.responseText);
							},
						});
					}
				}, 500);
			}),
			$('.btn-select-image').off("click").on("click", function(t) {
				var e = $(this);
				$('#form-upload-preview').remove();

				$('body').prepend('<form enctype="multipart/form-data" id="form-upload-preview" style="display: none;"><input type="file" name="userimage2" value="" /></form>');

				$('#form-upload-preview input[name="userimage2"]').trigger('click');

				if (typeof timer != 'undefined') {
					clearInterval(timer);
				}
				
				timer = setInterval(function() {
					if ($('#form-upload-preview input[name="userimage2"]').val() != '') {
						
						clearInterval(timer);
						
						$.ajax({
							url: $('.btn-select-image').attr('data-href'),
							type: 'post',
							data: new FormData($('#form-upload-preview')[0]),
							dataType: 'json',
							cache: false,
							contentType: false,
							processData: false,
							beforeSend: function() {
								$('.preloader').show();
								$('.preview-error').html('');
								$('.preview-error').hide();
								$('#btn-save').prop('disabled', true);
							},
							success: function(data) {
								
								if (data.error == 0)
								{
									$('#post-media-placeholder').hide();
									$('#post-preview-container').show();
									$('#post-media-description').show();
									$('#post-media-content').show();
									$('#post-media-content').html('<input type="hidden" name="post_image"  class="form-control" value="'+ data.image_name +'" readonly="readonly" >');
									$('#post-media-preview').html('<div class="thumbnail"><img class="img-responsive" src="'+ data.content +'"></div>');
								}
								else
								{
									$('.preview-error').show();
									$('.preview-error').html('<div class="alert alert-danger">' + data.message + '</div>');
								}
							},
							complete: function(){
								$('.preloader').hide();
								$('#btn-save').prop('disabled', false);					
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(xhr.statusText + '\r\n' + xhr.responseText);
							}
						});
					}
				}, 500);
			}),
			$('#btn-delete-image').on('click', function(t) {
				swal({
						title: 'Hapus Foto ?',
						text: '',
						type: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#DD6B55',
						confirmButtonText: 'Ya, Hapus!',
						cancelButtonText: 'Tidak',
						closeOnConfirm: true,
						animation: false
					},
					function(isConfirm){
						if (isConfirm) {
							$('#post-media-placeholder').show();
							$('#post-preview-container').hide();
							$('#post-media-description').hide();
							$('#post-media-content').hide();
							$('#post-media-content').html('<input type="text" name="post_image"  class="form-control" value="" readonly="readonly" >');
						}
					})
				
			}),
			$("#clear_prev_video").on('click', function(t) {
				$(".thumbnail-video").html('');
				$(".thumbnail-video").hide();
				$(".thumbnail-video-actions").hide();
				$(".input-thumbnail-video").val('');
			}),
			
			$('#set_prev_video').off("click").on("click", function() {
				var url = $('.input-thumbnail-video').val(), 
					ini = $(this), 
					z = ini.attr('data-href');
				$.ajax({
					url: z,
					type: 'post',
					data: $.param({image: url}),
					dataType: 'json',
					beforeSend: function() {
						$('.preloader').show();
						$('#btn-save').prop('disabled', true);
					},
					success: function(data) {
						
						if (data.error == 0)
						{
							$('#post-media-placeholder').hide();
							$('#post-preview-container').show();
							$('#post-media-description').show();
							$('#post-media-content').html('<input type="text" name="post_image"  class="form-control" value="'+ data.image_name +'" readonly="readonly" ><input type="text" class="form-control input-thumbnail-video" name="input-thumbnail-video" readonly="readonly">');
							$('#post-media-preview').html('<div class="thumbnail"><img class="img-responsive" src="'+ data.content +'"></div>');
							$('.thumbnail-video').html('');
							$('.thumbnail-video').hide();
							$(".thumbnail-video-actions").hide();
						}
						else
						{
							$('.preview-error').show();
							$('.preview-error').html('<div class="alert alert-danger">' + data.message + '</div>');
						}
					},
					complete: function(){
						$('.preloader').hide();
						$('#btn-save').prop('disabled', false);					
					},
					error: function(xhr, ajaxOptions, thrownError) { 
						swal({
							title: 'ERROR',
							text:  'Terjadi kesalahan !',
							type:  "error",
							showCancelButton: true,
							html: true,
							closeOnConfirm: true, 
						})
						return false;
					}
				});
			})
			
			
        },
        imagevideo = function(t) {
            "" == $(".input-thumbnail-video").val() && ($(".thumbnail-video").html(''+
            '<div class="form-group">'+
				'<label>Thumbnail Video</label>'+
				'<div class="thumbnail" >'+
					'<img class="img-responsive" src="' + t + '" >'+
				'</div>'+
			'</div>'),
            $(".input-thumbnail-video").val(t)); 
            $(".thumbnail-video-actions").show();
            $(".thumbnail-video").show();
        },
        video = function() {
            $(".createvideo").on("click", function() {
				
				var ini = $(this),
					z = ini.attr('data-count'), t, e, a, 
					i = $('.input-url-'+ z +'').val(),
					r = i.match(/^(?:http(?:s)?:\/\/)?(?:[a-z0-9.]+\.)?vimeo\.com\/([0-9]+)$/),
					n = i.match(/^(?:http(?:s)?:\/\/)?(?:[a-z0-9.]+\.)?(?:youtu\.be|youtube\.com)\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/)([^\?&\"'>]+)/),
					s = i.match(/^.+dailymotion.com\/(video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/),
					l = i.match(/https?\:\/\/(?:www\.)?facebook\.com\/(\d+|[A-Za-z0-9\.]+)\/(\d+|[A-Za-z0-9\.]+)\/(\d+|[A-Za-z0-9\.]+)\/?/);
				if (n && 11 == n[1].length)
				{
					t = '<iframe src="//www.youtube.com/embed/' + n[1] + '" width="100%" height="400" frameborder="0" allowfullscreen></iframe>', 
					a = "http://img.youtube.com/vi/" + n[1] + "/hqdefault.jpg", 
					imagevideo(a), 
					e = t;
				}
				else if (r) 
				{
					t = '<iframe src="//player.vimeo.com/video/' + r[1] + '" width="100%" height="400" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>', 
					e = t;
				}
				else if (s) 
				{
					t = '<iframe src="//www.dailymotion.com/embed/video/' + s[2] + '" width="100%" height="400" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>', 
					e = t;
				}
				else {
					
					if (!l)
					{
						swal({
							title: "Tidak valid!",
							text:  "Tidak dapat mengambil video dari link ini",
							type:  "error",
							showCancelButton: false,
							closeOnConfirm: true,
							timer: 3000
						})
						return false;
					}
					var u = "video-" + (new Date).getTime();
						t = '<div id="' + u + '" class="fb-video" data-href="' + i + '" style="max-height: 360px;"><div class="fb-xfbml-parse-ignore"></div></div><script>(typeof FB != "undefined") && FB.XFBML.parse($("#' + u + '").parent()[0]);</script>', 
						a = "https://graph.facebook.com/" + l[3] + "/picture?type=large", 
						imagevideo(a), 
						e = t;
				}
				showembed($(this), t, e) 
            })
        },
        
        showembed = function(t, e, a) {
            t.parents(".inputget").hide(), 
            t.parents(".entry").find(".embedarea").removeClass("hide"), 
            t.parents(".entry").find(".videoembed").html(e), 
            t.parents(".entry").find(".input-content").val(a),
            t.parents(".entry").find(".moredetail").removeClass("hide")
        },
        tweet = function() {
            $(".createtweet").off("click").on("click", function() {
                var t = $(this),
					z = t.attr('data-count'),
                    e = $('.input-url-'+ z +'').val(),
                    a = $.ajax({
                        cache: false,
                        url: "https://api.twitter.com/1/statuses/oembed.json?url=" + e,
                        method: "GET",
                        dataType: "jsonp"
                    });
                    if ("" == e) {
					swal({
						title: "Invalid URL",
						text: "Please try full link!",
						type: "error",
						showCancelButton: false,
						closeOnConfirm: true,
						timer: 3000
					})
					return false;
					}
                u("tweet", t, a, e)
            })
        },
        facebook = function() {
            $(".createfacebookpost").off("click").on("click", function() {
				var t = $(this), 
					z = t.attr('data-count'), 
					e = $('.input-url-'+ z +'').val();
					if ("" == e) {
						swal({
							title: "Invalid URL",
							text: "Please try full link!",
							type: "error",
							showCancelButton: false,
							closeOnConfirm: true,
							timer: 3000
						})
						return false;
					}
                var a = '<div class="fb-post" data-href="' + e + '" data-width="100%"></div>';
                showembed(t, a, e), FB.XFBML.parse();
            })
        },
        instagram = function() {
            $(".createinstagram").off("click").on("click", function() {
                var t = $(this), 
					z = t.attr('data-count'),
                    e = $('.input-url-'+ z +'').val();
                    if ("" == e) {
						swal({
							title: "Invalid URL",
							text: "Please try full link!",
							type: "error",
							showCancelButton: false,
							closeOnConfirm: true,
							timer: 3000
						})
						return false;
					}
                var a = $.ajax({
                        cache: !1,
                        url: "http://api.instagram.com/publicapi/oembed/?url=" + e,
                        method: "GET",
                        dataType: "jsonp"
                    });
                u("instagram", t, a, e)
            })
        },
        u = function(t, e, a, i) {
            void a.done(function(a) {
                if (a) {
                    var n, o, r = a.html;
                    if (t == "tweet") 
                    {
                        var s = r.replace('<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>', "");
                        o = r, n = s
                    } 
                    else if (t == "instagram") 
                    {
                        var l = Math.floor(1e3 * Math.random() + 1);
                        o = '<iframe class="instagram-media instagram-media-rendered" id="instagram-embed-' + l + '" src="' + i + 'embed/captioned/?v=5" allowtransparency="true" frameborder="0" data-instgrm-payload-id="instagram-media-payload-0" scrolling="no"  style="border: 0; margin: 1px;width: calc(100% - 2px); border-radius: 4px;"></iframe>', n = i
                    }
                    showembed(e, o, n);
                } 
                else
                {	 
					swal({
						title: "Invalid URL",
						text: "Please try full link!",
						type: "error",
						showCancelButton: false,
						closeOnConfirm: true,
						timer: 3000
					})
					return false;
				}
            })
        },
        editor = function() {
            $('.wysiwyg').summernote({
				placeholder: 'Content ... *',
				disableDragAndDrop: true, 
				toolbar: [
					['group1', ['style']],
					['group2', ['bold','italic','underline','strikethrough','superscript', 'subscript','clear']],
					['group3', ['ul', 'ol', 'paragraph','table','picture']],
					['group4', ['link','hr']],
					['group5', ['codeview']]  
				]
			});
        },
        smalleditor = function(){
			$('.smalleditor').summernote({
				placeholder: 'Content ... *',
				disableDragAndDrop: true, 
				toolbar: [
					['group1', ['bold','italic','underline']],
					['group2', ['ul', 'ol', 'paragraph']],
					['group3', ['link']] 
				]
			});
		},
        ordernumber = function() {
            $('#ajax-items .entry').each(function(t) {
                a = Math.ceil(t), 
                b = a + 1,
                $(this).find('.item-order').val(b);
            }),!1;
        },
        autosize = function(){
			$('.autosize').autosize({append: "\n"});
		};
    return {
        
        EditorInit: function() {
           e(),ordernumber(),editor(),smalleditor(), autosize()
        },
        GetVideo: function() {
            video()
        },
        GetFacebook: function(){
			facebook()
		},
        GetInstagram: function(){
			instagram()
		},
		GetTweet: function(){
			tweet()
		}
    }
}();

