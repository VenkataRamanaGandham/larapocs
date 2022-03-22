@extends('customer::customer.layout.master')
   
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Order</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('customers.index') }}"> Back</a>
            </div>
        </div>
    </div>
   
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
  
    <form action="{{ route('customers.update',$customer->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
     <div class="form-group">
     <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>First Name:</strong>
                <input type="text" name="firstname" class="form-control" value="{{ $customer->firstname }}">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Last Name:</strong>
                <input type="text" name="lastname" class="form-control" value="{{ $customer->lastname }}">
            </div>
        </div> 
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Phone Number:</strong>
                <input type="text" name="phone_number" class="form-control" value="{{ $customer->phone_number }}">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Email:</strong>
                <input type="email" name="email" class="form-control" value="{{ $customer->email }}">
            </div>
        </div>
     <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <strong>DOB:</strong>
                <input type="date" name="dob" class="form-control" value="{{ $customer->dob }}">
            </div>
        </div>
        
        
        
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Address:</strong>
                <textarea name="address" class="form-control">{{ $customer->address }}</textarea>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Gender:</strong>
                <input type="radio" name="gender" value="male" {{ $customer->gender == 'male' ? 'selected' : ''}}>Male
                <input type="radio" name="gender" value="female" {{ $customer->gender == 'female' ? 'selected' : ''}}>Female
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Devices:</strong>
                <select class="devices" name="devices[]" multiple>
                <option value="mobile" {{ in_array('mobile', $customer->devices) ? 'selected' : ''}}>Mobile</option>
                <option value="tablet" {{ in_array('tablet', $customer->devices) ? 'selected' : ''}}>Tables</option>
                <option value="laptop" {{ in_array('laptop', $customer->devices) ? 'selected' : ''}}>Laptop</option>
                <option value="smartband" {{ in_array('smartband', $customer->devices) ? 'selected' : ''}}>Smartband</option>
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Status:</strong>
                <select class="devices" name="status">
                <option>Select Status</option>
                <option value="1" {{ ($customer->status == 1) ? 'selected' : ''}}>Active</option>
                <option value="0" {{ ($customer->status == 0) ? 'selected' : ''}}>In Active</option>                
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Subscription:</strong>
                <input type="checkbox" name="subscription" value="1" {{ !empty($customer->subscription) ? 'checked' : ''}}>Subscribe                
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Image:</strong>
                <input type="file" name="cusomer_image" class="form-control" value="{{ $customer->customer_image }}">
            </div>            
            @if ($customer->customer_image != "")
                <img src="{{ asset('customer_images/'.$customer->customer_image) }}" width="100px" height="100px">
            @else
                    <p>No image found</p>
            @endif
        </div>
        
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Product Images:</strong>
                <input type="file" name="product_images[]" id="product_images" class="form-control" multiple>
                      
            @if (isset($customer->product_images) &&  $customer->product_images != "")
            @foreach($customer->product_images as $product_image)
                <img src="{{ asset('images/product_images/'.$product_image) }}" width="100px" height="100px">
            @endforeach
            @else
                    <p>No image found</p>
            @endif
            </div> 
        </div>
        <table class="table table-bordered" id="dynamicAddRemove">
                <tr>
                    <th>Addons</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                @foreach($customer->addMoreInputFields as $key => $addMoreInputField)
                @if($key == 0)
                <tr>
                    <td><input type="text" name="addMoreInputFields[{{$key}}][name]" value="{{ $addMoreInputField->name }}" placeholder="Enter Addon Name" class="form-control" />
                    </td>
                    <td><textarea name="addMoreInputFields[{{$key}}][description]" class="form-control">{{ $addMoreInputField->description }}</textarea>
                    </td>
                    <td><button type="button" name="add" id="dynamic-ar" class="btn btn-outline-primary">Add</button></td>
                </tr>
                @else
                <tr>
                    <td><input type="text" name="addMoreInputFields[{{$key}}][name]" value="{{ $addMoreInputField->name }}" placeholder="Enter Addon Name" class="form-control" />
                    </td>
                    <td><textarea name="addMoreInputFields[{{$key}}][description]" class="form-control">{{ $addMoreInputField->description }}</textarea>
                    </td>
                    <td><button type="button" name="add" id="dynamic-ar" class="btn btn-outline-danger remove-input-field">Delete</button></td>
                </tr>
                @endif
                @endforeach
            </table>
            <div class="col-xs-12 col-sm-12 col-md-12 increment">
            <div class="input-group control-group" >
            <strong>Dynamic Images:</strong>
                <input type="file" name="filename[]" class="form-control">
            <div class="input-group-btn"> 
                <button class="btn btn-success" type="button"><i class="glyphicon glyphicon-plus"></i>Add</button>
            </div>
            </div>
            @if (isset($customer->filename) &&  $customer->filename != "")
            @foreach($customer->filename as $filename)
                <img src="{{ asset('images/'.$filename) }}" width="100px" height="100px">
            @endforeach
            @else
                    <p>No Images found</p>
            @endif
        </div>  
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
   
</form>
</div>
<script type="text/javascript">
    var i = {{ count($customer->addMoreInputFields)-1 }};
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
    $(document).ready(function () {
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
         $(".increment").append(moreImage);
      });

      $("body").on("click",".btn-danger",function(){ 
          $(this).parents(".control-group").remove();
      });

    });    
</script>
@endsection