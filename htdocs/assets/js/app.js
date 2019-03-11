(function ($, window) {
    var app = {
        init: function (ns) {
            var $body = $('body');

            app.load.maillist();

            $('.trigger-check-all').on('click', function () { app.mail.checkAll(true); });
            $('.trigger-uncheck-all').on('click', function () { app.mail.checkAll(false); });
            $('.trigger-mailcheck').on('click', app.mail.check);
            $('.trigger-remove').on('click', app.mail.remove);
            $('.trigger-purge').on('click', app.mail.purge);

            $('#maillist-container').on('click', app.mail.unOpen);

            $body.on('click', '.trigger-mail-open', app.mail.open);
            $body.on('click', '.trigger-mail-selection', app.mail.select);

            return ns;
        },

        preventDefault: function (e) {
            e = (e || event);
            e.cancelBubble = true;
            e.stopPropagation();
            // e.preventDefault();

            return false;
        },

        toggle: {
            mailSelected: function ($element) {
                $('#maillist-container').find('.trigger-mail-open').css({
                    'background-color': 'transparent',
                    'border-right': 0
                });

                if ($element) {
                    $element.css({
                        'background-color': '#f1f1f1',
                        'border-right': '3px #444444 solid'
                    });
                }
            },

            maillistLoader: function (show) {
                var $insertEl = $('#maillist-container'),
                    $loaderEl = $('.maillist-loader'),
                    loaderElHtml = '<div class="maillist-loader text-center"><span class="fa fa-spinner fa-spin"></span></div>';

                if (show) {
                    $insertEl.html('').html(loaderElHtml);
                } else {
                    $loaderEl.remove();
                }
            },

            mailButtons: function (show) {
                if (show) {
                    $('.group-enable-disable > button, .button-enable-disable').removeAttr('disabled');
                } else {
                    $('.group-enable-disable > button, .button-enable-disable').attr('disabled', 'disabled');
                }
            },

            mailRemoveButton: function () {
                var $el = $('.trigger-remove'),
                    $checkedEls = $('.trigger-mail-selection:checked');

                if ($checkedEls.length > 0) {
                    $el.removeAttr('disabled');
                } else {
                    $el.attr('disabled', 'disabled');
                }
            },

            mailIframe: function (show) {
                var $iframeContainer = $('.mail-iframe'),
                    $noMailMessage = $('.no-mail-message');

                if (show) {
                    $noMailMessage.hide();
                    $iframeContainer.show();
                } else {
                    $noMailMessage.show();
                    $iframeContainer.hide();
                }
            }
        },

        load: {
            maillist: function () {
                var url = '/phpdummymail/index.php/maillist/grid',
                    $el = $('#maillist-container');

                app.toggle.maillistLoader(true);
                $.get(url, function (response) {
                    app.toggle.mailIframe(false);

                    app.toggle.maillistLoader(false);

                    $el.html(response.html);

                    app.toggle.mailButtons(response.mailCount > 0);
                });
            }
        },

        mail: {
            select: function (e) {
                app.preventDefault(e);

                app.toggle.mailRemoveButton();
            },

            check: function () {
                var url = $('#url-mailcheck').attr('href');

                app.toggle.mailButtons(false);

                app.toggle.maillistLoader(true);

                $.get(url, function (response) {
                    app.load.maillist();
                });
            },

            checkAll: function (check) {
                $('#maillist-container .trigger-mail-selection').each(function (i, o) {
                    $(o).get(0).checked = check;
                });

                app.toggle.mailRemoveButton();
            },

            remove: function () {
                var ids = [],
                    $el = $('#maillist-container'),
                    $selectedEls = $el.find('.trigger-mail-selection');

                $selectedEls.each(function (i, o) {
                    ids.push($(o).val());
                });

                bootbox.confirm(
                    'Are you sure you want to delete the selected messages?',
                    function (result) {
                        if (result) {
                            $.post(
                                $('#url-mailremove').attr('href'),
                                {
                                    mailIds: ids
                                },
                                function (response) {
                                    if (response.success) {
                                        app.load.maillist();
                                    } else {
                                        bootbox.alert('Something went terribly wrong');
                                    }
                                }
                            );
                        }
                    }
                );
            },

            purge: function () {
                bootbox.confirm(
                    'Are you sure you want to delete all messages?',
                    function (result) {
                        if (result) {
                            app.toggle.mailButtons(false);
                            app.toggle.maillistLoader(true);

                            $.post(
                                $('#url-mailpurge').attr('href'),
                                function (response) {
                                    if (response.success) {
                                        app.toggle.mailRemoveButton();
                                        app.load.maillist();
                                    } else {
                                        bootbox.alert('Something went terribly wrong');
                                    }
                                }
                            );
                        }
                    }
                );
            },

            open: function (e) {
                var $this = $(this),
                    url = $('#url-mailopen').attr('href'),
                    $iframeContainer = $('.mail-iframe'),
                    $iframe = $iframeContainer.find('iframe'),
                    $noMailMessage = $('.no-mail-message');

                $noMailMessage.hide();

                $iframeContainer.show();

                $iframe.css('height', 500);

                $iframe.get(0).src = [url, $this.data('mail-id')].join('/');

                app.toggle.mailSelected($this);
                app.preventDefault(e);
            },

            unOpen: function () {
                app.toggle.mailIframe(false);
                app.toggle.mailSelected(null);
            }
        }
    };

    $(function () {
        window.app = app.init(app);
    });
}(jQuery, window));
