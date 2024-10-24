<?php

namespace App\Http\Controllers;

use App\Http\Requests\PassengerStoreRequest;
use App\Http\Requests\PassengerUpdateRequest;
use App\Models\Organization;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PassengerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $passengers = Passenger::orderBy('id', 'DESC')
            ->take(10)
            ->get();
        if ($request->has('filter')) {
            $passengers = $this->filter($request);
        }
        return view('passenger.index', [
            'passengers' => $passengers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PassengerStoreRequest $request)
    {
        try {
            $otp = rand(1000, 9999);
            $password = Hash::make($request->password);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = uploadImage($image, 'passengers/profile', 'passenger_profile');
            }

            Passenger::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => $password,
                'image' => $imagePath,
                'unique_id' => substr(uniqid(), -8),
                'gaurd_code' => substr(uniqid(), -8)
                // 'otp' => $otp,
                // Add other fields from the request as needed
            ]);

            return redirect()->route('passenger.index')
                ->with('success', 'Passenger created successfully');
        } catch (\Throwable $th) {
            return redirect()->route('passenger.index')
                ->with('error', 'Error occurred while creating passenger');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PassengerUpdateRequest $request, $id)
    {
        $passenger = Passenger::findOrFail($request->id);
        // ----- these function are to remove old picture from the folder
        if ($request->hasFile('image')) {
            removeImage($passenger->image_name, 'passengers/profile');
        }

        $passenger->image = ($request->file('image')) ?
            uploadImage($request->file('image'), 'passengers/profile')
            : $passenger->image_name;
        $passenger->name = $request->name;
        $passenger->email = $request->email;
        $passenger->phone = $request->phone;
        $passenger->save();
        try {
            return redirect()->route('passenger.index')
                ->with('success', 'Passenger updated successfully');
        } catch (\Throwable $th) {
            return redirect()->route('passenger.index')
                ->with('error', 'Error occurred while updating passenger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $passenger = Passenger::findOrFail($id);
            $passenger->delete();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Function to filter records
     *
     * @param Request $request
     * @return void
     */
    public function filter($request)
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to' => [
                'nullable',
                'date',
                // 'after:from'
            ],
        ], [
            'from.date' => "The registration from date must be a valid date.",
            'to.date' => "The registration to date must be a valid date.",
            'to.after' => "The registration to date must be a date after registration from.",
        ]);
        // Get the input values from the request
        $from = $request->input('from');
        $to = $request->input('to');

        // Get the filtered records
        $records = Passenger::when($from && $to, function ($query) use ($from, $to) {
            return $query->whereBetween('created_at', [$from, $to]);
        })
            ->when($from && !$to, function ($query) use ($from) {
                return $query->whereDate('created_at', '>=', $from);
            })
            ->when($to && !$from, function ($query) use ($to) {
                return $query->whereDate('created_at', '<=', $to);
            })
            ->get();

        // Return the filtered records to the view
        return $records;
    }
}
