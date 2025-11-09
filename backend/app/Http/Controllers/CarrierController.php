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

        // Carrier rekord a userhez
        $carrier = Carrier::where('user_id', $user->id)->first();

        if (!$carrier) {
            return response()->json([
                'message' => 'Nincs fuvarozó rekord ehhez a felhasználóhoz'
            ], 404);
        }

        // Fuvarok lekérdezése a carrier.id alapján
        $jobs = TransportJob::where('carrier_id', $carrier->id)
            ->select('id', 'pickup_address', 'delivery_address', 'recipient_name', 'recipient_phone', 'status')
            ->get();

        return response()->json([
            'user_id' => $user->id,
            'transport_jobs' => $jobs
        ]);
    }







    
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);

        $user = $request->user();
        $carrier = Carrier::where('user_id', $user->id)->first();

        if (!$carrier) {
            return response()->json([
                'message' => 'Nincs fuvarozó rekord ehhez a felhasználóhoz'
            ], 404);
        }

        $job = TransportJob::findOrFail($id);

        // Ellenőrizzük, hogy a fuvar tényleg ehhez a carrierhez tartozik
        if ($job->carrier_id !== $carrier->id) {
            return response()->json([
                'message' => 'Ehhez a fuvarhoz nincs jogosultságod.'
            ], 403);
        }

        $job->status = $request->status;
        $job->save();

        return response()->json([
            'message' => 'Státusz sikeresen frissítve!',
            'job' => $job
        ]);
    }
}
