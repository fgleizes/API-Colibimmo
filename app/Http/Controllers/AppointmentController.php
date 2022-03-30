<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\City;
use App\Models\Department;
use App\Models\Region;
use App\Models\Agency;
use App\Models\Appointment;
use App\Models\Energy_index;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Person_appointment;
use App\Models\Project;
use App\Models\Role;
use App\Models\Status_project;
use App\Models\Type_appointment;
use App\Models\Type_project;

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
            'start_datetime' => 'nullable|date_format:Y-m-d H:i:s',
            'end_datetime' => 'nullable|date_format:Y-m-d H:i:s',
            'is_canceled' => 'nullable|integer',
            'id_Type_appointment' => 'nullable|integer',
            'id_Project1' => 'integer|nullable|exists:project,id',
            'id_Project2' => 'integer|nullable|exists:project,id',
        ]);

        try {
            $appointment = Appointment::findOrFail($id);
            $userInput = $request->all();

            foreach ($userInput as $key => $value) {
                if (!empty($value) && $key != 'id_Project1' && $key != 'id_Project2') {
                    $appointment->$key = $value;
                } 
                // else if ($key == 'id_Project1' || $key == 'id_Project2') {
                //     // $personAppointment->id_Project1 = Person_appointment::where('id_Appointment', $value)->firstOrFail()->id;
                //     $personAppointments = Person_appointment::where('id_Appointment', $id)->get();
                //     foreach ($personAppointments as $personAppointment) {
                //         if ($personAppointment != $key) {
                //             # code...
                //         }
                //         $personAppointment->delete();
                //     }
                // } else if ($key == 'id_Project2') {
                //     // $personAppointment->id_Project2 = Person_appointment::where($appointment->id, $value)->firstOrFail()->id;
                // } 
                else {
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
        $appointments = Appointment::with('person_appointment')->get();
        foreach ($appointments as $appointment) {
            $appointment->type = Type_appointment::findOrFail($appointment->id_Type_appointment);
            foreach( $appointment->person_appointment as $person_appointment) {
                $person_appointment->project = Project::findOrfail($person_appointment->id_Project, ['id','reference', 'id_PersonAgent', 'id_Person', 'id_Type_project', 'id_Address']);
                $person_appointment->project->type = Type_project::findOrFail($person_appointment->project->id_Type_project)->name;
                $person_appointment->project->personAgent = Person::findOrFail($person_appointment->project->id_PersonAgent);
                $person_appointment->project->person = Person::findOrFail($person_appointment->project->id_Person);
                if(isset($person_appointment->project->id_Address)) {
                    $person_appointment->project->address = Address::findOrfail($person_appointment->project->id_Address);
                    $person_appointment->project->address->city = City::findOrfail($person_appointment->project->address->id_City);
                    $person_appointment->project->address->department = Department::findOrfail($person_appointment->project->address->city->id_Department);
                    $person_appointment->project->address->region = Region::findOrfail($person_appointment->project->address->department->id_Region);
                }
            }
        }
            
        return response()->json($appointments,200);
    }

    public function showOne($id)
    {
        try{
            return response()->json(Appointment::with('person_appointment')->findOrFail($id), 200);
        }catch (\Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 404);
        }   
    }

    public function showAppointmentsForAuthUser()
    {
        try {
            $appointments = Appointment::with('person_appointmentProject:id_PersonAgent,id_Person,reference')->whereHas('person_appointmentProject', function($q) {
                $q->where('id_PersonAgent', Auth::user()->id);
            })->get();

            foreach($appointments as $appointment){
                foreach ($appointment->person_appointmentProject as $project) {
                    $project->personAgent = Person::findOrfail($project->id_PersonAgent);
                    $project->person = Person::findOrfail($project->id_Person);
                    $project->address = Address::findOrfail($project->person->id_Address);
                }
            }
            return response()->json( $appointments, 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    public function showAppointmentsForCustomer($idPerson)
    {
        try {
            $appointments = Appointment::with('person_appointmentProject:id_PersonAgent,id_Person,project.id,reference,id_Type_project')->whereHas('person_appointmentProject', function ($query) use($idPerson) {
                $query->where('id_Person', $idPerson);
            })->get();

            foreach ($appointments as $appointment) {
                foreach ($appointment->person_appointmentProject as $project) {
                    $project->type = Type_project::findOrfail($project->id_Type_project)->name;
                    $project->person = Person::findOrfail($project->id_Person, ['id', 'lastname', 'firstname', 'mail', 'phone', 'id_Role']);
                    $project->person->role = Role::findOrfail($project->person->id_Role)->name;
                    unset($project->person->id_Role);
                }

                $appointment->personAgent = Person::findOrfail($appointment->person_appointmentProject[0]->id_PersonAgent, ['id','lastname','firstname','mail','phone']);
            }
            return response()->json($appointments, 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }
    
    
    public function showByProject($id_Project)
    {
        try{
            $person_appointment = Person_appointment::where('id_Project', $id_Project)->get();
            $appointments=[];
            foreach ($person_appointment as $key => $value) {
                // $appointments[$key] = Appointment::with('type_appointment')->findOrFail($value->id_Appointment);
                $appointments[$key] = Appointment::findOrFail($value->id_Appointment);
                $type_appointment = Type_appointment::findOrFail($appointments[$key]->id_Type_appointment);
                // $appointments[$key]->id_Project = $person_appointment[$key]->id_Project;
                $appointments[$key]->type_appointment = $type_appointment->name;
            }
            return response()->json($appointments, 200);
        }catch (\Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 404);
        }   
    }

    public function showTypeAppointment($id){
        try{
            return response()->json(Type_appointment::where('id',$id)->get(),200);
        }catch (\Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 404);
        } 
    }
}