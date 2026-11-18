(function($) {
    $(document).ready(function() {
        $('[data-browse-results]').each(function() {
            var results = $(this);
            var list = results.children('.resource-list');
            var toggle = results.children('.browse-controls').find('.layout-toggle');
            if (results.data('browseReady') || !list.length || !toggle.length) {
                return;
            }
            results.data('browseReady', true);
            toggle.prop('hidden', false);

            function updateUrl(href, layout) {
                var url = new URL(href, window.location.href);
                url.searchParams.set('browse_layout', layout);
                return url.href;
            }

            toggle.on('click.maobjectsBrowse', 'button[name="browse_layout"]', function(event) {
                var layout = $(this).val();
                if (layout !== 'grid' && layout !== 'list') {
                    return;
                }
                event.preventDefault();
                list.removeClass('grid list').addClass(layout);
                toggle.find('button[name="browse_layout"]').each(function() {
                    $(this).attr('aria-pressed', String($(this).val() === layout));
                });
                // Previews switch independently without changing the page URL.
                if (results.attr('data-layout-persist') === 'false') {
                    return;
                }
                window.history.replaceState(null, '', updateUrl(window.location.href, layout));
                results.find('.pagination a').each(function() {
                    $(this).attr('href', updateUrl($(this).attr('href'), layout));
                });
                results.find('.pagination form, form.sorting').each(function() {
                    var form = $(this);
                    form.find('input[type="hidden"][name="browse_layout"]').remove();
                    $('<input>', {type: 'hidden', name: 'browse_layout', value: layout}).appendTo(form);
                });
            });
        });
    });
})(jQuery);
