<?php

namespace App\Http\Controllers;

use App\Models\Type_appointment;
use Illuminate\Http\Request;

class typeAppointmentController extends Controller
{
    public function create(Request $request){
        $this->validate($request, [
            'name' => 'string',
        ]);

        try {
            $typeAppointment = new Type_appointment();
            $typeAppointment->name = $request->input('name');
            $typeAppointment->save();

            return response()->json(['message' => 'CREATED TYPE_APPOINTMENT'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }

    }
}
