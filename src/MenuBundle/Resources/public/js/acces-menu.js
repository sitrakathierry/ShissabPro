$(document).ready(function () {
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

    $(document).on('change','#access_role', function(event) {
        var role = $(this).val();

        var url = Routing.generate('menu_access_role', {
            role: role
        });

        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                $('.menu-list').html(data);
                var updateOutput = function (e) {
                    var list = e.length ? e : $(e.target),
                       output = list.data('output');
                    if (window.JSON) {
                       output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
                    } else {
                       output.val('JSON browser support required for this demo.');
                    }
                };

                $('#nestable2').nestable({
                    group: 1,
                    maxDepth: 0,
                    noDragClass:'no-drag'
                })
                // .on('change', updateOutput);
                updateOutput($('#nestable2').data('output', $('#nestable2-output')));
            }
        });
    });


    $(document).on('click','#btn_save',function(event) {
        event.preventDefault();

        var checked_menus = [];
        var unchecked_menus = [];

        $('.check-menu').each(function() { 
              // this.checked = true; 
            var checked = $(this).is(":checked");

            var menu_id = $(this).data('menu')

            if (checked) {
                checked_menus.push(menu_id);
            } else {
                unchecked_menus.push(menu_id);
            }
        });

        $('#checked_menus').val(JSON.stringify(checked_menus));
        $('#unchecked_menus').val(JSON.stringify(unchecked_menus));
        
        var url = Routing.generate('menu_access_role_save');

        var post = {
            access_role : $('#access_role').val(),
            checked_menus : $('#checked_menus').val(),
            unchecked_menus : $('#unchecked_menus').val()
        };

        $.ajax({
            url: url,
            type: 'POST',
            data: post,
            success: function(data) {
                if (data == 200) {
                    show_info("Succes", 'Menu Enregistré','success');
                }
            }
        });
      });
});
