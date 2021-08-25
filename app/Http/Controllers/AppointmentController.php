<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Person_appointment;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function create(Request $request){
        $this->validate($request, [
            'subject' => 'string|nullable',
            'start_datetime' => 'date_format:Y-m-d H:i:s|required',
            'end_datetime' => 'date_format:Y-m-d H:i:s|required',
            'id_Type_appointment' => 'integer|required',
            
        ]);

        try {
            $appointment = new Appointment();
            $appointment->subject = $request->input('subject');
            $appointment->start_datetime = $request->input('start_datetime');
            $appointment->end_datetime = $request->input('end_datetime');
            $appointment->id_Type_appointment = $request->input('id_Type_appointment');
            $appointment->save();
            
            // $personAppointment = new Person_appointment();
            // $personAppointment->id_Appointment = $appointment->id;



            return response()->json(['message' => 'APPOINTMENT CREATED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }

    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'subject' => 'string',
            'start_datetime' => 'required|date_format:Y-m-d H:i:s',
            'end_datetime' => 'required|date_format:Y-m-d H:i:s',
            'is_canceled' => 'nullable|integer',
            'id_Type_appointment' => 'required|integer',
        ]);

        try {
            $appointment = Appointment::findOrFail($id);
            $userInput = $request->all();

            foreach ($userInput as $key => $value) {
                if (!empty($value)) {
                    $appointment->$key = $value;
                }else {
                    $appointment->$key = null;
                }
            }
            $appointment->save();

            return response()->json(['message' => 'APPOINTMENT UPDATED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    public function delete($id)
    {
        try {
            $agency = Appointment::findOrFail($id);
            $agency->delete();
    
            return response()->json(['message' => 'APPOINTMENT DELETED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function show()
    {
        return response()->json(Appointment::all(), 200);
    }

    public function showOne($id)
    {
        try{
            return response()->json(Appointment::findOrFail($id), 200);
        }catch (\Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 404);
        }   
    }
}
