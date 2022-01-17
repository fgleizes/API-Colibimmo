<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Appointment;
use App\Models\Energy_index;
use App\Models\Person;
use App\Models\Person_appointment;
use App\Models\Project;
use App\Models\Status_project;
use App\Models\Type_project;

class PersonAppointmentController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function show()
    {
        $personAppointment = Person_appointment::get();
        $listProject = [];
        $listAppointment = [];

        foreach ($personAppointment as $key => $value) {
            $listProject[$key] = Project::findOrFail($value->id_Project);
            $type_project = Type_project::findOrfail($listProject[$key]->id_Type_project);
            $listProject[$key]->type_project = $type_project->name;
            $personAgent = Project::findOrfail($listProject[$key]->id_PersonAgent);
            $listProject[$key]->manageProject = Person::findOrfail($personAgent->id_Person);
            $listAppointment[$key] = Appointment::findOrFail($value->id_Appointment);
            $address = $listProject[$key]->id_Address;
            $listProject[$key]->address= Address::findOrfail($address);
            $energyIndex = Energy_index::findOrfail($listProject[$key]->id_Energy_index);
            $listProject[$key]->energyIndex = $energyIndex->index;
            $statutProject = Status_project::findOrfail($listProject[$key]->id_Statut_project);
            $listProject[$key]->statutProject = $statutProject->name;
        }

        return [
            response()->json($listAppointment, 200),
            response()->json($listProject, 200)
        ];
    }
}
