<?php

namespace App\Http\Controllers;

use App\Models\Rider;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RiderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $riders = Rider::all();
        return view('admin.riders.dashboard', compact('riders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.riders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'profile_pic' => 'required|file|mimes:jpeg,jpg,png,pdf|max:2048',
            'dob' => 'required|date',
            'email' => 'required|email|unique:riders,email',
            'license_no' => 'required|string|unique:riders,license_no|max:20',
            'license_front' => 'required|file|mimes:jpeg,png,pdf|max:2048',
            'national_id' => 'required|file|mimes:jpeg,jpg,png,pdf|max:2048',
        ]);

        //Handle Profile Pic
        if ($request->hasFile('profile_pic') && $request->file('profile_pic')->isValid()) {
            $profile_pic = $request->file('profile_pic');
            $profile_pic_name = Str::uuid().'.'.$profile_pic->getClientOriginalExtension();

            //Store the file in profile directory
            $profile_pic_path = $profile_pic->storeAs('rider/profile', $profile_pic_name, 'public');
        }

        //Handle License Front Image
        if ($request->hasFile('license_front') && $request->file('license_front')->isValid()) {
            $license_front = $request->file('license_front');
            $license_front_name = Str::uuid().'.'.$license_front->getClientOriginalExtension();

            //Store the file in license_front directory
            $license_front_path = $license_front->storeAs('rider/license_front', $license_front_name, 'public');
        }

        //Handle National Id Image
        if ($request->hasFile('national_id') && $request->file('national_id')->isValid()) {
            $national_id = $request->file('national_id');
            $national_id_name = Str::uuid().'.'.$national_id->getClientOriginalExtension();

            //Store the file in national_id directory
            $national_id_path = $national_id->storeAs('rider/national_id', $national_id_name, 'public');
        }

        //Create New Rider
        $rider = Rider::create([
            'firstname' => $validatedData['firstname'],
            'lastname' => $validatedData['lastname'],
            'profile_pic' => $profile_pic_path ?? null,
            'dob' => $validatedData['dob'],
            'email' => $validatedData['email'],
            'license_no' => $validatedData['license_no'],
            'license_front' => $license_front_path ?? null,
            'national_id' => $national_id_path ?? null,
        ]);

        if ($rider) {
            return redirect()->route('riders.index')->with('success', 'Rider created successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to Create Rider');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Rider $rider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rider $rider)
    {
        return view('admin.riders.edit', compact('rider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rider $rider)
    {
        $validatedData = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'profile_pic' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
            'dob' => 'required|date',
            'email' => ['required','email','max:255',Rule::unique('riders')->ignore($rider->id)],
            'license_no' => ['required','string','max:20',Rule::unique('riders')->ignore($rider->id)],
            'license_front' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'national_id' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
        ]);

        //Handle Profile Pic
        if ($request->hasFile('profile_pic') && $request->file('profile_pic')->isValid()) {
            Storage::delete('public/'.$rider->profile_pic);
            $profile_pic = $request->file('profile_pic');
            $profile_pic_name = Str::uuid().'.'.$profile_pic->getClientOriginalExtension();

            //Store the file in profile directory
            $profile_pic_path = $profile_pic->storeAs('rider/profile', $profile_pic_name, 'public');
        }

        //Handle License Front Image
        if ($request->hasFile('license_front') && $request->file('license_front')->isValid()) {
            Storage::delete('public/'.$rider->license_front);
            $license_front = $request->file('license_front');
            $license_front_name = Str::uuid().'.'.$license_front->getClientOriginalExtension();

            //Store the file in license_front directory
            $license_front_path = $license_front->storeAs('rider/license_front', $license_front_name, 'public');
        }

        //Handle National Id Image
        if ($request->hasFile('national_id') && $request->file('national_id')->isValid()) {
            Storage::delete('public/'.$rider->national_id);
            $national_id = $request->file('national_id');
            $national_id_name = Str::uuid().'.'.$national_id->getClientOriginalExtension();

            //Store the file in national_id directory
            $national_id_path = $national_id->storeAs('rider/national_id', $national_id_name, 'public');
        }

        //Update New Rider
        $rider->firstname = $validatedData['firstname'];
        $rider->lastname = $validatedData['lastname'];
        //Check Profile Pic
        if (isset($profile_pic_path)) {
            $rider->profile_pic = $profile_pic_path;
        }
        $rider->dob = $validatedData['dob'];
        $rider->email = $validatedData['email'];
        $rider->license_no = $validatedData['license_no'];
        //Check License Front Image
        if (isset($license_front_path)) {
            $rider->license_front = $license_front_path;
        }
        //Check National Id
        if (isset($national_id_path)) {
            $rider->national_id = $national_id_path;
        }
        $rider->save();

        if ($rider) {
            return redirect()->route('riders.index')->with('success', 'Rider updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to Update Rider');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rider $rider)
    {
        $filesToDelete = [
            $rider->profile_pic,
            $rider->license_front,
            $rider->national_id,
        ];
        foreach ($filesToDelete as $file) {
            Storage::delete('public/'.$file);
        }

        $rider->delete();

        return redirect()->route('riders.index')->with('success', 'Rider deleted successfully');

    }
}
