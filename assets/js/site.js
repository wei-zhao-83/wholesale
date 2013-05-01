$(document).ready(function() {
    $('.btn-add').live('click', function() {
        var tbl_id = $(this).parents('table').attr('id');
        var id_str = $('#' + tbl_id + ' tbody>tr:last').attr('id').split("-");
        var current_id = parseInt(id_str[1]);
        var myRegExp = new RegExp(current_id, 'ig');
        
        $string = $('#' + tbl_id + ' tbody>tr:last').clone(true).wrap("<div />").parent().html().replace(myRegExp, current_id + 1);
        $(this).parents('table').append($string);
        
        return false;
    });
    
    $('.btn-remove').live('click', function() {
        var id_str = $(this).parents('tr').attr('id').split("-");
        var current_id = parseInt(id_str[1]);
        if (current_id != 0) {
            $(this).parents('tr').remove();
        }
        
        return false;
    });
    
    $('.btn-remove-current').live('click', function() {
        $(this).parents('tr').remove();
        
        return false;
    });
    
    $('.check-all').click(function () {
        $(this).parents('ul').find(':checkbox').attr('checked', this.checked);
    });
});