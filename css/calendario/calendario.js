function selected(b,a){
	b.sel.value=a;
	if(b.sel.id=="sel1"||b.sel.id=="sel3"){
		b.callCloseHandler()
	}
}

function closeHandler(a){
	a.hide()
}

function showCalendar(c,d){
	var b=document.getElementById(c);
	if(calendar!=null){
		calendar.hide()
		}else{
			var a=new Calendar(false,null,selected,closeHandler);
			calendar=a;
			a.setRange(1900,2070);
			a.create()}calendar.setDateFormat(d);
			calendar.parseDate(b.value);
			calendar.sel=b;
			calendar.showAtElement(b);
			return false
		}
		
		var MINUTE=60*1000;
		var HOUR=60*MINUTE;
		var DAY=24*HOUR;
		var WEEK=7*DAY;
		
		function isDisabled(b){
			var a=new Date();
			return(Math.abs(b.getTime()-a.getTime())/DAY)>10;
		}