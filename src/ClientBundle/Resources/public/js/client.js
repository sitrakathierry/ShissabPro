/**
 * Created by SITRAKA on 11/02/2020.
 */

var cl_row_edited = 'r-cl-edited';

function charger_client_in_grid(id, type)
{
    type = typeof type !== 'undefined' ? type :
        {
            per: 1,
            soc: 1
        };

    $.ajax({
        data: {
            types: JSON.stringify(type)
        },
        type: 'POST',
        url: Routing.generate('client_liste'),
        dataType: 'html',
        success: function(data) {
            $('#' + id).jqGrid({
                data: $.parseJSON(data),
                datatype: "local",
                height: 450,
                autowidth: true,
                shrinkToFit: true,
                rowNum: 20,
                rowList: [10, 20, 30],
                colNames:['Nom','Prénom/Forme Juridique',''],
                colModel:[
                    { 
                        name:'n',
                        index:'n',
                        align: 'center' 
                    },
                    { 
                        name:'p',
                        index:'p',
                        align: 'center'
                    },
                    {
                        name:'x',
                        index:'x',
                        align: 'center',
                        formatter: function(v){ 
                            return '<i class="fa fa-pencil-square-o pointer cl_edit_client" data-type="0" aria-hidden="true"></i>&nbsp;&nbsp;<i class="fa fa-trash-o pointer cl_edit_client" data-type="2" aria-hidden="true"></i>' }
                    }
                ],
                //pager: "#pager_list_2",
                viewrecords: true,
                //caption: "Example jqGrid 2",
                add: true,
                edit: true,
                addtext: 'Add',
                edittext: 'Edit',
                hidegrid: false
            });
        }
    });
}

function show_editeur_client(id)
{
    id = typeof id !== 'undefined' ? id : 0;

    $.ajax({
        data: {
            id: id
        },
        type: 'POST',
        url: Routing.generate('client_editeur'),
        dataType: 'html',
        success: function(data) {
            show_modal(data,'Edition Client');
        }
    });
}

function edit_client(act, id, props, contacts)
{
    $.ajax({
        data: {
            act: act,
            id: id,
            props: JSON.stringify(props),
            contacts: JSON.stringify(contacts)
        },
        type: 'POST',
        url: Routing.generate('client_edit'),
        dataType: 'html',
        success: function(data) {
            show_info('Succés','Modification bien enregistrée avec succés');
            close_modal();
            if(typeof after_change_client === 'function') after_change_client();
        }
    });
}

function update_client_in_grid(table_id, id)
{
    id = (typeof id === 'undefined') ? $('.' + cl_row_edited).attr('id') : id;
    $.ajax({
        data: {
            id: id
        },
        url: Routing.generate('client_grid'),
        type: 'POST',
        dataType: 'html',
        success: function(data){
            test_security(data);
            var newData = $.parseJSON(data);
            newData.id = id;
            $('#'+table_id).jqGrid('setRowData', id, newData);
        }
    });
}