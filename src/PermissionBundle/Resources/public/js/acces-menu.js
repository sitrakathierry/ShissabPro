$(document).ready(function () {
    set_accordion();
    var window_height = window.innerHeight;
    var menu_list = $('#menu-list');
    var tab_container = $('#tab-container');

    tab_container.height(window_height - 150)
    menu_list.height(tab_container.height() - 100);

    setTimeout(function() {
        menu_list.nestable({
            group: 0,
            maxDepth: 2,
            reject: [{
                rule: function () {
                    var ils = $(this).find('>ol.dd-list > li.dd-item');
                    for (var i = 0; i < ils.length; i++) {
                        var datatype = $(ils[i]).data('type');
                        if (datatype === 'child')
                            return true;
                    }
                    return false;
                },
                action: function (nestable) {
                }
            }]
        }).nestable('collapseAll');
        menu_list.nestable({handleClass:'123'});  
    },1000);

    $(document).on('change', '.menu-select', function () {
        var checkbox = $(this);
        var state = checkbox.prop('checked');
        var level = checkbox.attr('data-level');

        /* MAJ descendant */
        checkbox.closest('.dd-item')
            .find('.menu-select')
            .prop('checked', state);

        /* MAJ ascendant  */
        if (state === true) {
            if (level === '1') {
                //Pas de parent
            } else if (level === '2') {
                //On cocher parent N+1
                checkbox.closest('.dd-list')
                    .closest('.dd-item')
                    .find('.menu-select[data-level="1"]')
                    .prop('checked', state);
            } else if (level === '3') {
                //On cocher parent N+1 et N+2
                checkbox.closest('.dd-list')
                    .closest('.dd-item')
                    .find('.menu-select[data-level="2"]')
                    .prop('checked', state);
                checkbox.closest('.dd-list')
                    .closest('.dd-item')
                    .find('.menu-select[data-level="2"]')
                    .closest('.dd-list')
                    .closest('.dd-item')
                    .find('.menu-select[data-level="1"]')
                    .prop('checked', state);
            }
        }
    });

    /* Ouvrir tout / Réduire tout - liste menus - acces operateur */
    $(document).on('click', '.btn-collapse-list-menu', function (event) {
        event.preventDefault();
        var target = $(this).attr('data-target');
        var action = $(this).attr('data-action');
        if (action === 'expand-all') {
            $(target).nestable('expandAll');
        } else {
            $(target).nestable('collapseAll');
        }
    });

    $(document).on('click', '.show-menu-societe-by-user', function (event) {
        event.preventDefault();
        $('.liste-user-menu')
            .find('.list-group-item')
            .removeClass('active');
        $(this).addClass('active');
        $('#btn-refresh-menu-acces').removeClass('hidden');
        $('#btn-save-menu-acces').attr('id', 'btn-save-menu-user');
        menu_list.find('.menu-select').prop('checked', false);
        menu_list.removeClass('hidden');
        var poste = $(this).attr('data-post-id');
        var user = $(this).attr('data-user-id');
        $('#btn-refresh-menu-acces').attr('data-id', user);

        $.ajax({
            url: Routing.generate('permission_menu_par_operateur', {user: user}),
            type: 'GET',
            data: {},
            success: function (data) {
                data = $.parseJSON(data);
                setMenuSettingsUser(data.menus, menu_list, data.menusRefuser);
                $('#btn-save-menu-user').trigger('click');
            }
        });
    });

    $(document).on('click', '.show-menu-societe', function (event) {
        event.preventDefault();
        $('.liste-user-menu')
            .find('.list-group-item')
            .removeClass('active');
        $('#btn-refresh-menu-acces').addClass('hidden');
        $('#id_acces_menu_accordion').find('.show-menu-societe').removeClass('active');
        $('#id_acces_menu_accordion').find('.show-menu-societe').parent().parent().removeAttr('style');
        $(this).parent().parent().css('background-color', '#d9edf7');
        $(this).addClass('active');
        $('#id_acces_menu_accordion').find('.panel-collapse').removeClass('in');
        $('#id_acces_menu_accordion').find('.panel-collapse').attr('aria-expanded', false);
        $('#btn-save-menu-user').attr('id', 'btn-save-menu-acces');
        menu_list.find('.menu-select').prop('checked', false);
        menu_list.removeClass('hidden');
        var agence = $(this).attr('data-id');

        $.ajax({
            url: Routing.generate('permission_menu_par_poste', {agence: agence}),
            type: 'GET',
            data: {},
            success: function (data) {
                data = $.parseJSON(data);
                setMenuSettings(data, menu_list);
            }
        });
    });

    /* Enregistrer Menus par societe */
    $(document).on('click', '#btn-save-menu-acces', function (event) {
        event.preventDefault();
        if ($('.show-menu-societe.active').length > 0) {
            var agence = $('.show-menu-societe.active')
                .attr('data-id');
            var menus = [];
            menu_list.find('.menu-select').each(function (index, item) {
                var state = $(item).prop('checked');
                if (state === true) {
                    menus.push({
                        menu: $(item).attr('data-menu-id'),
                    });
                }
            });
            $.ajax({
                url: Routing.generate('permission_acces_menu_par_poste_edit', {agence: agence}),
                type: 'POST',
                data: {
                    menus: menus
                },
                success: function (data) {
                    data = $.parseJSON(data);
                    if (data.erreur === false) {
                        show_info("", "Paramètres enregistrés.", "success");
                        menu_list.find('.menu-select').prop('checked', false);
                        setMenuSettings(data.menus, menu_list)
                    } else {
                        show_info("", data.erreur_text, "error");
                    }
                }
            });
        } else {
            show_info("", "Séléctionner un rôle.", "warning");
        }
    });

    $(document).on('click', '#btn-save-menu-user', function (event) {
        event.preventDefault();
        if ($('.liste-user-menu').find('.list-group-item.active').length > 0) {
            var user = $('.liste-user-menu').find('.list-group-item.active')
                .attr('data-id');
            var menus = [];
            menu_list.find('.menu-select').each(function (index, item) {
                var state = $(item).prop('checked');
                if (state === true) {
                    menus.push({
                        menu: $(item).attr('data-menu-id')
                    });
                }
            });

            $.ajax({
                url: Routing.generate('permission_menu_par_operateur_edit', {user: user}),
                type: 'POST',
                data: {
                    menus: menus
                },
                success: function (data) {
                    data = $.parseJSON(data);
                    if (data.erreur === false) {
                        show_info("", "Paramètres enregistrés.", "success");
                        menu_list.find('.menu-select').prop('checked', false);
                        setMenuSettingsUser(data.menus, menu_list);
                    } else {
                        show_info("", data.erreur_text, "error");
                    }
                }
            });
        } else {
            show_info("", "Séléctionner un rôle.", "warning");
        }
    });

    function setMenuSettingsUser(data, parent, dataPoste) {
        if (typeof data === 'undefined') {
            return;
        }

        if(dataPoste){
            parent.find('.menu-select').attr('disabled', 'disabled');
            $.each(dataPoste, function(index, item) {
                if (typeof item.menu !== 'undefined' && item.menu !== null) {
                    var search = parent.find('.menu-select[data-menu-id="' + item.menu.id + '"]');
                    if (search.length > 0) {
                        search.removeAttr('disabled');
                    }
                }
            });
        }

        $.each(data, function(index, item) {
            if (typeof item.menu !== 'undefined' && item.menu !== null) {
                var search = parent.find('.menu-select[data-menu-id="' + item.menu.id + '"]');
                if (search.length > 0 && $(search).filter("[disabled]").length === 0) {
                    search.prop('checked', true);
                }
            }
        });
    }

    function setMenuSettings(data, parent) {
        if (typeof data === 'undefined') {
            return;
        }

        parent.find('.menu-select').removeAttr('disabled');
        $.each(data, function(index, item) {
            if (typeof item.menu !== 'undefined' && item.menu !== null) {
                var search = parent.find('.menu-select[data-menu-id="' + item.menu.id + '"]');
                if (search.length > 0) {
                    search.prop('checked', true);
                }
            }
        });
    }
});


function set_accordion()
{
    $('#id_acces_menu_accordion').on('show.bs.collapse', function (e) {
        if ($(e.target).closest('.panel').hasClass('js_regime'))
        {
            $(e.target).closest('.panel').find('.cl_taches_edit').removeClass('hidden');
            $(e.target).closest('.panel').find('.cl_edit_taches_group').removeClass('hidden');
        }
    }).on('hide.bs.collapse', function (e) {
        if ($(e.target).closest('.panel').hasClass('js_regime'))
        {
            $(e.target).closest('.panel').find('.cl_taches_edit').addClass('hidden');
            $(e.target).closest('.panel').find('.cl_edit_taches_group').addClass('hidden');
        }
    });
}