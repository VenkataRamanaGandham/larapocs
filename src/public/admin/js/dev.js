   var firstname_err_msg = (firstname_err_msg != "") ? firstname_err_msg : "";
   var lastname_err_msg = (lastname_err_msg != "") ? lastname_err_msg : "";
   var email_req_err_msg = (email_req_err_msg != "") ? email_req_err_msg : "";
   var email_format_err_msg = (email_format_err_msg != "") ? email_format_err_msg : "";
   var phone_req_err_msg = (phone_req_err_msg != "") ? phone_req_err_msg : "";
   var digit_err_msg = (digit_err_msg != "") ? digit_err_msg : "";
   var min_length_err_msg = (min_length_err_msg != "") ? min_length_err_msg : "";
   var max_length_err_msg = (max_length_err_msg != "") ? max_length_err_msg : "";
   var i = 0;
    $("#dynamic-ar").click(function () {
        ++i;
        $("#dynamicAddRemove").append('<tr><td><input type="text" name="addMoreInputFields[' + i +
            '][name]" placeholder="Enter Addon Name" class="form-control" /></td><td><textarea name="addMoreInputFields[' + i +
            '][description]" class="form-control"></textarea></td><td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td></tr>'
            );
    });
    $(document).on('click', '.remove-input-field', function () {
        $(this).parents('tr').remove();
    });
    //$(document).ready(function () {
        $('#form').validate({ 
            rules: {
                firstname: {
                    required: true
                },
                lastname: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true
                },
                phone_number: {
                    required: true,
                    number: true,
		            minlength: 10,
		            maxlength: 10
                }
            },
            messages: {
                    firstname: firstname_err_msg,
                    lastname: lastname_err_msg,
                    email: {
                        required: email_req_err_msg,
                        email: email_format_err_msg
                    },
                    phone_number: {
                        required: phone_req_err_msg,
                        digit: digit_err_msg,
                        minlength: min_length_err_msg,
                        maxlength: max_length_err_msg
                    }                   
                },
            errorElement: 'span',
                errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        //Image upload preview and delete functionality
        if (window.File && window.FileList && window.FileReader) {
    $("#product_images").on("change", function(e) {
      var files = e.target.files,
        filesLength = files.length;
      for (var i = 0; i < filesLength; i++) {
        var f = files[i]
        var fileReader = new FileReader();
        fileReader.onload = (function(e) {
          var file = e.target;
          $("<span class=\"pip\">" +
            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
            "<br/><span class=\"remove\">Remove image</span>" +
            "</span>").insertAfter("#product_images");
          $(".remove").click(function(){
            $(this).parent(".pip").remove();
          });
          
          // Old code here
          /*$("<img></img>", {
            class: "imageThumb",
            src: e.target.result,
            title: file.name + " | Click to remove"
          }).insertAfter("#files").click(function(){$(this).remove();});*/
          
        });
        fileReader.readAsDataURL(f);
      }
    });
  } else {
    alert("Your browser doesn't support to File API")
  }

  $(".btn-success").click(function(){ 
          var moreImage = '<div class="clone hide"><div class="control-group input-group" style="margin-top:10px"><input type="file" name="filename[]" class="form-control">'+
            '<div class="input-group-btn"><button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>'+
            '</div></div></div>';
         // var html = $(".clone").html();
          $(".increment").append(moreImage);
      });

        $("body").on("click",".btn-danger",function(){ 
            $(this).parents(".control-group").remove();
        });
        /*Dependent Fields code start */
        $('#country_id').on('change', function () {
            var idCountry = this.value;
            $("#state_id").html('');
            $.ajax({
                url: "/api/fetch-states",
                type: "POST",
                data: {
                    "country_id": idCountry,
                    "req_type": "state",
                    //_token: '{{csrf_token()}}'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (result) {
                    $('#state_id').html('<option value="">Select State</option>');
                    $.each(result.states, function (key, value) {
                        $("#state_id").append('<option value="' + value
                            .id + '">' + value.name + '</option>');
                    });
                    $('#city_id').html('<option value="">Select City</option>');
                }
            });
        });
        $('#state_id').on('change', function () {
            var idState = this.value;
            $("#city_id").html('');
            $.ajax({
                url: "/api/fetch-cities",
                type: "POST",
                data: {
                    state_id: idState,
                    "req_type": "city",
                   // _token: '{{csrf_token()}}'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (res) {
                    $('#city_id').html('<option value="">Select City</option>');
                    $.each(res.cities, function (key, value) {
                        $("#city_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        });
        /*Dependent Fields code end */
        /* Auto complete code start*/
        $( "#auto_city_id" ).autocomplete({
			source: function( request, response ) {
				$.ajax({
                    url: "/api/search-cities",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
					type: 'get',
					dataType: "json",
					data: {
						search: request.term
					},
					success: function( data ) {
						response( data );
					}
				});
			},
			select: function (event, ui) {
				$('#auto_city_id').val(ui.item.label); 
				$('#selectcity_id').val(ui.item.value);
				return false;
			}
		});
        /* Auto complete code end*/
        /*wysiwyg start */
        if ($('#description_2').length > 0) {
        CKEDITOR.replace('description_2')
        //bootstrap WYSIHTML5 - text editor
        $('.textarea').wysihtml5();
    }		
        /* wysieyg end */
    //});
//$(document).ready(function() {
multiselctColumns('displayOrHideColumns');
$('.displayOrHideColumns').select2({
    //placeholder: "Select Columns",
    //allowClear: true,
    closeOnSelect : false,
			placeholder : "Select Columns",
			allowHtml: true,
			allowClear: true,
			tags: false
});
$('#export-data').on('click', function(){
                var filterName = $('#filter').val();
                var statusFilter = $('#statusFilter').val();
                var columnFilter = $('.displayOrHideColumns').find('option:selected').map(function(){
                return (this).value;
               }).get();
               var extension = $('#export-select').val()
               var query = {'filterName' : filterName, 'statusFilter': statusFilter, 'columnFilter': columnFilter, 'extension': extension, '_token': $('meta[name="csrf-token"]').attr('content')};
               var url = "/customer_export?" + $.param(query)
                window.location = url;     
});
$('#reset').on('click', function(){
    $('#filter').val("");
    $('#statusFilter').val("");
    window.location = "/customers";
});

/*$('#export-select').on('change', function(){
                var filterName = $('#filter').val();
                var statusFilter = $('#statusFilter').val();
                var columnFilter = $('.displayOrHideColumns').find('option:selected').map(function(){
                return (this).value;
               }).get();
               var extension = $(this).val();                          
               var query = {'filterName' : filterName, 'statusFilter': statusFilter, 'columnFilter': columnFilter, 'extension': extension, '_token': $('meta[name="csrf-token"]').attr('content')};
               var url = "/customer_export?" + $.param(query)
                window.location = url;     
});*/
                   
$('.updateStatus').on('click', function(){
    if (confirm('Do you need to update the status?')) {
    var id = $(this).attr('data-id');
    var status = $(this).attr('data-status');
    $this = $(this);
    $.ajax({
        url:"/customer_status_update",
        type: "POST",
        dataType: 'html',
        data: { "_token": $('meta[name="csrf-token"]').attr('content'), 'customerId': id, 'customerStatus': status},
        success:function(resp) {
                       if(resp == '0') {
                        $this.attr('data-status', 0);
                        $this.text("In Active");
                        }else{
                        $this.attr('data-status', 1);
                        $this.text("Active");
                        }
        }
    });
    }
});

//});
function multiselctColumns(selectId) {
            var selectedValues = $('.'+selectId).find('option').map(function(){
                var column = "table ."+$(this).attr('class');
                $(column).show();
               });
               var unselectedValues = $('.displayOrHideColumns').find('option:not(:selected)').map(function(){
                if($(".displayOrHideColumns :selected").length !== 0) {
                var column = "table ."+$(this).attr('class');
                $(column).hide();
                }
               });
        }
        /*Checkboxes code start */
        $('#selectAll').click(function(e){
            var table= $(e.target).closest('table');
            $('td input:checkbox',table).prop('checked',this.checked);
        });
        $("#change_status").click(function(){
            var array = [];
            $(".tbl_chk_boxed:checked").each(function() {
                array.push($(this).val());
            });
            var bulk_action = $('#bulk-select').val();
            if(array.length > 0) {
                $.ajax({
                    url: "/api/update-status",
                    type: "POST",
                    data: {
                        "ids": array,
                        "action": bulk_action,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function (result) {
                       location.reload();
                    }
                });
            }
        })
        /* Checkboxes code end*/
