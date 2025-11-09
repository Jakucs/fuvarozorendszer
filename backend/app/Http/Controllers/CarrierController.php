<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrier;
use App\Models\TransportJob;
use App\Models\Delivery;

class CarrierController extends Controller
{
    
        public function index(Request $request)
        {
            $user = $request->user();

            $jobs = TransportJob::where('carrier_id', $user->id)
                ->get([
                    'id',
                    'pickup_address',
                    'delivery_address',
                    'recipient_name',
                    'recipient_phone',
                    'status'
                ]);

            return response()->json([
                'user_id' => $user->id,
                'transport_jobs' => $jobs
            ]);
        }







    
        public function updateStatus(Request $request, $id)
            {
                $request->validate(['status' => 'required|string']);

                $job = TransportJob::findOrFail($id);

                
                if ($job->carrier_id !== $request->user()->id) {
                    return response()->json(['message' => 'Ehhez a fuvarhoz nincs jogosultságod.'], 403);
                }

                $job->status = $request->status;
                $job->save();

                return response()->json([
                    'message' => 'Státusz sikeresen frissítve!',
                    'job' => $job
                ]);
            }
}
