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

            'note' => 'string|nullable',
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
            // 'options' => 'exists:option,id|required',
            'rooms'=>'string|nullable',
            // 'rooms' => 'exists:type_room,id|required',
            'types'=>'string|nullable',
            // 'types' => 'exists:type_property,id|required',
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
            $project->reference = ('lou2408');

            $project->note = $request->input('note');
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
            $inputPropertyTypes = $request->input('types');
            $propertyTypes=unserialize($inputPropertyTypes);

            $project->save();
            foreach($propertyTypes as $propertyType){
                $propertyProject = new Type_property_project();
                $propertyProject->id_Type_property = $propertyType;
                $propertyProject->id_Project = $project->id;
                $propertyProject->save();
            }

            // $type = $request->input('type');
            // $typeProject= new Type_property_project();
            // $typeProject->id_Type_property=$type;
            // $typeProject->id_Project=$project->id;
            // $typeProject->save();

            //recuperation des entrées pour room et ajouts.
            //deserialisation de la chaine de caractère types -> tableaux de données
            $inputRooms = $request->input('rooms');
            $rooms=unserialize($inputRooms);
           
            //tableaux à deux niveaux, cibler les keys de chaque colonnes type_room et area
            foreach($rooms as $room){
                $roomProject = new Room();
                $roomProject->id_Type_room = $room['id_Type_room'];
                $roomProject->area = $room['area'];
                $roomProject->id_Project = $project->id;
                $roomProject->save();
            }

            //recuperation des entrées pour options_project et ajouts.
            //deserialisation de la chaine de caractère options -> tableaux de données

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
            'note' => 'string|nullable',
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
     * DELETE ROOM/OPTION/PROPERTY_TYPE LINK TO PROJECT->id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            $project = Project::findOrFail($id);
            $optionProjects = Option_project::where('id_project', $id)->get();
            $roomProjects = Room::where('id_project', $id)->get();
            $propertyProjects = Type_property_project::where('id_project', $id)->get();
            foreach ($optionProjects as $optionProject) {
                $optionProject->delete(); 
            }
            foreach ($roomProjects as $roomProject) {
                $roomProject->delete(); 
            }
            foreach ($propertyProjects as $propertyProject) {
                $propertyProject->delete(); 
            }
            $project->delete();
    
            return response()->json(['message' => 'PROJECT DELETED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }
    
    
}
