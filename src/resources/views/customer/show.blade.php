@extends('customer::customer.layout.master')
  
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Customer Details</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('customers.index') }}"> Back</a>
            </div>
        </div>
    </div>
   
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>First Name:</strong>
                {{ $customer->firstname }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Last Name:</strong>
                {{ $customer->lastname }}
            </div>
        </div>
<div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Phone Number:</strong>
                {{ $customer->phone_number }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Email:</strong>
                {{ $customer->email }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>DOB:</strong>
                {{ $customer->dob }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Address:</strong>
                {{ $customer->address }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Gender:</strong>
                {{ $customer->gender }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Devices:</strong>
                {{ $customer->devices }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Status:</strong>
                {{ $customer->status == 1 ? 'Active' : 'In active' }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Customer Image:</strong>
                <img src="{{ asset('customer_images/'.$customer->customer_image) }}" height="100px" width="100px"/>
            </div>
        </div>        
        @if(isset($customer->product_images))
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Multiple Images:</strong>
                @foreach($customer->product_images as $product_image)
                <img src="{{ asset('images/product_images/'.$product_image) }}" height="100px" width="100px"/>
                @endforeach
            </div>
        </div>
        @endif
        <div class="col-xs-12 col-sm-12 col-md-12">
        @foreach($customer->addMoreInputFields as $addMoreInputField)
        <div class="form-group">
        Name: {{ $addMoreInputField->name }} ---
        Description: {{ $addMoreInputField->description }}
        </div>
        @endforeach
        </div>
    </div>
</div>    
@endsection
