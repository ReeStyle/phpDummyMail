(function ($, window) {
    var app = {
        init: function (ns) {
            app.load.maillist();

            $('.trigger-mailcheck').on('click', app.check.mail);
            $('.trigger-purge').on('click', app.purge);

            return ns;
        },

        toggleMaillistLoader: function (show) {
            var $insertEl = $('#maillist-container'),
                $loaderEl = $('.maillist-loader'),
                loaderElHtml = '<div class="maillist-loader text-center"><span class="fa fa-spinner fa-spin"></span></div>';

            if (show) {
                $insertEl.html('').html(loaderElHtml);
            } else {
                $loaderEl.remove();
            }
        },

        toggleMailButtons: function (show) {
            if (show) {
                $('.group-enable-disable > button, .button-enable-disable').removeAttr('disabled');
            } else {
                $('.group-enable-disable > button, .button-enable-disable').attr('disabled', 'disabled');
            }
        },

        load: {
            maillist: function () {
                var url = '/phpdummymail/index.php/maillist/grid',
                $el = $('#maillist-container');

                app.toggleMaillistLoader(true);
                $.get(url, function (response) {
                    $el.html(response.html);

                    app.toggleMailButtons(response.mailCount > 0);
                });
            }
        },

        purge: function () {
            bootbox.confirm(
                'Are you sure you want to delete all messages?',
                function (result) {
                    if (result) {
                        app.toggleMailButtons(false);
                        app.toggleMaillistLoader(true);

                        $.post(
                            $('#url-mailpurge').attr('href'),
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

        remove: function () {
            bootbox.confirm(
                'Are you sure you want to delete the selected messages?',
                function (result) {
                    if (result) {
                        // Loader
                        // Remove messages call
                        // Trigger maillist load
                    }
                }
            );
        },

        check: {
            mail: function () {
                var url = $('#url-mailcheck').attr('href');

                app.toggleMailButtons(false);

                app.toggleMaillistLoader(true);

                $.get(url, function (response) {
                    app.load.maillist();
                });
            }
        }
    };

    $(function () {
        window.app = app.init(app);
    });
}(jQuery, window));
