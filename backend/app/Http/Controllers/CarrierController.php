<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrier;
use App\Models\TransportJob;

class CarrierController extends Controller
{
    // Összes fuvarozói munka lekérése a bejelentkezett fuvarozónak
    public function index(Request $request)
    {
        $carrier = $request->user();

        // Feltételezzük, hogy a transport_jobs táblában van 'carrier_id'
        $jobs = TransportJob::where('carrier_id', $carrier->id)
            ->with(['job']) // ha van kapcsolat a jobs táblával
            ->get();

        return response()->json($jobs);
    }

    // Státusz frissítése
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);

        $transportJob = TransportJob::findOrFail($id);

        if ($transportJob->carrier_id !== $request->user()->id) {
            return response()->json(['message' => 'Ehhez a fuvarhoz nincs jogosultságod.'], 403);
        }

        $transportJob->status = $request->status;
        $transportJob->save();

        return response()->json([
            'message' => 'Státusz sikeresen frissítve!',
            'job' => $transportJob,
        ]);
    }
}
