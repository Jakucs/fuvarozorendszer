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
                'email' => 'nullable|email|unique:carriers,email',
                'password' => 'nullable|string|min:6',
                'user_id' => 'required|exists:users,id', // ide adjuk át a user ID-t
            ]);

            $carrier = Carrier::create([
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'password' => isset($validated['password']) ? bcrypt($validated['password']) : null,
                'user_id' => $validated['user_id'], // így lesz összekapcsolva
            ]);

            return response()->json([
                'message' => 'Carrier sikeresen létrehozva!',
                'carrier' => $carrier,
            ]);
        }







        public function update(Request $request, $id)
        {
            $delivery = Delivery::findOrFail($id);

            $validated = $request->validate([
                'pickup_address' => 'nullable|string',
                'delivery_address' => 'nullable|string',
                'recipient_name' => 'nullable|string',
                'recipient_phone' => 'nullable|string',
                'carrier_id' => 'nullable|exists:carriers,id',
                'status' => 'nullable|string'
            ]);

            $delivery->update($validated);

            return response()->json([
                'message' => 'Munka sikeresen módosítva!',
                'data' => $delivery->load('carrier')
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




        public function index(Request $request)
        {
            
            $query = Delivery::with('carrier');

            
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            
            return response()->json($query->get());
        }



        public function getCarriers()
        {
            $carriers = Carrier::all();
            return response()->json($carriers);
        }
}
