<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrier;
use App\Models\Delivery;


class AdminController extends Controller
{
        public function store(Request $request)
        {
            $validated = $request->validate([
                'pickup_address' => 'required|string',
                'delivery_address' => 'required|string',
                'recipient_name' => 'required|string',
                'recipient_phone' => 'required|string',
                'carrier_id' => 'nullable|exists:carriers,id',
                'carrier_name' => 'nullable|string|max:255',
            ]);

            if (empty($validated['carrier_id']) && !empty($validated['carrier_name'])) {
                $carrier = Carrier::create(['name' => $validated['carrier_name']]);
                $validated['carrier_id'] = $carrier->id;
            }

            if (empty($validated['carrier_id'])) {
                return response()->json(['message' => 'Fuvarozó megadása kötelező'], 422);
            }

            $delivery = Delivery::create([
                'pickup_address' => $validated['pickup_address'],
                'delivery_address' => $validated['delivery_address'],
                'recipient_name' => $validated['recipient_name'],
                'recipient_phone' => $validated['recipient_phone'],
                'carrier_id' => $validated['carrier_id'],
            ]);

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
            $delivery = Delivery::findOrFail($id);

            $delivery->update($request->only([
                'start_address',
                'end_address',
                'recipient_name',
                'recipient_contact',
                'status'
            ]));

            return response()->json([
                'message' => 'Munka sikeresen módosítva!',
                'data' => $delivery
            ]);
        }

        public function destroy($id)
        {
            $delivery = Delivery::findOrFail($id);
            $delivery->delete();

            return response()->json(['message' => 'Munka törölve!']);
        }

        public function assignCarrier(Request $request, $id)
        {
            $validated = $request->validate([
                'carrier_id' => 'required|exists:carriers,id'
            ]);

            $delivery = Delivery::findOrFail($id);
            $delivery->carrier_id = $validated['carrier_id'];
            $delivery->status = 'Kiosztva';
            $delivery->save();

            return response()->json([
                'message' => 'Fuvarozó sikeresen hozzárendelve!',
                'data' => $delivery
            ]);
        }




        public function index() {
            $deliveries = Delivery::all();
            return response()->json($deliveries);
        }


        public function getCarriers()
        {
            $carriers = Carrier::all();
            return response()->json($carriers);
        }
}
