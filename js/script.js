//submit student to queue
$(document).ready(function() { 
	$("#join_btn").on("click", function(){
		if ($('#help').val() == '' || $('#loc').val() == '' ) {
			alert("All fields are required!");
		} else {
			$.ajax({
				type: "GET",
				url: 'enqueue.php',
				data: $('#join_queue').serialize(),
				success: function(data) {
					$('#your_location').html(data);
					$('#join_queue').replaceWith($('#your_location'));
					$('#your_location').fadeIn('fast');
				}
			});
		}
		return false;
	});
});

$(document).ready(function(){
	$("body").on("click", "#change_loc", function(){
		$('#input_modal .modal-header #label').text("Change Location");
		$('#input_modal .modal-body').html('<input class="input-xlarge" id="new_location" type="text" placeholder="New Location...">');
		$('#input_modal .modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button id="confirm_change_loc" class="btn btn-success">Change</button>');
		$('#input_modal').modal('show');
		
		$("#confirm_change_loc").on("click", function(){
			$('#input_modal').modal('hide');
			$.ajax({
				type: "GET",
				url: 'change_loc.php',
				data: {loc: $('#new_location').val() },
				success: function(data) {
					$('#top-alert').html(data);
					$('#top-alert').fadeIn('slow');
					$("#student_info").load("index.php #student_info");
				}
			});
			return false;
		});
	});
});

$(document).ready(function(){
	$("body").on("click", "#change_help", function(){
		$('#input_modal .modal-header #label').text("Change Help");
		$('#input_modal .modal-body').html('<input class="input-xlarge" id="new_help" type="text" placeholder="New Help...">');
		$('#input_modal .modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button id="confirm_change_help" class="btn btn-success">Change</button>');
		$('#input_modal').modal('show');
		
		$("#confirm_change_help").on("click", function(){
			$('#input_modal').modal('hide');
			$.ajax({
				type: "GET",
				url: 'change_help.php',
				data: {help: $('#new_help').val() },
				success: function(data) {
					$('#top-alert').html(data);
					$('#top-alert').fadeIn('slow');
					$("#student_info").load("index.php #student_info");
				}
			});
			return false;
		});	
	});
});

$(document).ready(function(){
	$("#ta_clear").on("click", function(){
		$('#confirm_modal .modal-header #label').text("Clear Queue?");
		$('#confirm_modal .modal-body').html("<p>Are you sure you want to clear the queue?</p>");
		$('#confirm_modal .modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button class="btn btn-danger" id="confirm_ta_clear">Yes</button>');
		$('#confirm_modal').modal('show');
		
		$("#confirm_ta_clear").on("click", function(){
			$('#confirm_modal').modal('hide');
			$.ajax({
				type: "GET",
				url: 'ta_clear.php',
				success: function(data){
					$('#top-alert').html(data);
					$('#top-alert').fadeIn('slow');
					$('#student_table').load('index.php #student_table');
					
				}
			});
			return false;
		});
	});
});

$(document).ready(function(){
	$("body").on("click", "#student_remove", function(){
		$('#confirm_modal .modal-header #label').text("Are You Sure?");
		$('#confirm_modal .modal-body').html('<p>Are you sure you want to leave the queue?</p>');
		$('#confirm_modal .modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button class="btn btn-danger" id="confirm_student_remove">Yes</button>');
		$('#confirm_modal').modal('show');
		
		$("#confirm_student_remove").on("click", function(){
			$('#confirm_modal').modal('hide');
			$.ajax({
				type: "GET",
				url: 'dequeue.php',
				success: function(data) {
					$('#top-alert').html(data);
					$('#top-alert').fadeIn('slow');
					$('#student_info').load('index.php #student_info');
				}
			});
			return false;
		});
	});
})

$(document).ready(function(){
	$("#student_table").on("click", "button.ta_remove", function(){
		var comp_id = $(this).attr("name");
		$('#confirm_modal').attr("name", comp_id);
		$('#confirm_modal .modal-header #label').text("Remove " + comp_id + "?");
		$('#confirm_modal .modal-body').html('<p>Are you sure you want to remove <strong>' + comp_id + '</strong> from the queue?</p>');
		$('#confirm_modal .modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button class="btn btn-danger" id="confirm_ta_remove">Yes</button>');
		$('#confirm_modal').modal('show');
		
		$("#confirm_ta_remove").on("click", function(){
			$('#confirm_modal').modal('hide');
			$.ajax({
				type: "GET",
				url: 'ta_remove.php',
				data: {id: $('#confirm_modal').attr("name")},
				success: function(data) {
					$('#top-alert').html(data);
					$('#top-alert').fadeIn('slow');
					$('#student_table').load('index.php #student_table');
					//add title update
				}
			});
			return false;
		});
	});
});

$(document).ready(function(){
	$("#toggle_btn").on("click", function(){
		$('#confirm_modal .modal-header #label').text("Toggle Queue");
		$('#confirm_modal .modal-body').html("<p>Are you sure you want to toggle the queue?</p>");
		$('#confirm_modal .modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button class="btn btn-danger" id="confirm_toggle">Yes</button>');
		$('#confirm_modal').modal('show');
		
		$("#confirm_toggle").on("click", function(){
			$('#confirm_modal').modal('hide');
			$.ajax({
				url: 'toggle_queue.php',
				success: function(response) {
					if(response == "on"){
						$('#toggle_btn').text('Turn OFF Queue');
					}else if(response == "off"){
						$('#toggle_btn').text('Turn ON Queue');
					}
					$("#student_table").load("index.php #student_table");
				}
			});
			return false;
		});
	});
});

var infoInterval = '';
$(document).ready(function(){
	setInterval(function(){
		$('#student_table').load('index.php #student_table');
		$.ajax({
			type: "GET",
			url: 'queue_count.php',
			success: function(data) { 	
				document.title = data + "Office Hours"; 
			}
		});
	},15000);
	infoInterval = setInterval(function(){
		$('#student_info').load('index.php #student_info');
	},60000);
});

$(document).ready(function(){
	$("#top-alert").on("click", "#close_alert", function(){
		$('#top-alert').fadeOut('slow');	
	});
});

$(document).ready(function(){
  $(function() {
    $( '#ldapqry' ).change(function(){
      $.ajax({
        type: 'POST',
        url: 'uvaldap.php',
        data: {param: $('#ldapqry').val()},
        success: function(data) {
          $( '#ldapresult' ).html(data);
        }
      });
      $.ajax({
      	type: 'POST',
      	url: 'student_activity_result.php',
      	data: {param: $('#ldapqry').val()},
      	success: function(data) {
      		$( '#student_activity_result' ).html(data);
      	}
      });
    });
  });
});



