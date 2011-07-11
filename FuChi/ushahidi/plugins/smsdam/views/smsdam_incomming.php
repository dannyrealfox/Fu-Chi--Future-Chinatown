<script type="text/javascript">



var count = 0;
var new_count = 0;
var showing_count = 0;
var skip_check = 0;

function loadAllMessages() {
	$.getJSON("<?php echo url::site(); ?>smsdam/json/?key=<?php echo $frontlinesmskey; ?>",
		function(data){
			showing_count = 0;
			var table = '<table><tr><th style="width:150px;">Date Received</th><th style="width:120px;">Phone Number</th><th>Message</th><th style="width:75px;"></th><th style="width:75px;"></th></tr>';
			$.each(data, function(i,item){
				table += '<tr style="border-bottom:1px solid #CCC;"><td>'+item.date_received+'</td><td>'+item.usid+'</td><td>'+item.message+'</td><td><a href="javascript:void(0);" id="smsdam_message_'+i+'" onClick="javascript:smsdam_holdBack('+i+');">Hold Back</a></td><td><a href="javascript:void(0);" id="smsdam_message_'+i+'" onClick="javascript:smsdam_approve('+i+');">Approve</a></td></tr>';
				showing_count++;
			});
			table += '</table>';
			$("#smsdam_messages").html(table);
		});
}

function smsdam_approve(i) {
	$.get("<?php echo url::site(); ?>smsdam/decide/?key=<?php echo $frontlinesmskey; ?>", { status: 1, id: i } );
	window.setTimeout('loadAllMessages()',1000);
	skip_check = 1;
}

function smsdam_holdBack(i) {
	$.get("<?php echo url::site(); ?>smsdam/decide/?key=<?php echo $frontlinesmskey; ?>", { status: 2, id: i } );
	window.setTimeout('loadAllMessages()',1000);
	skip_check = 1;
}

function loadNewMessages() {
	loadAllMessages();
	$('#smsdam_loadnewmessages').toggle(0);
}

function checkMessageCount() {
    $.get("<?php echo url::site(); ?>smsdam/messagecount/?key=<?php echo $frontlinesmskey; ?>", getCount);
	var message = '&nbsp;';
	var difference = new_count - showing_count;
	if(difference > 0)
	{
		// We have new messages
		message = '<a href="javascript:void(0);" id="smsdam_loadnewmessages" onClick="javascript:loadNewMessages();">'+difference+' new message(s). Load new message(s).</a>&nbsp;';
	}
	if(skip_check == 0)
	{
		$("#smsdam_new_messages").html(message);
	}
	count = new_count;
	skip_check = 0;
}

function getCount(data) {
    new_count = data;
}

jQuery(function() {
	checkMessageCount();
	loadAllMessages();
    setInterval(checkMessageCount, 30000);
});
</script>

<div id="smsdam_new_messages">&nbsp;</div>
<div id="smsdam_messages"><img src="<?php echo url::base() . "media/img/loading_g2.gif"; ?>" alt="Loading..." /></div>
