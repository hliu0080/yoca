$(document).ready(function() {
//	$('#mentor_usertable').dataTable( {
////		"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
//		"aoColumnDefs": [
//		    { "bSortable": false, "aTargets": [ 8 ] }
//		]
//	});
	
	
	
	$('#mentee_usertable').dataTable( {
		"sDom": "<'row'<'span6'><'span6'f>r>t<'row'<'span6'><'span6'p>>",
		"bLengthChange": false,
		"bInfo": false,
		"bPaginate": false,
		"aoColumnDefs": [
		    { "bSortable": false, "aTargets": [ 6 ] }
		]
	});
	$('#member_usertable').dataTable( {
		"sDom": "<'row'<'span6'><'span6'f>r>t<'row'<'span6'><'span6'p>>",
		"bLengthChange": false,
		"bInfo": false,
		"bPaginate": false,
		"aoColumnDefs": [
		    { "bSortable": false, "aTargets": [ 0 ] }
		]
	});
	$('#admin_usertable').dataTable( {
		"sDom": "<'row'<'span6'><'span6'f>r>t<'row'<'span6'><'span6'p>>",
		"bLengthChange": false,
		"bInfo": false,
		"bPaginate": false,
		"aoColumnDefs": [
		    { "bSortable": false, "aTargets": [ 4 ] }
		]
	});
});