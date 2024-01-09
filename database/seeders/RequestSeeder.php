<?php

namespace Database\Seeders;

use App\Models\Request as Requests;
use Illuminate\Database\Seeder;

class RequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Requests::factory()->count(20)->create();
        $allRequests = Requests::all(); // Retrieve all requests

        $guardianRequests = $allRequests->filter(function ($request) {
            return $request->type === Requests::STUDENT_GUARDIAN || $request->type === Requests::EMPLOYEE_GUARDIAN;
        });

        foreach ($guardianRequests as $guardianRequest) {
            $parentRequest = $allRequests->where('id', '<>', $guardianRequest->id)
                ->whereIn('type', [Requests::STUDENT, Requests::EMPLOYEE])
                ->random(); // Get a random parent request

            if ($parentRequest) {
                $guardianRequest->update([
                    'parent_request_id' => $parentRequest->id,
                ]);
            }
        }
    }
}
