$ ->
  $('.short-url_link').click ->
    $parent = $(this).parents('.short-url')
    $parent.find('.short-url_popup').fadeToggle()
    $parent.find('.short-url_input').select()
    false

  $('body').click ->
    $('.short-url_popup').fadeOut()

  $('.short-url_popup').click (e) ->
    false
