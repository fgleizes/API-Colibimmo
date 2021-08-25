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
            'id_Type_appointment' => 'integer|required|exists:type_appointment,id',
            'id_Project1' => 'integer|required|exists:project,id',
            'id_Project2' => 'integer|nullable|exists:project,id',
            
        ]);

        try {
            $appointment = new Appointment();
            $appointment->subject = $request->input('subject');
            $appointment->start_datetime = $request->input('start_datetime');
            $appointment->end_datetime = $request->input('end_datetime');
            $appointment->id_Type_appointment = $request->input('id_Type_appointment');
            $appointment->save();
            
            $personAppointment1 = new Person_appointment();
            $personAppointment1->id_Appointment = $appointment->id;
            $personAppointment1->id_Project = $request->input('id_Project1');
            // $personAppointment1->id_Project = Project::findOrFail($request->input('id_Project1'))->id;
            $personAppointment1->save();

            if($request->input('id_Project2') != null && !empty($request->input('id_Project2'))) {
                $personAppointment2 = new Person_appointment();
                $personAppointment2->id_Appointment = $appointment->id;
                $personAppointment2->id_Project = $request->input('id_Project2');
                $personAppointment2->save();
            }

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
            $appointment = Appointment::findOrFail($id);
            $personAppointments = Person_appointment::where('id_Appointment', $id)->get();
            foreach ($personAppointments as $personAppointment) {
                $personAppointment->delete();
            }
            $appointment->delete();
    
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
