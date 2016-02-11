/*
var a = moment("2015-10-27 07:00:00", "YYYY-MM-DD HH:mm:SS");
var b = moment("2015-10-27 07:00:00", "YYYY-MM-DD HH:mm:SS").utc();
a.format()  //"2015-10-27T07:00:00+05:30"
b.format() //"2015-10-27T01:30:00+00:00"
*/
// 
// LOCAL to UTC
function localToUtc(date, format) {
	var orig = moment(date, format);
	var utc = moment(date, format).utc();
	return utc.format(format);
}

// UTC to LOCAL
function utcToLocal(date, format) {
	var utc = moment(date, format);
	var localTime  = toUTC(moment.utc(utc).toDate());
	localTime = moment(localTime).format(format);
    return localTime;
}

function toUTC(/*Date*/date) {
    return Date.UTC(
        date.getFullYear()
        , date.getMonth()
        , date.getDate()
        , date.getHours()
        , date.getMinutes()
        , date.getSeconds()
        , date.getMilliseconds()
    );
} //toUTC()
jQuery(document).ready(function() {
	function applyUtc2local() {
		// utc datetime to local datetime 
		jQuery.each(jQuery(".utc-date-time"), function(k,v) {
			v.innerHTML = (utcToLocal(v.innerHTML, "DD/MM/YY hh:mm:SS A"));
		});
		jQuery.each(jQuery(".utc-time"), function(k,v) {
			v.innerHTML = (utcToLocal(v.innerHTML, "hh:mm:SS A"));
		});
	}
	applyUtc2local();
	
	// set current timezone in session
	$.ajax({
        type: "POST",
        url: "index/index",
        data: {
        	is_locale_set: 1,
            client_date: moment().toLocaleString(),
            client_timezone: moment().format("Z"),
        },
        success: function(response) {
            
        }
    });

});
	