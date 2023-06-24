<?php

namespace App\Observers;

use Exception;
use App\Models\Vehicle;
use App\Models\LocationHistory;
use illuminate\Support\Facades\DB;

class VehicleObserver
{
    /**
     * Handle the Vehicle "created" event.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return void
     */
    public function created(Vehicle $vehicle)
    {
        // Transactional Handling
        try{
            DB::beginTransaction();
            LocationHistory::create([
                'v_id' => $vehicle->id,
                'latitude' => rand(),
                'longitude' => rand()

            ]);
            // If created then commit 
            DB::commit();

        }catch (Exception $e)
        {
            // otherwise rollback
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Handle the Vehicle "updated" event.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return void
     */
    public function updated(Vehicle $vehicle)
    {
        //Transactional Handling 
        try{
            DB::beginTransaction();
            // Update the currentVehicle Record 
            $currentVehicle  = Vehicle::where('v_id',$vehicle->id)->first();
            $currentVehicle->update([
                'latitude' => rand(),
                'longitude' => rand()
            ]);
            DB::commit();

        }catch (Exception $e)
        {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Handle the Vehicle "deleted" event.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return void
     */
    public function deleted(Vehicle $vehicle)
    {
        //Transactional Handling
        try{
            DB::beginTransaction();
            // Deleting the record 
            Vehicle::where('d_id',$vehicle->id)->delete();
            // Result Satisfied the commit to the database table
            DB::commit();

        }catch (Exception $e)
        {
            // Otherwise- rollback the changings
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Handle the Vehicle "restored" event.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return void
     */
    public function restored(Vehicle $vehicle)
    {
        //
    }

    /**
     * Handle the Vehicle "force deleted" event.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return void
     */
    public function forceDeleted(Vehicle $vehicle)
    {
        //
    }
}
