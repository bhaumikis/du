function prompttodelete(url){
    if(confirm("Are you sure to delete user?")){
        window.location.href = url;
    }
}
function closeDialogBox() {
    $("#dialog-form").dialog("close");
    window.location.reload();
}
function prompttodeletetemplate(url){
    if(confirm("Are you sure to delete email template?")){
        window.location.href = url;
    }
}
function prompttosetticketadmin(url){
    if(confirm("Are you sure to give rights to this admin for query assignment?")){
        window.location.href = url;
    }
}
function prompttounsetticketadmin(url){
    if(confirm("Are you sure to remove rights form this admin for query assignment?")){
        window.location.href = url;
    }
}

function getMSDrowpdownValidate(strFormName,strFieldName,strMessage)
{
    var isDestinationValid = $('#'+strFormName).validate().element($('#'+strFieldName));
    
    if(isDestinationValid){
        $('#'+strFieldName+'_msdd').tooltip('destroy');
    }
    else{
        $('#'+strFieldName+'_msdd').attr("title",strMessage);
        $('#'+strFieldName+'_msdd').tooltip('show');
    }    
}

jQuery.validator.addMethod("greaterThanEqualsDate",
        function(value, element, params) {
            if (!/Invalid|NaN/.test(new Date(value))) {
                return new Date(value) >= new Date($(params).val());
            }

            return isNaN(value) && isNaN($(params).val())
                    || (Number(value) >= Number($(params).val()));
        }
, 'Must be greater than or equal to {0}.');

jQuery.validator.addMethod("greaterDate", function (value, element, params) {
    return this.optional(element) || new Date(value) >= new Date($(params).val());
},'Must be greater than start date.');

$.validator.addMethod("validateAlphaNum", function(value, element) {
    return this.optional(element) || /^[a-zAz0-9]+$/i.test(value);
}, "Directory name must contain only letters and numbers.");

function prompttodeletevendor(url){
    if(confirm("Are you sure to delete vendor?")){
        window.location.href = url;
    }
}