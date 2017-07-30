'use strict'

export function formattedToSave(dat) {
	dat = treatAsUTC(dat);
	var curr_date = dat.getDate();
	var curr_month = dat.getMonth() + 1;
	var curr_year = dat.getFullYear();

	return (curr_year + "-" + (curr_month < 10 ? "0"+curr_month : curr_month ) + "-" + (curr_date < 10 ? "0"+curr_date : curr_date ));
}

export function formattedToSaveTime(dat) {
	var curr_date = dat.getDate();
	var curr_month = dat.getMonth() + 1;
	var curr_year = dat.getFullYear();

	var curr_hour = dat.getHours();
	var curr_min = dat.getMinutes();
	var curr_ss = dat.getSeconds();

	return (curr_year + "-" + (curr_month < 10 ? "0"+curr_month : curr_month ) + "-" + (curr_date < 10 ? "0"+curr_date : curr_date ) +  ' ' + (curr_hour < 10 ? "0"+curr_hour : curr_hour ) +  ':' + (curr_min < 10 ? "0"+curr_min : curr_min ) +  ':' + (curr_ss < 10 ? "0"+curr_ss : curr_ss ));
}

export function formattedToRu(dat) {
	var curr_date = dat.getDate();
	var curr_month = dat.getMonth() + 1;
	var curr_year = dat.getFullYear();

	return ((curr_date < 10 ? "0"+curr_date : curr_date ) + '-' + (curr_month < 10 ? "0"+curr_month : curr_month ) + '-' + curr_year);
}

export function datePlusDays(days, start_date) {
	if (! start_date)
		start_date = new Date();
	var result = start_date.setDate(start_date.getDate() + days);
	return 	result;
}

export function daysFromToday(specified_date) {
	var today = new Date();

	return ((specified_date - today)/24/60/60/1000).toFixed();
}

export function treatAsUTC(date) {
    var result = new Date(date);
    result.setMinutes(result.getMinutes() - result.getTimezoneOffset());
    return result;
}

export function daysBetween(startDate, endDate) {
    var millisecondsPerDay = 24 * 60 * 60 * 1000;
    return (treatAsUTC(endDate) - treatAsUTC(startDate)) / millisecondsPerDay;
}

export function calcWeekStartAndEnd(){
    var curDate = new Date();
    var curDayOfTheWeek = curDate.getDay();

    var startDate = new Date(datePlusDays(-curDayOfTheWeek + 1, curDate));
    // var startDate = new Date(datePlusDays(-curDayOfTheWeek, curDate));

    curDate = new Date();
    curDayOfTheWeek = curDate.getDay();
    var endDate = new Date(datePlusDays(7-curDayOfTheWeek, curDate));

    return {
    	start: startDate,
	    end: endDate,
    }
}