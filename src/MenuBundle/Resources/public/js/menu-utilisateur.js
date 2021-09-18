var user_search = document.getElementById('user-search');
var window_height = window.innerHeight;
var tab_container = $('#tab-container');
var acces_role = $('#acces-role');
var acces_user = $('#acces-user');
var role_list = $('#role-list');
var menu_list_user = $('#menu-list-user');
var user_list = $('#user-list');
var client_user = $('#client-user');
var selected_user = null;
var item_selected_user = null;

tab_container.height(window_height - 150);
role_list.height(tab_container.height() - 100);
user_list.height(tab_container.height() - 170);
menu_list_user.height(user_list.height() + 35);

$.ajax({
    url: Routing.generate('menu_liste_complet'),
    type: 'GET',
    success: function(data) {
        menu_list_user.html(data);
        setTimeout(function() {
            /* Activer Nestable List */
            menu_list_user.nestable({
                group: 1
            }).nestable('collapseAll');
        }, 1000);
    }
});

/* Ouvrir tout / Réduire tout - liste menus - role */
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

$(document).on('click', '#user-list .list-group-item', function (event) {
        event.preventDefault();

        $(this)
            .closest('.list-group')
            .find('.list-group-item')
            .removeClass('active');
        $(this).addClass('active');
        menu_list_user.find('.menu-select').prop('checked', false);
        // menu_list_user.find('.type-access').bootstrapToggle('off');
        menu_list_user.removeClass('hidden');

        var user_id = $(this).attr('data-id');
        item_selected_user = $(this);
        selected_user = user_id;

        $.ajax({
            url: Routing.generate('menu_utilisateur_list', {user: user_id}),
            type: 'POST',
            data: {},
            success: function (data) {
                data = $.parseJSON(data);
                setMenuSettings(data, menu_list_user);
            }
        });
    });

function setMenuSettings(data, parent) {
    if (typeof data === 'undefined') {
        return;
    }
    $.each(data, function(index, item) {
        if (typeof item.menu !== 'undefined' && item.menu !== null) {
            var search = parent.find('.menu-select[data-menu-id="' + item.menu.id + '"]');
            if (search.length > 0) {
                search.prop('checked', true);
                var check_parent = search.closest('.dd-handle');
                var type_access = check_parent.find('.type-access');
                // if (item.canEdit === true) {
                //     type_access.bootstrapToggle('on');
                // } else {
                //     type_access.bootstrapToggle('off');
                // }
            }
        }
    });
}

/* Enregistrer Menus par Utilisateur */
$(document).on('click', '#btn-save-menu-user', function (event) {
    event.preventDefault();
    saveUserMenus();
});

function saveUserMenus() {
        if (user_list.find('.list-group-item.active').length > 0) {
            var user = user_list.find('.list-group-item.active')
                .attr('data-id');
            var menus = [];
            menu_list_user.find('.menu-select').each(function (index, item) {
                var state = $(item).prop('checked');
                if (state === true) {
                    menus.push({
                        menu: $(item).attr('data-menu-id')
                    });
                }
            });

            $.ajax({
                url: Routing.generate('menu_utilisateur_save', {user: user}),
                type: 'POST',
                data: {
                    menus: menus
                },
                success: function (data) {
                    data = $.parseJSON(data);
                    if (data.erreur === false) {
                        show_info("", "Paramètres enregistrés.", "success");
                        menu_list_user.find('.menu-select').prop('checked', false);
                        setMenuSettings(data.menus, menu_list_user);
                    } else {
                        show_info("", data.erreur_text, "error");
                    }
                }
            });
        } else {
            show_info("", "Séléctionner un rôle.", "warning");
        }
    }

/* Cocher / Décocher un menu - Changer les childs - role */
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
                //On cocher prent N+1 et N+2
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

    /** Chercher un utilisateur */
    user_search.addEventListener('keyup', makeDebounce(function(e) {
        var search_text = accent_fold(e.target.value).toLowerCase();
        $('#user-list').find('.list-group-item').each(function(index, item) {
            var item_text = accent_fold($(item).text()).toLowerCase();
            if (item_text.indexOf(search_text) >= 0) {
                $(item).removeClass('hidden');
            } else {
                $(item).addClass('hidden');
            }
        });
    }, 500));

    /*$(document).on('click', '#btn-override-menu-user', function (event) {
        event.preventDefault();
        saveUserMenus(1);
    });*/

    function makeDebounce(callback, delay){
    var timer;
    return function(){
        var args = arguments;
        var context = this;
        clearTimeout(timer);
        timer = setTimeout(function(){
            callback.apply(context, args);
        }, delay)
    }
}

