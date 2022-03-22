@extends('customer::customer.layout.master')
  
@section('content')
<div class="container-fluid">
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Add New Customer</h2>
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
   
<form action="{{ route('customers.store') }}" id="form" method="POST" enctype="multipart/form-data">
    @csrf
  
     <div class="form-group">
        <strong>Dependent Fields:</strong>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group mb-3">
                <strong>Country:</strong>
                <select  id="country_id" name="country_id" class="form-control">
                    <option value="">Select Country</option>
                    @foreach ($countries as $data)
                        <option value="{{$data->id}}">{{$data->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <strong>State:</strong>
                <select id="state_id" name="state_id" class="form-control">
                </select>
            </div>
            <div class="form-group">
                <strong>City:</strong>
                <select id="city_id" name="city_id" class="form-control">
                </select>
            </div>
        </div>
        <strong>AutoComplete :</strong>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>City Name:</strong>
                <input type="text" name="auto_city_id" id="auto_city_id" class="form-control" placeholder="Start Typing the city Name...">
                <input type="hidden" name="selectcity_id" id="selectcity_id" />
            </div>
        </div>
        <strong>WYSIWYG Editor :</strong>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">Description</h4>
                    </div>
                    <div class="box-body">
                        <textarea id="description_2" name="description" rows="10" cols="80"> This is my textarea to be replaced with CKEditor.</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>First Name:</strong>
                <input type="text" name="firstname" class="form-control" placeholder="Enter First Name">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Last Name:</strong>
                <input type="text" name="lastname" class="form-control" placeholder="Enter Last Name">
            </div>
        </div> 
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Phone Number:</strong>
                <input type="text" name="phone_number" class="form-control" placeholder="Enter Phone Number">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Email:</strong>
                <input type="email" name="email" class="form-control" placeholder="Enter Email">
            </div>
        </div>
     <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <strong>DOB:</strong>
                <input type="date" name="dob" class="form-control" placeholder="Enter DOB">
            </div>
        </div>
        
        
        
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Address:</strong>
                <textarea name="address" class="form-control"></textarea>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Gender:</strong>
                <input type="radio" name="gender" value="male">Male
                <input type="radio" name="gender" value="female">Female
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Devices:</strong>
                <select class="devices" name="devices[]" multiple>
                <option value="mobile">Mobile</option>
                <option value="tablet">Tables</option>
                <option value="laptop">Laptop</option>
                <option value="smartband">Smartband</option>
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Status:</strong>
                <select class="devices" name="status">
                <option>Select Status</option>
                <option value="1">Active</option>
                <option value="0">In Active</option>                
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Subscription:</strong>
                <input type="checkbox" name="subscription" value="1">Subscribe                
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Image:</strong>
                <input type="file" name="customer_image" class="form-control">
            </div>
        </div>
        
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Multiple Images:</strong>
                <input type="file" name="product_images[]" id="product_images" class="form-control" multiple>
            </div>
        </div>    
        <table class="table table-bordered" id="dynamicAddRemove">
                <tr>
                    <th>Addons</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                <tr>
                    <td><input type="text" name="addMoreInputFields[0][name]" placeholder="Enter Addon Name" class="form-control" />
                    </td>
                    <td><textarea name="addMoreInputFields[0][description]" class="form-control"></textarea>
                    </td>
                    <td><button type="button" name="add" id="dynamic-ar" class="btn btn-outline-primary">Add</button></td>
                </tr>
            </table>
        <div class="col-xs-12 col-sm-12 col-md-12 increment">
            <div class="form-group" >
            <strong>Dynamic Images:</strong>
                <input type="file" name="filename[]" class="form-control">
            <div class="input-group-btn"> 
                <button class="btn btn-success" type="button"><i class="glyphicon glyphicon-plus"></i>Add</button>
            </div>
            </div>
        </div>    
        
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
   
</form>
</div>
<script type="text/javascript">
var firstname_err_msg = "{{ config('customer.firstname') }}";
var lastname_err_msg = "{{ config('customer.lastname') }}";
var email_req_err_msg = "{{ config('customer.email_required') }}";
var email_format_err_msg = "{{ config('customer.email_format') }}";
var phone_req_err_msg = "{{ config('customer.phone_required') }}";
var digit_err_msg = "{{ config('customer.phone_digit') }}";
var min_length_err_msg = "{{ config('customer.phone_minlength') }}";
var max_length_err_msg = "{{ config('customer.phone_maxlength') }}";

</script>
@endsection
