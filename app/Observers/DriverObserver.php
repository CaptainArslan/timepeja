<?php

namespace App\Observers;

use Exception;
use App\Models\Driver;
use App\Models\Location;
use App\Models\LocationHistory;
use Illuminate\Support\Facades\DB;

class DriverObserver
{
    /**
     * Handle the Driver "created" event.
     *
     * @param  \App\Models\Driver  $driver
     * @return void
     */
    public function created(Driver $driver)
    {
        // Transactional Handling
        try {
            DB::beginTransaction();
            //Add Location to Location History
            LocationHistory::create([
                'd_id' => $driver->id,
                'latitude' => rand(),
                'longitude' => rand()
            ]);
            // If Created then commit to the Database
            DB::commit();
        } catch (Exception $e) {
            // If not commited then rollback
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Handle the Driver "updated" event.
     *
     * @param  \App\Models\Driver  $driver
     * @return void
     */
    public function updated(Driver $driver)
    {
        // Transaction Handling
        try {
            DB::beginTransaction();
            //Update Current Location
            $currentLocation = Location::where('d_id', $driver->id)->first();
            $currentLocation->update([
                'latitude' => rand(),
                'longitude' => rand()
            ]);
            // If satisfied then update the record in the datasbase after the actual record updation
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Handle the Driver "deleted" event.
     *
     * @param  \App\Models\Driver  $driver
     * @return void
     */
    public function deleted(Driver $driver)
    {
        // Handling the transaction/record
        try {
            DB::beginTransaction();
            // Delete the record
            Location::where('d_id', $driver->id)->delete();
            // if satisfied then commit this record to database
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Handle the Driver "restored" event.
     *
     * @param  \App\Models\Driver  $driver
     * @return void
     */
    public function restored(Driver $driver)
    {
        //
    }

    /**
     * Handle the Driver "force deleted" event.
     *
     * @param  \App\Models\Driver  $driver
     * @return void
     */
    public function forceDeleted(Driver $driver)
    {
        //
    }
}
