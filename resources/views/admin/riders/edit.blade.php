@extends('adminlte::page')

@section('title', 'Edit Rider')

@section('content_header')
<h1>Edit Rider</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('riders.update',$rider) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="firstname">First Name</label>
                            <input type="text" class="form-control" id="firstname" name="firstname"
                                value="{{old('firstname',$rider->firstname)}}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lastname">Last Name</label>
                            <input type="text" class="form-control" id="lastname" name="lastname"
                                value="{{old('lastname',$rider->lastname)}}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="profile_pic">Profile Pic</label>
                            <input type="file" class="form-control" id="profile_pic" name="profile_pic"
                                value="{{old('profile_pic',$rider->profile_pic)}}"
                                onchange="getImagePreview(event,'profile_images_view')">
                            @if($rider->profile_pic)
                            <img src="{{ url('storage/'.$rider->profile_pic) }}" id="profile_images_view"
                                alt="{{ $rider->firstname }}" style="width: 100px; height: 100px;padding:5px;">
                            <!-- Display image -->
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="dob">DOB</label>
                            <input type="date" class="form-control" id="dob" name="dob"
                                value="{{old('dob',$rider->dob)}}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" name="email"
                                value="{{old('email',$rider->email)}}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="license_no">License No</label>
                            <input type="text" class="form-control" id="license_no" name="license_no"
                                value="{{old('license_no',$rider->license_no)}}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="license_front">License Front Image</label>
                            <input type="file" class="form-control" id="license_front" name="license_front"
                                value="{{old('license_front',$rider->license_front)}}"
                                onchange="getImagePreview(event,'license_front_images_view')">
                            @if($rider->license_front)
                            <img src="{{ url('storage/'.$rider->license_front) }}" id="license_front_images_view"
                                alt="{{ $rider->firstname }}" style="width: 150px; height: auto; padding:5px;">
                            <!-- Display image -->
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="national_id">Nationa ID</label>
                            <input type="file" class="form-control" id="national_id" name="national_id"
                                value="{{old('national_id',$rider->national_id)}}"
                                onchange="getImagePreview(event,'national_id_images_view')">
                            @if($rider->national_id)
                            <img src="{{ url('storage/'.$rider->national_id) }}" id="national_id_images_view"
                                alt="{{ $rider->firstname }}" style="width: 150px; height: auto;padding:5px;">
                            <!-- Display image -->
                            @endif
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Rider</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function getImagePreview(event,divId){
        var image = URL.createObjectURL(event.target.files[0]);
        var imageDiv = document.getElementById(divId);

        imageDiv.src = image;
    }
</script>
@stop