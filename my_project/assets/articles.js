
//recupération de la liste des articles => ok

//création d'une ligne tableau (html) => ok

//append de la ligne => ok

var $ = require('jquery');
window.$ = window.jQuery = $;
var url = $('#url-articles-list').val();
$.ajax({url: url, success: function(result){
    if(result.length){
        $('#tab-liste-article  tbody tr').remove();
    }
    $.each(result, function (index, value) {
        var tr='<tr><td>'+value.id+'</td><td>'+value.titre+'</td><td>'+value.contenu+'</td><td>'+value.auteur+'</td><td>'+value.datepublication+'</td></tr>';
        $('#tab-liste-article').append(tr);
    });
  }});