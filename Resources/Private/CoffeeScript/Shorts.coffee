$ ->
    $('#shortURL').click ->
        if $('.displayShortUrl').length == 0
            urlField = generateElements();
            $(urlField).prependTo($('#shortURL').parent('li')).focus().select();
        else
            $('.displayShortUrl').remove()
        false

# creates dom elements to display the short url
generateElements = ->
    displayShortUrl = document.createElement('input')
    displayShortUrl.setAttribute('class', 'displayShortUrl')
    shortUrl = $('a#shortURL').attr('href');
    displayShortUrl.setAttribute('value', shortUrl)
    displayShortUrl
