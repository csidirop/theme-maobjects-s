var CenterRow = window.CenterRow || {};

(function($) {
    var mobileWidth = window.matchMedia('(max-width: 800px)');

    function closePanels(header) {
        header.find('.site-navigation .sub-nav').removeClass('open').attr('aria-hidden', 'true');
        header.find('.site-navigation [aria-expanded]').removeClass('open').attr('aria-expanded', 'false');
    }

    CenterRow.megaMenu = function(menuSelector, customMenuOptions) {
        var options = $.extend({
            uuidPrefix: 'accessible-megamenu',
            menuClass: 'nav-menu',
            topNavItemClass: 'nav-item',
            panelClass: 'sub-nav',
            panelGroupClass: 'sub-nav-group',
            hoverClass: 'hover',
            focusClass: 'focus',
            openClass: 'open',
            openOnMouseover: window.matchMedia('(hover: hover) and (pointer: fine)').matches
        }, customMenuOptions);
        $(menuSelector || '.site-header .site-navigation').accessibleMegaMenu(options);
    };

    CenterRow.mobileMenu = function() {
        $('.site-header').each(function() {
            var header = $(this);
            var button = header.find('.menu-button');
            var nav = header.find('.site-navigation');
            if (header.data('mobileMenuReady') || !nav.data('plugin_accessibleMegaMenu')) {
                return;
            }
            header.data('mobileMenuReady', true).addClass('menu-enhanced');
            button.prop('hidden', false);

            function closeMenu(restoreFocus) {
                header.removeClass('menu-open');
                button.attr('aria-expanded', 'false');
                closePanels(header);
                if (restoreFocus) {
                    button.trigger('focus');
                }
            }

            button.on('click.maobjects', function() {
                var open = button.attr('aria-expanded') !== 'true';
                closeMenu(false);
                if (open) {
                    header.addClass('menu-open');
                    button.attr('aria-expanded', 'true');
                    nav.find('a').first().trigger('focus');
                }
            });

            // Handle Escape before the menu plugin schedules its own focus move.
            this.addEventListener('keydown', function(event) {
                if (event.key !== 'Escape' || !nav[0].contains(event.target)) {
                    return;
                }
                var panel = nav.find('.sub-nav.open').first();
                if (panel.length) {
                    var parent = panel.siblings('a').first();
                    closePanels(header);
                    parent.trigger('focus');
                } else if (mobileWidth.matches && header.hasClass('menu-open')) {
                    closeMenu(true);
                } else {
                    return;
                }
                event.preventDefault();
                event.stopPropagation();
            }, true);

            $(document).on('click.maobjectsMenu', function(event) {
                if (mobileWidth.matches && !header[0].contains(event.target)) {
                    closeMenu(false);
                }
            });
            mobileWidth.addEventListener('change', function() {
                var focused = nav[0].contains(document.activeElement);
                var buttonFocused = document.activeElement === button[0];
                closeMenu(mobileWidth.matches && focused);
                if (!mobileWidth.matches && buttonFocused) {
                    nav.find('a').first().trigger('focus');
                }
            });
        });
    };

    CenterRow.headerSearch = function() {
        $('.site-header #search-container').each(function() {
            var container = $(this);
            var button = container.find('.search-toggle');
            var panel = container.find('#header-search-panel');
            if (container.data('searchReady')) {
                return;
            }
            container.data('searchReady', true).addClass('search-enhanced');
            panel.prop('hidden', true);
            button.prop('hidden', false);

            function closeSearch(restoreFocus) {
                panel.prop('hidden', true);
                button.attr('aria-expanded', 'false');
                if (restoreFocus) {
                    button.trigger('focus');
                }
            }

            button.on('click.maobjects', function() {
                var open = panel.prop('hidden');
                panel.prop('hidden', !open);
                button.attr('aria-expanded', String(open));
                if (open) {
                    closePanels(container.closest('.site-header'));
                    panel.find('input:not([type="hidden"]), select, textarea').first().trigger('focus');
                }
            });
            container.on('keydown.maobjects', function(event) {
                if (event.key === 'Escape' && !panel.prop('hidden')) {
                    closeSearch(true);
                    event.preventDefault();
                    event.stopPropagation();
                }
            });
            $(document).on('click.maobjectsSearch focusin.maobjectsSearch', function(event) {
                if (!container[0].contains(event.target)) {
                    closeSearch(false);
                }
            });
        });
    };

    $(document).ready(function() {
        CenterRow.megaMenu();
        CenterRow.mobileMenu();
        CenterRow.headerSearch();
    });
})(jQuery);
