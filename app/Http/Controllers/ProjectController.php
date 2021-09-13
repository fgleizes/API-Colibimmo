<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Project;
use App\Models\Note;
use App\Models\Person;
use App\Models\Address;
use App\Models\Document;
use App\Models\Appointment;
use App\Models\Energy_index;
use App\Models\Room_project;
use App\Models\Type_project;
use Illuminate\Http\Request;
use App\Models\Option_project;
use App\Models\Status_project;
use App\Models\Location_project;
use App\Models\Type_property_project;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use App\Models\Room;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [

            'additional_information' => 'string|nullable',
            'commission' => 'integer|nullable',
            // 'area' => 'nullable|regex:/^\d{1,6}(\.\d{1,2})?$/',
            'area' => 'numeric|nullable',
            'min_area' => 'integer|nullable',
            'max_area' => 'integer|nullable',
            'price' => 'numeric|nullable',
            'min_price' => 'numeric|nullable',
            'max_price' => 'numeric|nullable',
            'short_description' => 'string|nullable',
            'description' => 'string|nullable',
            'visibility_priority' => 'integer|nullable',
            'options'=>'string|nullable',
            'rooms'=>'string|nullable',
            'type'=>'exists:type_property,id|required',
            'id_Person' => 'exists:person,id|required',
            'id_Type_project' => 'exists:type_project,id|required',
            // 'id_Statut_project' => 'exists:status_project,id|required',
            'id_Energy_index' => 'exists:energy_index,id|nullable',
            'id_Address' => 'exists:address,id|nullable',
            // 'id_Manage_project' => 'exists:manage_project,id|required',
            'id_PersonAgent' => 'exists:person,id|required'
            
        ]);



        try {
            $project = new Project();
            // $project->reference = ('lou2408');
            $startRef = substr(Type_project::findOrFail($request->input('id_Type_project'))->name, 0, 3);
            $endRef = substr(Person::findOrFail($request->input('id_Person'))->firstname, 0, 2) . substr(Person::findOrFail($request->input('id_Person'))->lastname, 0, 3);
            // dd(strtoupper($startRef . bin2hex(random_bytes((20-strlen($startRef)-strlen($endRef))/2)) . $endRef));
            $project->reference = strtoupper($startRef . bin2hex(random_bytes((20 - strlen($startRef) - strlen($endRef)) / 2)) . $endRef);

            $project->additional_information = $request->input('additional_information');
            $project->commission = $request->input('commission');
            $project->area = $request->input('area');
            $project->min_area = $request->input('min_area');
            $project->max_area = $request->input('max_area');
            $project->price = $request->input('price');
            $project->min_price = $request->input('min_price');
            $project->max_price = $request->input('max_price');
            $project->short_description = $request->input('short_description');
            $project->description = $request->input('description');
            $project->visibility_priority = $request->input('visibility_priority');
            $project->id_Person = $request->input('id_Person');
            $project->id_Type_project = $request->input('id_Type_project');
            $project->id_Statut_project = 1;
            $project->id_Energy_index = $request->input('id_Energy_index');
            $project->id_Address = $request->input('id_Address');
            // $project->id_Manage_project = $request->input('id_Manage_project');
            // if (Person::where('id_Role', 4)->findOrFail($request->input('id_PersonAgent'))) {
                $project->id_PersonAgent = $request->input('id_PersonAgent');
            // }

            //recuperation des entrées pour type_property_project et ajouts.
            $project->save();
            $type = $request->input('type');
            $typeProject= new Type_property_project();
            $typeProject->id_Type_property=$type;
            $typeProject->id_Project=$project->id;
            $typeProject->save();

            //recuperation des entrées pour room et ajouts.
            $inputRooms = $request->input('rooms');
            $rooms=unserialize($inputRooms);
           
            foreach($rooms as $room){
                $roomProject = new Room();
                $roomProject->id_Type_room = $room['id_Type_room'];
                $roomProject->area = $room['area'];
                $roomProject->id_Project = $project->id;
                $roomProject->save();
            }

            //recuperation des entrées pour options_project et ajouts.
            $inputOptions = $request->input('options');
            $options=unserialize($inputOptions);
            
            foreach($options as $option){
                $optionProject = new Option_project();
                $optionProject->id_Option = $option;
                $optionProject->id_Project = $project->id;
                $optionProject->save();
            }


            return response()->json(['message' => 'CREATED'], 201);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showOne($id)
    {
        try {
            return response()->json(Project::with('project_option')->findOrFail($id), 200);
            // return response()->json(Project::with('note')->where('id',$id)->get(), 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return response()->json(Project::with('project_option')->get(), 200);
        // return response()->json(Project::with('note')->get(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'additional_information' => 'string|nullable',
            'commission' => 'integer|nullable',
            // 'area' => 'nullable|regex:/^\d{1,6}(\.\d{1,2})?$/',
            'area' => 'numeric|nullable',
            'min_area' => 'integer|nullable',
            'max_area' => 'integer|nullable',
            'price' => 'numeric|nullable',
            'min_price' => 'numeric|nullable',
            'max_price' => 'numeric|nullable',
            'short_description' => 'string|nullable',
            'description' => 'string|nullable',
            'visibility_priority' => 'integer|nullable',
            'options'=>'string|nullable',
            'type'=>'string|nullable',
            'id_Person' => 'exists:person,id|nullable',
            'id_Type_project' => 'exists:type_project,id|nullable',
            'id_Statut_project' => 'exists:status_project,id|nullable',
            'id_Energy_index' => 'exists:energy_index,id|nullable',
            'id_Address' => 'exists:address,id|nullable',
            'id_Manage_project' => 'exists:manage_project,id|nullable'
        ]);

        try {
            $project = Project::findOrFail($id);
            $userInput = $request->all();
            foreach ($userInput as $key => $value) {
                if(!empty($value) && $key != 'options'){
                    $project->$key = $value;
                } else if($key == 'options') {
                    $newOptions=unserialize($value);
                    
                    $oldOptions=Option_project::where('id_Project',$project->id)->get()->pluck('id_Option')->toArray();
                    
                    // dd($oldOptions->toArray());
                    $optionsToDelete=array_diff($oldOptions,$newOptions);
                    // dd($optionsToDelete);
                    foreach($optionsToDelete as $optionToDelete){
                        Option_project::where('id_Option',$optionToDelete)->where('id_Project',$project->id)->delete();
                    }
                    foreach($newOptions as $option){
                        
                        
                        if(!sizeof(Option_project::where('id_Option',$option)->where('id_Project',$project->id)->get())){
                            $optionProject = new Option_project();
                            $optionProject->id_Option = $option;
                            $optionProject->id_Project = $project->id;
                            $optionProject->save();
                            // dd($option);
                        } 
                        
                    }
                } else {
                    $project->$key = null;
                }
            }

            
            
            // $project->reference;
            // $project->note = $request->input('note');
            // $project->commission = $request->input('commission');
            // $project->area = $request->input('area');
            // $project->min_area = $request->input('min_area');
            // $project->max_area = $request->input('max_area');
            // $project->price = $request->input('price');
            // $project->min_price = $request->input('min_price');
            // $project->max_price = $request->input('max_price');
            // $project->short_description = $request->input('short_description');
            // $project->description = $request->input('description');
            // $project->visibility_priority = $request->input('visibility_priority');
            // $project->id_Person = $request->input('id_Person');
            // $project->id_Type_project = $request->input('id_Type_project');
            // $project->id_Statut_project = $request->input('id_Statut_project');
            // $project->id_Energy_index = $request->input('id_Energy_index');
            // $project->id_Address = $request->input('id_Address');
            // $project->id_Manage_project = $request->input('id_Manage_project');

            $project->save();

            

            return response()->json(['message' => 'UPDATED'], 201);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            $project = Project::findOrFail($id);
            $optionProjects = Option_project::where('id_project', $id)->get();
            foreach ($optionProjects as $optionProject) {
                $optionProject->delete(); 
            }
            $project->delete();
    
            return response()->json(['message' => 'PROJECT DELETED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }
}
