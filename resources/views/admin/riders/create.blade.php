@extends('adminlte::page')

@section('title', 'Create Rider')

@section('content_header')
<h1>Create Rider</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('riders.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
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
                                value="{{old('firstname')}}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lastname">Last Name</label>
                            <input type="text" class="form-control" id="lastname" name="lastname"
                                value="{{old('lastname')}}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="profile_pic">Profile Pic</label>
                            <input type="file" class="form-control" id="profile_pic" name="profile_pic"
                                value="{{old('profile_pic')}}" onchange="getImagePreview(event,'profile_images_view')"
                                required>
                            <div id="profile_images_view">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="dob">DOB</label>
                            <input type="date" class="form-control" id="dob" name="dob" value="{{old('dob')}}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" name="email" value="{{old('email')}}"
                                required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="license_no">License No</label>
                            <input type="text" class="form-control" id="license_no" name="license_no"
                                value="{{old('license_no')}}" required>

                        </div>
                        <div class="form-group col-md-6">
                            <label for="license_front">License Front Image</label>
                            <input type="file" class="form-control" id="license_front" name="license_front"
                                value="{{old('license_front')}}"
                                onchange="getImagePreview(event,'license_front_images_view')" required>
                            <div id="license_front_images_view">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="national_id">Nationa ID</label>
                            <input type="file" class="form-control" id="national_id" name="national_id"
                                value="{{old('national_id')}}"
                                onchange="getImagePreview(event,'national_id_images_view')" required>
                            <div id="national_id_images_view">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Rider</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function getImagePreview(event,divId){
        var image = URL.createObjectURL(event.target.files[0]);
        var imageDiv = document.getElementById(divId);
        var imagetag = document.createElement('img');
        
        imagetag.src = image;
        imagetag.width = "150";
        imagetag.style.padding = "5px";
        imageDiv.appendChild(imagetag);
    }
</script>
@stop