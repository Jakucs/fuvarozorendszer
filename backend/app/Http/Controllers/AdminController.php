<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrier;

class AdminController extends Controller
{
        public function store(Request $request)
        {
            $validated = $request->validate([
                'start_address' => 'required|string',
                'end_address' => 'required|string',
                'recipient_name' => 'required|string',
                'recipient_contact' => 'required|string',
                'carrier_id' => 'required|exists:carriers,id', // ez fontos
            ]);

            $delivery = Delivery::create($validated);

            return response()->json([
                'message' => 'Munka sikeresen létrehozva!',
                'data' => $delivery
            ], 201);
        }


        public function storeCarrier(Request $request)
        {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $carrier = Carrier::create([
                'name' => $validated['name'],
                'email' => null,
                'password' => null,
            ]);

            return response()->json([
                'message' => 'Fuvarozó sikeresen létrehozva!',
                'data' => $carrier
            ], 201);
        }






        public function update(Request $request, $id)
    {
        $delivery = Carrier::findOrFail($id);

        $delivery->update($request->only([
            'pickup_address',
            'delivery_address',
            'recipient_name',
            'recipient_phone',
            'status'
        ]));

        return response()->json([
            'message' => 'Munka sikeresen módosítva!',
            'data' => $delivery
        ]);
    }

        public function destroy($id)
    {
        $delivery = Carrier::findOrFail($id);
        $delivery->delete();

        return response()->json(['message' => 'Munka törölve!']);
    }

    
    public function assignCarrier(Request $request, $id)
    {
        $validated = $request->validate([
            'carrier_id' => 'required|exists:carriers,id'
        ]);

        $delivery = Carrier::findOrFail($id);
        $delivery->carrier_id = $validated['carrier_id'];
        $delivery->status = 'Kiosztva';
        $delivery->save();

        return response()->json([
            'message' => 'Fuvarozó sikeresen hozzárendelve!',
            'data' => $delivery
        ]);
    }



        public function index()
        {
            $carriers = Carrier::all();
            return response()->json($carriers);
        }

        public function getCarriers()
        {
            $carriers = Carrier::all();
            return response()->json($carriers);
        }
}
