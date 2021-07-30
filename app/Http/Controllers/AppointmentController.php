<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function create(Request $request){
        $this->validate($request, [
            'subject' => 'string',
            'start_datetime' => 'date_format:d/m/Y',
            'end_datetime' => 'date_format:d/m/Y',
            'is_canceled' => 'integer',
            'id_Type_appointment' => 'exists:type_appointment,id',
        ]);

        try {
            $agency = new Appointment();
            $agency->name = $request->input('subject');
            $agency->mail = $request->input('start_datetime');
            $agency->phone = $request->input('end_datetime');
            $agency->phone = $request->input('id_Type_appointment');
            $agency->id_Type_appointment = 1;
            $agency->save();

            return response()->json(['message' => 'CREATED AGENCY'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }

    }
}
