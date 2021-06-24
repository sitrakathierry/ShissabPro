/**
 * Created by SITRAKA on 11/02/2020.
 */

$(document).ready(function(){
    charger_client();

    $(document).on('change', '.cl_filtre_client', function(){
        charger_client();
    });

    $(document).on('click', '.cl_edit_client', function(){
        var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
            action = parseInt($(this).attr('data-type'));

        $('.'+cl_row_edited).removeClass(cl_row_edited);

        if (action === 2) {
            var url = Routing.generate('client_delete',{
                id : id,
                ajax : 1
            });

            $.ajax({
                url: url,
                type: 'GET',
                datatype: 'json',
                success: function(res) {
                    charger_client();
                    show_info("SUCCESS", 'LE FICHE A ETE ARCHIVE','success');
                }
            })
        } else {
            if (id !== 0) $(this).closest('tr').addClass(cl_row_edited);
            show_editeur_client(id);
        }
    });

    $(document).on('click','#id_save_client',function(){
        var type = $('input[name="radio-cl_type"]:checked').val(),
            nom = $('#id_nom').val().trim(),
            prenom = $('#id_prenom').val().trim(),
            adresse = $('#id_adresse').val().trim(),
            bp = $('#id_bp').val().trim(),
            contacts = [],
            id = $('#contacts').attr('data-id');

        if (nom === '')
        {
            show_info('Erreur','Nom vide','error');
            return;
        }

        $('#contacts').find('tr').each(function(){
            contacts.push({
                'id': parseInt($(this).attr('data-id')),
                't': parseInt($(this).find('.cl_type').val()),
                'v': $(this).find('.cl_valeur').val()
            });
        });

        edit_client(0, id,
            {
                't': type,
                'n': nom,
                'p': prenom,
                'a': adresse,
                'bp' : bp
            },
            contacts
        );
    });
});

function charger_client()
{
    var type = {
        per: $('#id_chk_personne').is(':checked') ? 1 :0,
        soc: $('#id_chk_societe').is(':checked') ? 1 :0
    };

    $('#id_container_table').html('<table id="id_table_client"></table>');
    charger_client_in_grid('id_table_client',type);
}

function after_change_client()
{
    if ($('.'+cl_row_edited).length > 0) update_client_in_grid('id_table_client');
    else charger_client();
}
