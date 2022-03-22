@extends('customer::customer.layout.master')
 
@section('content')
<div class="container-fluid">
    <div class="row" style="margin: 2rem 0 1rem 0;">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Customers</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('customers.create') }}"> Create New Customer</a>
            </div>
        </div>
    </div>
    <div class="row">
    <div class="col-sm-12 p-0">
    <form action="{{ route('customers.search') }}" class="form-inline" method="POST">
    {{ csrf_field() }}
        <div class="col-sm-3 col-md-3">
            <input type="text" class="typeahead form-control" id="filter" name="filter" placeholder="Employee name..." value="{{$filter}}">
        </div>
        <div class="col-sm-2 col-md-2">
            <select id="statusFilter" class="form-control" name="status">
                <option value="" {{ ($status == "") ? 'selected' : ''}}>Show All</option>
                <option value="1" {{ ($status == '1') ? 'selected' : ''}}>Active</option>
                <option value="0" {{ ($status == '0') ? 'selected' : ''}}>In Active</option>
            </select>
        </div>
        <div class="col-sm-5 col-md-5">
            <select class="displayOrHideColumns form-control" name="selectedColumns[]" multiple>
                <option value="id" {{ in_array('id', $selectedColumns) ? 'selected' : ''}} class="id">ID</option>
                <option value="firstname" {{ in_array('firstname', $selectedColumns) ? 'selected' : ''}} class="first_name">First Name</option>
                <option value="lastname"  {{ in_array('lastname', $selectedColumns) ? 'selected' : ''}} class="last_name">Last Name</option>
                <option value="email" {{ in_array('email', $selectedColumns) ? 'selected' : ''}} class="email">Email</option>
                <option value="dob" {{ in_array('dob', $selectedColumns) ? 'selected' : ''}} class="dob">DOB</option>
                <option value="status" {{ in_array('status', $selectedColumns) ? 'selected' : ''}} class="status">Status</option>
                <option value="address" {{ in_array('address', $selectedColumns) ? 'selected' : ''}} class="address">Address</option>
                <option value="gender" {{ in_array('gender', $selectedColumns) ? 'selected' : ''}} class="gender">Gender</option>
                <option value="devices" {{ in_array('devices', $selectedColumns) ? 'selected' : ''}} class="devices">Devices</option>
            </select>
        </div>
        <div class="col-sm-2 col-md-2">
            <button type="submit" class="btn btn-primary">Apply</button>
            <button type="reset" id="reset" class="btn btn-primary">Reset</button>
        </div>  
    </form>
    </div>
    </div>
    <div class="row mb-2">
        <div class="col-sm-12 p-0">
            <div class="col-sm-3 col-md-3 pull-left">
                <select class="form-control" name="bulk-select" id="bulk-select">
                    <option value="update">Status Update</option>
                    <option value="delete">Delete</option>            
                </select>
            </div>
            <div class="col-sm-2 col-md-2 pull-left">
                <a href="javascript:void(0)" id="change_status" class="btn btn-primary">
                        Apply
                </a>
            </div>
            <div class="col-sm-5 col-md-5 pull-left">
                <select id="export-select" class="form-control" name="export-select">
                    <option value="" >Select Export Format</option>
                    <option value="xlsx">XLSX</option>
                    <option value="xls">XLS</option>
                    <option value="csv">CSV</option>
                    <option value="pdf">PDF</option>
                </select>
            </div>
            <div class="col-sm-2 col-md-2 pull-left">
            <a href="javascript:void(0)" id="export-data" class="btn btn-primary">Export</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
   
    <table class="table table-bordered" id="orders-table">
        <thead>
        <tr>
            <th><input type="checkbox" id="selectAll" /></th>
            <th class="id">@sortablelink('id','#')</th>            
            <th class="first_name">@sortablelink('firstname','First Name')</th>
            <th class="last_name">@sortablelink('lastname','Last Name')</th>
            <th class="email">@sortablelink('email','Email')</th>
            <th class="dob">@sortablelink('dob','DOB')</th>
            <th class="address">@sortablelink('address','Address')</th>
            <th class="gender">Gender</th>
            <th class="devices">Devices</th>
            <!-- <th>Category</th> -->
            <th class="status">@sortablelink('status','Status')</th>
            <th>Addons</th>
            <th width="280px">Action</th>
        </tr>
        </thead>
        
        <tbody>
        @foreach ($data as $key => $value)
        <tr>
            <td><input type="checkbox" class="tbl_chk_boxed" name="row_ids" id="row_ids" value="{{ $value->id}}" /></td>
            <td class="id">{{ $value->id }}</td>
            <td class="first_name">{{ $value->firstname }}</td>
            <td class="last_name">{{ $value->lastname }}</td>
            <td class="email">{{ $value->email }}</td>
            <td class="dob">{{ $value->dob }}</td>
            <td class="address">{{ $value->address }}</td>
            <!-- <td><img src="{{ asset('images/'.$value->order_image) }}" height="100px" width="100px"/></td> -->
            <td class="gender">{{ $value->gender}}</td>
            <td class="devices">{{ $value->devices }}</td>
            <td class="status">{{ $value->status == 1 ? 'Active' : 'In active' }}</td>
            <td class="">{{-- count($value->addMoreInputFields) --}}</td>
            <td>
                <form action="{{ route('customers.destroy',$value->id) }}" method="POST">   
                    <a class="btn btn-info" href="{{ route('customers.show',$value->id) }}">Show</a>    
                    <a class="btn btn-primary" href="{{ route('customers.edit',$value->id) }}">Edit</a>
                    <a class="btn btn-primary updateStatus" href="javascript:void(0)" data-id="{{ $value->id}}" data-status="{{ $value->status }}"> {{ $value->status == 1 ? 'Active' : 'In active' }}</a>      
                    @csrf
                    @method('DELETE')      
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure to delete?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <div class="row">
    <div class="col-sm-12 col-md-5">
    Displaying {{$data->count()}} of {{ $data->total() }} customer(s).
</div>
<div class="col-sm-12 col-md-7">{{ $data->links() }} </div>
</div>
</div>

         
@endsection
   
