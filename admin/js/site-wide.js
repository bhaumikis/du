
$(document).ready(function() {
	
    //focus form first element
    $("input:text:visible:enabled:first").focus();
	
    //hide messagges after 5 sec
    setTimeout(function() {
        $('.actionMessage').slideUp('slow');
        $('.errorMessage').slideUp('slow');
    },5000);
	 
});

function prompttodelete(url){
    if(confirm("Are you sure to delete a record?")){
        window.location.href = url;
    }	
}

$(document).ready(function() {
    $("#chkall").click(function() {
        var checked_status = this.checked;
        var checkbox_name = this.name;
        $("[type=checkbox]").each(function() {
            this.checked = checked_status;
        });
    });
});

