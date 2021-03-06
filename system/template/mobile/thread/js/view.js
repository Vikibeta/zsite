$(function()
{
    $('.thread > .card-footer').each(function()
    {
        var $footer = $(this);
        var $children = $footer.children();
        var length = $children.length;
        if(length < 1 
           || (length === 1 && $children.filter('.actions').length === 1 && $children.filter('.actions').children().length < 1))
        {
            $footer.hide();
        }
    });

    $(document).on('click', '.quote', function()
    {
        if($(this).parents('.alert-replies').length)
        {
            var $quote = $(this).parents('.thread-content');
            var date   = $quote.find('.reply-date').html();
            var user   = $quote.find('.reply-author').html().replace('\：', '');
            var quoteTitle = v.quoteTitle.replace('\%\s', user).replace('\%\s', date);

            var quoteContent = '[quote]';
            quoteContent += quoteTitle;
            quoteContent += $quote.find('.reply-content').html();
            quoteContent += '[/quote]';
        }
        else
        {
            var $quote     = $(this).parents('.panel.reply');
            var date       = $quote.find('.panel-heading span.muted')[0].childNodes[1].textContent;
            var user       = $quote.find('.table .speaker .thread-author')[0].childNodes[1].textContent;
            var quoteTitle = v.quoteTitle.replace('\%\s', user).replace('%s', date);
            
            var quoteContent = '[quote]';
            quoteContent += quoteTitle;
            quoteContent += $quote.find('.table .thread-wrapper .thread-content').html();
            quoteContent += '<br/>[/quote]';
        }

        $('#content').focus().val(quoteContent);
    })
});
