// validation scripts
function eventValidation(f)
{
	if(f.event_title.value.match(regEx.blank))
	{
		Alert('Please type title');
		Focus(f.event_title);
		return false;
	}
	if(f.event_desc.value.match(regEx.blank))
	{
		Alert('Please type event description');
		Focus(f.event_desc);
		return false;
	}
	return true;
}
// EOF validation scripts

function loadCalendar(year,month)
{
	$.ajax({
			type: "POST",
			url: "calendar_ajax.php?mode=loadCalendar&year="+year+"&month="+month,
			data: "",
			success: function(msg){
				$('#calendar').html(msg);
			}
	});
}

function showEventList(date)
{
	$.ajax({
			type: "POST",
			url: "calendar_ajax.php?mode=showEventList&date="+date,
			data: "",
			success: function(msg){
				openConsole(msg);
			}
	});
}

function openConsole(data)
{
	closeConsole();
	$('#console').show("slow");
	$('#console #content').html(data);
}

function closeConsole()
{
	$('#console').hide("slow");
}

function loadAddEventForm(date)
{
	$.ajax({
			type: "POST",
			url: "calendar_ajax.php?mode=loadAddEventForm&date="+date,
			data: "",
			success: function(msg){
				openConsole(msg);
				loadDatePicker('event_date');
			}
	});
}

function saveEvent()
{
	eventValidated=false;
	eventValidated=eventValidation(document.getElementById('add_event_form'));
	if(!eventValidated)
	{
		return false;	
	}
	data=$("#add_event_form").serialize();
	$.ajax({
			type: "POST",
			url: "calendar_ajax.php?mode=saveEvent",
			data: data,
			success: function(msg){
				date_arr=msg.split("-");
				year=date_arr[0];
				month=date_arr[1];
				loadCalendar(year,month);
				showEventList(msg);
			}
	});
}

function loadEditEventForm(event_id)
{
	$.ajax({
			type: "POST",
			url: "calendar_ajax.php?mode=loadEditEventForm&event_id="+event_id,
			data: "",
			success: function(msg){
				openConsole(msg);
				loadDatePicker('event_date');
			}
	});
}

function updateEvent()
{
	eventValidated=false;
	eventValidated=eventValidation(document.getElementById('edit_event_form'));
	if(!eventValidated)
	{
		return false;	
	}
	data=$("#edit_event_form").serialize();
	$.ajax({
			type: "POST",
			url: "calendar_ajax.php?mode=updateEvent",
			data: data,
			success: function(msg){
				date_arr=msg.split("-");
				year=date_arr[0];
				month=date_arr[1];
				loadCalendar(year,month);
				showEventList(msg);
			}
	});
}

function deleteEvent(event_id)
{
	$.ajax({
			type: "POST",
			url: "calendar_ajax.php?mode=deleteEvent&event_id="+event_id,
			data: "",
			success: function(msg){
				date_arr=msg.split("-");
				year=date_arr[0];
				month=date_arr[1];
				loadCalendar(year,month);
				showEventList(msg);
			}
	});
}

function loadDatePicker(elementId)
{
	var dateFormat='yy-mm-dd';
	if(arguments[1])
	{
		dateFormat=arguments[1];
	}
	$(function() {
					$('#'+elementId).datepicker({
						numberOfMonths: 1,
						dateFormat: dateFormat,
						showButtonPanel: false,
						stepMonths:1,
						closeText:'Select Date',
						changeMonth: true,
						yearRange: '-5:+5',
						changeYear: true
					});
				});	
}

function loadDatePickerLongYear(elementId)
{
	var dateFormat='yy-mm-dd';
	var year_limit=5;
	if(arguments[1])
	{
		dateFormat=arguments[1];
	}
	if(arguments[2])
	{
		year_limit=arguments[2];
	}
	$(function() {
					$('#'+elementId).datepicker({
						numberOfMonths: 1,
						dateFormat: dateFormat,
						showButtonPanel: false,
						stepMonths:1,
						closeText:'Select Date',
						changeMonth: true,
						yearRange: '-'+year_limit+':+'+year_limit+'',
						changeYear: true
					});
				});	
}

// user function
function saveUserEvent()
{
	eventValidated=false;
	eventValidated=eventValidation(document.getElementById('add_event_form'));
	if(!eventValidated)
	{
		return false;	
	}
	data=$("#add_event_form").serialize();
	$.ajax({
			type: "POST",
			url: "calendar_ajax.php?mode=saveEvent",
			data: data,
			success: function(msg){
				//date_arr=msg.split("-");
//				year=date_arr[0];
//				month=date_arr[1];
//				loadCalendar(year,month);
//				showUserEventList(msg);
				openUserConsole(msg);
			}
	});
}

function showUserEventList(date)
{
	$.ajax({
			type: "POST",
			url: "calendar_ajax.php?mode=showEventList&date="+date,
			data: "",
			success: function(msg){
				openUserConsole(msg);
			}
	});
}

function openUserConsole(data)
{
	closeConsole();
	$('#console').show("slow");
	$('#console #content').html(data);
}

function closeConsole()
{
	$('#console').hide("slow");
}

function postThisPoll(pollId)
{
	formElement=$('#poll'+pollId);
	data=formElement.serialize();
	$.ajax({
			type: "POST",
			url: "poll_post_ajax.php",
			data: data,
			success: function(msg){
				$('#poll_msg'+pollId).html(msg);
			}
	});	
}


