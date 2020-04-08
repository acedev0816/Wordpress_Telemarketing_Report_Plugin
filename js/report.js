if (typeof $ != 'undefined') {
$(document).ready(function() {
    var date = new Date();
    var month = new Array();
    month[0] = "January";
    month[1] = "February";
    month[2] = "March";
    month[3] = "April";
    month[4] = "May";
    month[5] = "June";
    month[6] = "July";
    month[7] = "August";
    month[8] = "September";
    month[9] = "October";
    month[10] = "November";
    month[11] = "December";
    document.getElementById('yearCaption').innerHTML = date.getFullYear();
    document.getElementById('monthCaption').innerHTML = month[date.getMonth()];
    document.getElementById('dayCaption').innerHTML = date.getDate();
    
    //get team list
    $.post("../wp-admin/admin-ajax.php",{ action: "handle_get_team_list"}, 
     function(data){
        var team_data = JSON.parse(data);
        console.log(team_data);
        for(i = 0; i< team_data.length; i++)
        {
            var o = new Option(team_data[i].name, team_data[i].id);
            $("#team").append(o);
        }
        get_current_user();
     });
    //get current user name
     function get_current_user(){
     $.post("../wp-admin/admin-ajax.php",{ action: "handle_get_name"},  
     function(data){
        console.log('handle_get_name begin');
        user = JSON.parse(data);
        if (user.display_name == false)
        {    
            user.display_name = "Not logged in.";
            $("#submit").hide();
        }  
        $('#lb_name').text('Your name: ' + user.display_name );
        $('#member').val( user.id );
        $('#team option').filter(function(){
               return this.value == user.team;
        }).prop('selected', true);
        console.log(user);  
     });
     }

    $("#submit").click(function() {

        $.post("../wp-admin/admin-ajax.php", $("#report_form").serialize(), function(data) {
            console.log(data);            
            if (data == "yes")
                $("#report_msg").text('Your report submitted successfully!');
            else
                $("#report_msg").text('Sorry but your report was not submitted. Please try again.');
            $("#msgbox").modal('show');
        });
    });
});
}