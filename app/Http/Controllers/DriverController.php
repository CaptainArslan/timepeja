<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use PDF;
use App\Models\Driver;
use App\Models\Schedule;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Driver\DriverStoreRequest;
use App\Http\Requests\Driver\DriverUpdateRequest;
use App\Http\Requests\Driver\DriverMultiDeleteRequest;


class DriverController extends Controller
{
    /**
     * Index function
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $organizations = Organization::where('status', Organization::STATUS_ACTIVE)->get();
        $drivers = Driver::with(['organization' => function ($query) {
            $query->select('id', 'name', 'email');
        }])
            ->latest()
            ->take(10)
            ->orderBy('id', 'DESC')
            ->get();
        if ($request->isMethod('post')) {
            if ($request->has('filter')) {
                $drivers = $this->filter($request);
            }
        }
        return view('driver.index', [
            'drivers' => $drivers,
            'organizations' => $organizations,
        ]);
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
            'o_id' => ['nullable', 'numeric', 'exists:organizations,id'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after:from'],
        ], [
            'o_id.exists' => "The selected organization doesn't exist.",
            'from.date' => "The registration from date must be a valid date.",
            'to.date' => "The registration to date must be a valid date.",
            'to.after' => "The registration to date must be a date after registration from.",
        ]);
        // Get the input values from the request
        $o_id = $request->input('o_id');
        $from = $request->input('from');
        $to = $request->input('to');

        // Get the filtered records
        $records = Driver::when($o_id, function ($query, $o_id) {
            return $query->where('o_id', $o_id);
        })
            ->when($from && $to, function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from, $to]);
            })
            ->when($from && !$to, function ($query) use ($from) {
                return $query->whereDate('created_at', '>=', $from);
            })
            ->when($to && !$from, function ($query) use ($to) {
                return $query->whereDate('created_at', '<=', $to);
            })
            ->with('organization', function ($query) {
                $query->select('id', 'name', 'branch_name', 'branch_code', 'email', 'phone', 'address', 'code'); // Select the id and name columns from the organizations table
            })
            ->get();

        // Return the filtered records to the view
        return $records;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $organizations = Organization::where('status', Organization::STATUS_ACTIVE)->get();
        $drivers = Driver::with('organizations')
            ->latest()
            ->take(10)
            ->orderBy('id', 'DESC')
            ->get();
        // dd($drivers->toArray());
        return view('driver.create', [
            'organizations' => $organizations,
            'drivers' => $drivers
        ]);
    }

    /**
     * Store request validation and store data in database by using form request
     *
     * @param DriverStoreRequest $request
     * @return void
     */
    public function store(DriverStoreRequest $request)
    {
        $user = Auth::user();
        $driver = new Driver();
        $driver->o_id = $request->input('o_id');
        $driver->u_id = $user->id;
        $driver->name = $request->input('name');
        $driver->phone = $request->input('phone');
        $driver->cnic = $request->input('cnic');
        $driver->license_no = $request->input('license_no');
        $driver->otp = rand(1000, 9999);
        $driver->status = $request->input('status');
        $driver->profile_picture = null;
        $driver->address = null;

        $driver->cnic_front_pic = ($request->file('cnic_front')) ?
            uploadImage($request->file('cnic_front'), 'drivers/cnic')
            : null;
        $driver->cnic_back_pic =  ($request->file('cnic_back')) ?
            uploadImage($request->file('cnic_back'), 'drivers/cnic') :
            null;

        $driver->license_no_front_pic =  ($request->file('license_front')) ?
            uploadImage($request->file('license_front'), 'drivers/license') :
            null;
        $driver->license_no_back_pic = ($request->file('license_back')) ?
            uploadImage($request->file('license_back'), 'drivers/license') :
            null;

        if ($driver->save()) {
            return redirect()->route('driver.index')
                ->with('success', 'Driver created successfully.');
        } else {
            return redirect()->route('driver.index')
                ->with('error', 'Error Occured while Driver creation .');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Driver $driver
     * @return Response
     */
    public function show(Driver $driver)
    {
        //
    }

    /**
     * function to update driver by using formrequest
     *
     * @param DriverUpdateRequest $request
     * @return RedirectResponse
     */
    public function edit(DriverUpdateRequest $request)
    {
        $driver = Driver::find($request->id);
        $user = Auth::user();
        $driver->o_id = $request->input('o_id');
        $driver->u_id = $user->id;
        $driver->name = $request->input('name');
        $driver->phone = $request->input('phone');
        $driver->cnic = $request->input('cnic');
        $driver->license_no = $request->input('license_no');
        $driver->status = $request->input('status');

        // ----- these function are to remove old picture from the folder
        // if ($request->hasFile('cnic_front')) {
        //     removeImage($driver->cnic_front_pic_name, 'drivers/cnic');
        // }
        //
        // if ($request->hasFile('cnic_back')) {
        //     removeImage($driver->cnic_back_pic_name, 'drivers/cnic');
        // }
        //
        // if ($request->hasFile('license_front')) {
        //     removeImage($driver->license_no_front_pic_name, 'drivers/license');
        // }
        //
        // if ($request->hasFile('license_back')) {
        //     removeImage($driver->license_no_back_pic_name, 'drivers/license');
        // }

        //  Updating image here if user add new it will update the image otherwise same image
        $driver->cnic_front_pic = ($request->file('cnic_front')) ?
            uploadImage($request->file('cnic_front'), 'drivers/cnic')
            : $driver->cnic_front_pic_name;
        $driver->cnic_back_pic =  ($request->file('cnic_back')) ?
            uploadImage($request->file('cnic_back'), 'drivers/cnic') :
            $driver->cnic_back_pic_name;

        $driver->license_no_front_pic =  ($request->file('license_front')) ?
            uploadImage($request->file('license_front'), 'drivers/license') :
            $driver->license_no_front_pic_name;
        $driver->license_no_back_pic = ($request->file('license_back')) ?
            uploadImage($request->file('license_back'), 'drivers/license') :
            $driver->license_no_back_pic_name;

        if($driver->device_token){
            notification('Profile updated', 'Your manager update your profile', $driver->device_token);
        }

        if ($driver->save()) {
            return redirect()->route('driver.index')
                ->with('success', 'Driver updated successfully.');
        } else {
            return redirect()->route('driver.index')
                ->with('error', 'Error occured while driver updation .');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Driver $driver
     * @return Response
     */
    public function update(Request $request, Driver $driver)
    {
        dd($request->all());
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $driver = Driver::findOrFail($id);
            if ($driver->device_token) {
                notification('Profile deleted', `Dear ${$driver->name}! Your manager delete your profile`, $driver->device_token);
            }
            $driver->delete();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * function to delete multiple drivers
     *
     * @param DriverMultiDeleteRequest $request
     * @return void
     */
    public function multiDelete(DriverMultiDeleteRequest $request)
    {
        try {
            // 2 is the number of queries to be executed
            DB::transaction(function () use ($request) {
                foreach ($request->driver_ids as $driver_id) {
                    $delete = Driver::where('id', $driver_id)->delete();
                    if (!$delete) {
                        throw new \Exception('Error deleting driver.');
                    }
                }
            }, 2);

            return redirect()->route('driver.index')
                ->with('success', 'Vehicles deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('driver.index')
                ->with('error', 'Error occured while Vehicle deletion.');
        }
    }

    /**
     * function to get all the drivers
     *
     * @return void
     */
    public function upcomingTrips(Request $request)
    {
        $trips = [];
        if ($request->isMethod('post')) {
            if ($request->has('filter')) {
                $trips = $this->filterUpcomingTrips($request);
            }
        }
        $organizations = Organization::where('status', Organization::STATUS_ACTIVE)->get();
        return view('driver.trips', [
            'organizations' => $organizations,
            'trips' => $trips
        ]);
    }

    /**
     * function to filter upcoming trips
     *
     * @param Request $request
     * @return void
     */
    public function filterUpcomingTrips($request)
    {
        $request->validate([
            'o_id' => ['required', 'numeric', 'exists:organizations,id'],
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after_or_equal:from'],
        ], [
            'o_id.required' => 'Organization required',
            'from.required' => 'Start date required',
            'from.date' => 'Start date must be a date',
            'to.required' => 'End date required',
            'to.date' => 'End date must be a date',
            'to.after_or_equal' => 'End date must be after or equal to start date',
        ]);

        // Get the input values from the request
        $o_id = $request->input('o_id');
        $from = $request->input('from');
        $to = $request->input('to');
        $driver = $request->input('driver');

        $trips = Schedule::when($o_id, function ($query, $o_id) {
            return $query->where('o_id', $o_id);
        })
            ->when($from && $to, function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from, $to]);
            })
            ->when($from && !$to, function ($query) use ($from) {
                return $query->whereDate('created_at', '>=', $from);
            })
            ->when($to && !$from, function ($query) use ($to) {
                return $query->whereDate('created_at', '<=', $to);
            })
            ->when($driver, function ($query, $driver) {
                return $query->where('d_id', $driver);
            })
            ->with('organizations', function ($query) {
                $query->select('id', 'name'); // Select the id and name columns from the organizations table
            })
            ->with('routes:id,name,number,from,to')
            ->with('vehicles:id,number')
            ->with('drivers:id,name')
            ->get();
        return $trips;
    }


    /**
     * Print PDF for to print the driver
     *
     * @param Request $request
     * @return void
     */
    public function printPdf(Request $request)
    {
        if (empty($request->o_id) && empty($request->from) && empty($request->to)) {
            $drivers = Driver::with('organization:id,name,branch_name,branch_code,email,phone,address,code')
                ->latest()
                ->take(10)
                ->get();
        } else {
            $drivers = $this->filter($request);
        }

        $data = [
            'drivers' => $drivers->toArray(),
            'request' => $request->all()
        ];

        $pdf = PDF::loadview('driver.export.driverpdf', $data);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download(time() . 'Log_Report.pdf');
    }
}
