@extends('adminlte::page')
@section('title', 'Admin Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riders</h3>
                <div class="card-tools">
                    <a href="{{ route('riders.create') }}" class="btn btn-primary">Add New Riders</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Profile Pic</th>
                            <th>DOB</th>
                            <th>Email</th>
                            <th>License No</th>
                            <th>License Front Image</th>
                            <th>National Id</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riders as $key=> $rider)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $rider->firstname.' '.$rider->lastname }}</td>
                            <td style="text-align: center; vertical-align:middle">
                                @if($rider->profile_pic)
                                <img src="{{ url('storage/'.$rider->profile_pic) }}" alt="{{ $rider->firstname }}"
                                    style="width: 100px; height: 75px;"> <!-- Display image -->
                                @else
                                No Image
                                @endif
                            </td>
                            <td>{{ Carbon\Carbon::parse($rider->dob)->format('F d,Y') }}</td>
                            <td>{{$rider->email}}</td>
                            <td>{{$rider->license_no}}</td>
                            <td style="text-align: center; vertical-align:middle">
                                @if($rider->license_front)
                                <img src="{{ url('storage/'.$rider->license_front) }}" alt="{{ $rider->firstname }}"
                                    style="width: 100px; height: 75px;"> <!-- Display image -->
                                @else
                                No Image
                                @endif
                            </td>
                            <td style="text-align: center; vertical-align:middle">
                                @if($rider->license_front)
                                <img src="{{ url('storage/'.$rider->national_id) }}" alt="{{ $rider->firstname }}"
                                    style="width: 100px; height: 75px;"> <!-- Display image -->
                                @else
                                No Image
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('riders.edit', $rider) }}" class="btn btn-sm btn-info">Edit</a>
                                <form action="{{ route('riders.destroy', $rider) }}" method="POST"
                                    style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop