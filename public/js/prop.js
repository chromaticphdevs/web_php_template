$(function(){

    if($('#proptypecode') && $('#propclasscodes')) {
        let proptypecode = $('#proptypecode');
        let propclasscodes = $('#propclasscodes');
        

        $(proptypecode).change(function(){
            $.ajax({
                type : 'GET',
                url : 'api/fetchpropertyclass.php',
                data : {
                    proptypecode : $(proptypecode).val()
                },

                success : function(response) {
                    let responseJSON = JSON.parse(response);
                    if(responseJSON.data) {
                        $(propclasscodes).empty();

                        $.each(responseJSON.data, function(key, value){
                            if(key == 0) {
                                $(propclasscodes).append($("<option> </option>").attr('value', '').text('--Select'));
                            }
                            $(propclasscodes).append($("<option> </option>").attr('value', value.propclasscode).text(value.propclassdesc));
                        });
                    }
                }
            });
        });

        
    }
});