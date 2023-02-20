$(document).ready(function(){
    load_list_produit_deduit();
    
    function load_list_produit_deduit() {

        var url = Routing.generate('produit_list_deduit');

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function(res) {
                $('#liste_produit_deduit').html(res);
            }
        });
    }

})