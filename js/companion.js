function comp_action(comp_id,comp_action)
{
	swal({
             title: "Are you sure? you want to "+comp_action+" the companion ?",
             //text: "Once deleted, you will not be able to recover this imaginary file!",
             icon: "warning",
             buttons: true,
             dangerMode: true,
           })
          .then((action) => {
				if (action) 
				{ 
				 
					$.ajax({
						url: configUrl+'comp_update_action.php',
						type: 'POST',
						data: { 'comp_id' : comp_id, 'comp_action' : comp_action },
						success: function(response){
							//alert(response);
						  var data = response.split("||");
							if(data[0] != 0){
								swal(data[1]);
								swal({
									title: "Success!",
									text: data[1],
									type: "success"
								}).then(function() {
									window.location.href = window.location.href;
								});
								
							}else{
								swal(data[1]);
							}
						},
					});
				}
		});
}
