$(document).ready(function(){
    if($('.ad-star-action')) {
        $('.ad-start-action').click(function(){
            var activeElement = $(this);
            var actionType =  activeElement.data('action');
            var moduleid = activeElement.data('moduleid');

            switch(actionType) {
                case 'toggle_star':
                    $.get({
                        type: 'POST',
                        url : 'api/ads.php',
                        data : {
                            action : 'toggle_star',
                            recno : moduleid
                        },
                        success : function(response){

                            if(response) {
                                let responseData = JSON.parse(response);

                                if(responseData.data.star_id == 0) {
                                    activeElement.addClass('btn-outline-secondary');
                                    activeElement.removeClass('btn-success');
                                    console.log('removed star');
                                } else {
                                    activeElement.addClass('btn-success');
                                    activeElement.removeClass('btn-outline-secondary');
                                }
                            }
                        }
                    })
                break;
            }
        });
    }
});