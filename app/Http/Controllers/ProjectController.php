<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Note;
use App\Models\Room;
use App\Models\Option;
use App\Models\Person;
use App\Models\Address;
use App\Models\Project;
use App\Models\Agency;
use App\Models\Document;
use App\Models\Appointment;
use App\Models\Energy_index;
use App\Models\Room_project;
use App\Models\Type_project;
use Illuminate\Http\Request;
use App\Models\Manage_project;
use App\Models\Option_project;
use App\Models\Status_project;
use App\Models\Location_project;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use App\Models\Type_property_project;
use App\Models\Type_room;
use App\Models\Department;
use App\Models\Favorite;
use App\Models\Person_appointment;
use App\Models\Region;

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
            $startRef = substr(Type_project::findOrFail($request->input('id_Type_project'))->name, 0, 3);
            $endRef = substr(Person::findOrFail($request->input('id_Person'))->firstname, 0, 2) . substr(Person::findOrFail($request->input('id_Person'))->lastname, 0, 3);
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

            $project->save();


            // // Création du dossier de stockage du projet
            // if (!mkdir(storage_path('projects/'.$project->reference), 0777, true)) {
            //     die('Échec lors de la création du dossier projet...');
            // }
            // // Création des dossiers photos et documents
            // if (!mkdir(storage_path('projects/pictures'), 0777, true) && !mkdir(storage_path('projects/documents'), 0777, true)) {
            //     die('Échec lors de la création des dossiers...');
            // }

            //recuperation des entrées pour type_property_project et ajouts.
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

            // $project->save();

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
            $project = Project::findOrFail($id);

            $project->option_project=Option_project::where('id_Project', $project->id)->get();
            foreach($project->option_project as $key => $value) {
                $project->option_project[$key]->name = Option::findOrFail($value->id_Option)->name;
            }
            $project->room_project=Room::where('id_Project', $project->id)->get();
            foreach($project->room_project as $key => $value) {
                $project->room_project[$key]->name = Type_room::findOrFail($value->id_Type_room)->name;
            }
            
            // $project->id_Type_project=Type_project::findOrFail($project->id_Type_project);
            $project->type_Project = Type_project::findOrfail($project->id_Type_project);
            $project->id_Statut_project=Status_project::findOrFail($project->id_Statut_project);            
            $project->id_Address=Address::findOrFail($project->id_Address);
            $project->id_Address->City=City::findOrFail($project->id_Address->id_City);
            $project->id_Address->City->Departement=Department::findOrFail($project->id_Address->City->id_Department);
            $project->id_Address->City->Departement->Region=Region::findOrFail($project->id_Address->City->Departement->id_Region);

            // $manageProject = Manage_project::findOrFail($project->id_Manage_project);
            $project->personAgent = Person::findOrFail($project->id_PersonAgent);
            $project->personProject = Person::findOrFail($project->id_Person);
            $project->energieIndex = Energy_index::findOrFail($project->id_Energy_index);
            
            return response()->json($project, 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showByPerson($id_Person)
    {
        try {
            $projects = Project::where('id_Person',$id_Person)->get();
            // dd($projects);
            // $projectPerson=[];
            // foreach ($projects as $key => $value) {
            //     $projectPerson[$key] = Project::findOrFail($value->id);
            //     $type_project = Type_project::findOrfail($projectPerson[$key]->id_Type_project);
            //     $projectPerson[$key]->type_project = $type_project->name;
            //     $customer = $projectPerson[$key]->id_Person;
            //     $projectPerson[$key]->customer= Person::findOrfail($customer);
            //     $manageProject = Manage_project::findOrfail($projectPerson[$key]->id_Manage_project);
            //     $projectPerson[$key]->manageProject = Person::findOrfail($manageProject->id_Person);
            //     $address = $projectPerson[$key]->id_Address;
            //     $projectPerson[$key]->address= Address::findOrfail($address);
            //     $agency = $projectPerson[$key]->manageProject->id_Agency;
            //     $projectPerson[$key]->agency = Agency::findOrfail($agency);
            //     $energyIndex = Energy_index::findOrfail($projectPerson[$key]->id_Energy_index);
            //     $projectPerson[$key]->energyIndex = $energyIndex->index;
            //     $statutProject = Status_project::findOrfail($projectPerson[$key]->id_Statut_project);
            //     $projectPerson[$key]->statutProject = $statutProject->name;
            // }
            foreach($projects as $project) {
                $project->person = Person::findOrfail($project->id_Person);
                $project->personAgent = Person::findOrfail($project->id_PersonAgent);
                $project->type_Project = Type_project::findOrfail($project->id_Type_project); // "id_Type_project": 1,
                $project->id_Statut_project = Status_project::findOrfail($project->id_Statut_project); // "id_Statut_project": 1,
                $project->id_Energy_index = Energy_index::findOrfail($project->id_Energy_index); // "id_Energy_index": 1,
                $project->address = Address::find($project->id_Address); // "id_Address": 5,
                if(isset($project->id_Address)) {
                    $project->address->city = City::findOrfail($project->address->id_City);
                    $project->address->department = Department::findOrfail($project->address->city->id_Department);
                    $project->address->region = Region::findOrfail($project->address->department->id_Region);
                    unset($project->id_Address);
                }
                $project->personAgent->agency = Agency::find($project->personAgent->id_Agency);
                if (isset($project->personAgent->id_Agency)) {
                    $project->personAgent->agency->address = Address::findOrfail($project->personAgent->agency->id_Address);
                    $project->personAgent->agency->city = City::findOrfail($project->personAgent->agency->address->id_City);
                    $project->personAgent->agency->department = Department::findOrfail($project->personAgent->agency->city->id_Department);
                    $project->personAgent->agency->region = Region::findOrfail($project->personAgent->agency->department->id_Region);
                    unset($project->personAgent->id_Agency);
                }
                $project->type_property = Type_property_project::where('id_Project', $project->id)->get();
            }
            // dd($projects);
            // return response()->json($projectPerson, 200);
            return response()->json($projects, 200);
            
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    public function showByAgent($id_PersonAgent)
    {
        try {
            $projects = Project::where('id_PersonAgent',$id_PersonAgent)->get();
            // dd($projects);
            // $projectPerson=[];
            // foreach ($projects as $key => $value) {
            //     $projectPerson[$key] = Project::findOrFail($value->id);
            //     $type_project = Type_project::findOrfail($projectPerson[$key]->id_Type_project);
            //     $projectPerson[$key]->type_project = $type_project->name;
            //     $customer = $projectPerson[$key]->id_Person;
            //     $projectPerson[$key]->customer= Person::findOrfail($customer);
            //     $manageProject = Manage_project::findOrfail($projectPerson[$key]->id_Manage_project);
            //     $projectPerson[$key]->manageProject = Person::findOrfail($manageProject->id_Person);
            //     $address = $projectPerson[$key]->id_Address;
            //     $projectPerson[$key]->address= Address::findOrfail($address);
            //     $agency = $projectPerson[$key]->manageProject->id_Agency;
            //     $projectPerson[$key]->agency = Agency::findOrfail($agency);
            //     $energyIndex = Energy_index::findOrfail($projectPerson[$key]->id_Energy_index);
            //     $projectPerson[$key]->energyIndex = $energyIndex->index;
            //     $statutProject = Status_project::findOrfail($projectPerson[$key]->id_Statut_project);
            //     $projectPerson[$key]->statutProject = $statutProject->name;
            // }
            foreach($projects as $project) {
                $project->person = Person::findOrfail($project->id_Person);
                $project->personAgent = Person::findOrfail($project->id_PersonAgent);
                $project->type_Project = Type_project::findOrfail($project->id_Type_project); // "id_Type_project": 1,
                $project->id_Statut_project = Status_project::findOrfail($project->id_Statut_project); // "id_Statut_project": 1,
                $project->id_Energy_index = Energy_index::findOrfail($project->id_Energy_index); // "id_Energy_index": 1,
                $project->address = Address::find($project->id_Address); // "id_Address": 5,
                if(isset($project->id_Address)) {
                    $project->address->city = City::findOrfail($project->address->id_City);
                    $project->address->department = Department::findOrfail($project->address->city->id_Department);
                    $project->address->region = Region::findOrfail($project->address->department->id_Region);
                    unset($project->id_Address);
                }
                $project->option_project=Option_project::where('id_Project', $project->id)->get();
                foreach($project->option_project as $key => $value) {
                    $project->option_project[$key]->name = Option::findOrFail($value->id_Option)->name;
                }
                $project->room_project=Room::where('id_Project', $project->id)->get();
                foreach($project->room_project as $key => $value) {
                    $project->room_project[$key]->name = Type_room::findOrFail($value->id_Type_room)->name;
                }
                $project->personAgent->agency = Agency::find($project->personAgent->id_Agency);
                if (isset($project->personAgent->id_Agency)) {
                    $project->personAgent->agency->address = Address::findOrfail($project->personAgent->agency->id_Address);
                    $project->personAgent->agency->city = City::findOrfail($project->personAgent->agency->address->id_City);
                    $project->personAgent->agency->department = Department::findOrfail($project->personAgent->agency->city->id_Department);
                    $project->personAgent->agency->region = Region::findOrfail($project->personAgent->agency->department->id_Region);
                    unset($project->personAgent->id_Agency);
                }
                $project->type_property = Type_property_project::where('id_Project', $project->id)->get();
            }
            // dd($projects);
            // return response()->json($projectPerson, 200);
            return response()->json($projects, 200);
            
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showFavorites($id_Person)
    {
        try {
            $favorites = Favorite::where('id_Person',$id_Person)->get();
            $projects = [];

            foreach($favorites as $favorite) {
                $project = Project::findOrFail($favorite->id_Project);
                $project->person = Person::findOrfail($project->id_Person);
                $project->personAgent = Person::findOrfail($project->id_PersonAgent);
                $project->type_Project = Type_project::findOrfail($project->id_Type_project); // "id_Type_project": 1,
                $project->id_Statut_project = Status_project::findOrfail($project->id_Statut_project); // "id_Statut_project": 1,
                $project->id_Energy_index = Energy_index::findOrfail($project->id_Energy_index); // "id_Energy_index": 1,
                $project->address = Address::find($project->id_Address); // "id_Address": 5,
                if(isset($project->id_Address)) {
                    $project->address->city = City::findOrfail($project->address->id_City);
                    $project->address->department = Department::findOrfail($project->address->city->id_Department);
                    $project->address->region = Region::findOrfail($project->address->department->id_Region);
                    unset($project->id_Address);
                }
                $project->personAgent->agency = Agency::find($project->personAgent->id_Agency);
                if (isset($project->personAgent->id_Agency)) {
                    $project->personAgent->agency->address = Address::findOrfail($project->personAgent->agency->id_Address);
                    $project->personAgent->agency->city = City::findOrfail($project->personAgent->agency->address->id_City);
                    $project->personAgent->agency->department = Department::findOrfail($project->personAgent->agency->city->id_Department);
                    $project->personAgent->agency->region = Region::findOrfail($project->personAgent->agency->department->id_Region);
                    unset($project->personAgent->id_Agency);
                }
                $project->type_property = Type_property_project::where('id_Project', $project->id)->get();
                array_push($projects, $project);
            }

            return response()->json($projects, 200);
            
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
        $projects = Project::all();
        foreach ($projects as $project) {
            // $mainImagePath =  Document::where('id_Type_document', '2')->where('id_Project', $project->id)->first();
            // $project->mainImagePath = $mainImagePath ? $mainImagePath->pathname : '../IMG/imgAppart.jpg';
            $project->id_Person=Person::findOrFail($project->id_Person);
            $project->id_PersonAgent=Person::findOrFail($project->id_PersonAgent);
            $project->option_project=Option_project::where('id_Project', $project->id)->get();
            foreach($project->option_project as $key => $value) {
                $project->option_project[$key]->name = Option::findOrFail($value->id_Option)->name;
            }
            $project->room_project=Room::where('id_Project', $project->id)->get();
            foreach($project->room_project as $key => $value) {
                $project->room_project[$key]->name = Type_room::findOrFail($value->id_Type_room)->name;
            }
            // $project->id_Type_project=Type_project::findOrFail($project->id_Type_project);
            $project->type_Project = Type_project::findOrfail($project->id_Type_project);
            $project->id_Statut_project=Status_project::findOrFail($project->id_Statut_project);
            $project->id_Address=Address::find($project->id_Address);
            if (isset($project->id_Address)) {
                $project->id_Address->City=City::findOrFail($project->id_Address->id_City);
                $project->id_Address->City->Departement=Department::findOrFail($project->id_Address->City->id_Department);
                $project->id_Address->City->Departement->Region=Region::findOrFail($project->id_Address->City->Departement->id_Region);
            }
        }
        return response()->json($projects, 200);
        
        
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
                    $optionsToDelete=array_diff($oldOptions,$newOptions);
                    
                    foreach($optionsToDelete as $optionToDelete){
                        Option_project::where('id_Option',$optionToDelete)->where('id_Project',$project->id)->delete();
                    }
                    foreach($newOptions as $option){
                        
                        if(!sizeof(Option_project::where('id_Option',$option)->where('id_Project',$project->id)->get())){
                            $optionProject = new Option_project();
                            $optionProject->id_Option = $option;
                            $optionProject->id_Project = $project->id;
                            $optionProject->save();
                        } 
                        
                    }
                } else {
                    $project->$key = null;
                }
            }

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
            $favoriteProjects = Favorite::where('id_project', $id)->get();
            $personAppointmentProjects = Person_appointment::where('id_project', $id)->get();
            $roomProjects = Room::where('id_project', $id)->get();
            $type_propertyProjects = Type_property_project::where('id_project', $id)->get();
            $documentProjects = Document::where('id_project', $id)->get();
            $locationProjects = Location_project::where('id_project', $id)->get();
            $noteProjects = Note::where('id_project', $id)->get();
            $room_Projects = Room_project::where('id_project', $id)->get();
            foreach ($personAppointmentProjects as $personAppointmentProject) {
                $personAppointmentProject->delete(); 
            }
            foreach ($favoriteProjects as $favoriteProject) {
                $favoriteProject->delete(); 
            }
            foreach ($optionProjects as $optionProject) {
                $optionProject->delete(); 
            }
            foreach ($roomProjects as $roomProject) {
                $roomProject->delete(); 
            }
            foreach ($type_propertyProjects as $type_propertyProject) {
                $type_propertyProject->delete(); 
            }
            foreach ($documentProjects as $documentProject) {
                $documentProject->delete(); 
            }
            foreach ($locationProjects as $locationProject) {
                $locationProject->delete(); 
            }
            foreach ($noteProjects as $noteProject) {
                $noteProject->delete(); 
            }
            foreach ($room_Projects as $room_Project) {
                $room_Project->delete(); 
            }
            $project->delete();
    
            return response()->json(['message' => 'PROJECT DELETED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function showTypeProject($id)
    {
        try{
            return response()->json(Type_project::where('id',$id)->get(),200);
        }catch (\Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 404);
        } 
    }

    public function showAllTypeProject()
    {
        try{
            return response()->json(Type_project::all(),200);
        }catch (\Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 404);
        } 
    }

    public function showStatutProject($id)
    {
        try{
            return response()->json(Status_project::where('id',$id)->get(),200);
        }catch (\Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 404);
        } 
    }

    public function showManageProject($id)
    {
        try{
            return response()->json(Manage_project::where('id',$id)->get(),200);
        }catch (\Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 404);
        } 
    }
    public function showEnergyIndex($id)
    {
        try{
            return response()->json(Energy_index::where('id',$id)->get(),200);
        }catch (\Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 404);
        } 
    }

    public function storeDocumentsToProject(Request $request, $id)
    {
        $this->validate($request, [
            'document' => 'required|image:jpeg,png,jpg,gif,svg,pdf|max:2048',
            'id_Type_document' => 'exists:type_document,id|required'
        ]);
        
        try {
            $projectFolder = Project::findOrFail($id)->reference;

            if ($request->hasFile('document')) {
                if ($request->file('document')->isValid()) {
                    // Save image
                    if($request->input('id_Type_document') == "1"
                    || $request->input('id_Type_document') == "2") {
                        // $path = storage_path('projects/' . $projectFolder . '/pictures');
                        $path = '../storage/projects/' . $projectFolder . '/pictures';
                    } else {
                        // $path = storage_path('projects/' . $projectFolder . '/documents');
                        $path = '../storage/projects/' . $projectFolder . '/documents';
                    }
                    $returnUpload = $this->uploadImage($path, $request->file('document'));

                    // Save in base
                    if(is_array($returnUpload) && $returnUpload['folder'] && $returnUpload['filename']) {
                        $document = new Document();
                        $document->pathname = $returnUpload['folder'] . '/' . $returnUpload['filename'];
                        $document->id_Project = $id;
                        $document->id_Type_document = $request->input('id_Type_document');
                        $document->save();

                        return response()->json(['message' => 'DOCUMENT STORED'], 200);
                    } else {
                        return $returnUpload;
                    }
                }
            }

        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    public function showMainImageProject($id) {
        try {
            $image = Document::where('id_Project', $id)->where('id_Type_document', '2')->firstOrFail();

            $source_url_parts = pathinfo($image->pathname);

            $folder = $source_url_parts['dirname'];
            $fileName = $source_url_parts['filename'];
            $fileExtension = $source_url_parts['extension'];
            $fileName .= '-resized.' . $fileExtension;
            $thumbPathname = $folder . '/' . $fileName;

            // Read image path, convert to base64 encoding
            $imageData = base64_encode(file_get_contents($image->pathname));
            $thumbData = base64_encode(file_get_contents($thumbPathname));
            // Format the image SRC:  data:{mime};base64,{data};
            $src = 'data: ' . mime_content_type($image->pathname) . ';base64,' . $imageData;
            $srcThumb = 'data: ' . mime_content_type($thumbPathname) . ';base64,' . $thumbData;
            $image_base64["image"] = $src;
            $image_base64["thumb"] = $srcThumb;

            return response()->json($image_base64, 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    public function showImagesProject($id)
    {
        try {
            $images = Document::where('id_Project', $id)->whereIn('id_Type_Document', [1, 2])->get();

            $images_base64 = [];

            for ($i = 0; $i < sizeof($images); $i++) {
                $source_url_parts = pathinfo($images[$i]->pathname);

                $folder = $source_url_parts['dirname'];
                $fileName = $source_url_parts['filename'];
                $fileExtension = $source_url_parts['extension'];
                $fileName .= '-resized.' . $fileExtension;
                $thumbPathname = $folder . '/' . $fileName;

                // Read image path, convert to base64 encoding
                $imageData = base64_encode(file_get_contents($images[$i]->pathname));
                $thumbData = base64_encode(file_get_contents($thumbPathname));
                // Format the image SRC:  data:{mime};base64,{data};
                $src = 'data: ' . mime_content_type($images[$i]->pathname) . ';base64,' . $imageData;
                $srcThumb = 'data: ' . mime_content_type($thumbPathname) . ';base64,' . $thumbData;
                $images_base64[$i]["image"] = $src;
                $images_base64[$i]["thumb"] = $srcThumb;
            }

            return response()->json($images_base64, 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }
    public function showProjectsByType($id_Type)
    {
        try{
            $projectsByType = Project::where('id_Type_project',$id_Type)->where('id_Statut_project', '1')->get();
            foreach($projectsByType as $project) {
                $project->person = Person::findOrfail($project->id_Person);
                $project->id_PersonAgent = Person::findOrfail($project->id_PersonAgent); // "id_PersonAgent": 1
                $project->id_Type_project = Type_project::findOrfail($project->id_Type_project); // "id_Type_project": 1,
                $project->id_Statut_project = Status_project::findOrfail($project->id_Statut_project); // "id_Statut_project": 1,
                $project->id_Energy_index = Energy_index::find($project->id_Energy_index); // "id_Energy_index": 1,
                $project->id_Address = Address::find($project->id_Address); // "id_Address": 5,
                $project->option_project = Option_project::where('id_Project', $project->id)->get();
                foreach ($project->option_project as $key => $value) {
                    $project->option_project[$key]->name = Option::findOrFail($value->id_Option)->name;
                }
                $project->room_project = Room::where('id_Project', $project->id)->get();
                foreach ($project->room_project as $key => $value) {
                    $project->room_project[$key]->name = Type_room::findOrFail($value->id_Type_room)->name;
                }
                if (isset($project->id_Address)) {
                    $project->id_Address->city = City::findOrfail($project->id_Address->id_City); // "id_Address": 5,
                    $project->id_Address->department = Department::findOrfail($project->id_Address->city->id_Department);
                    $project->id_Address->region = Region::findOrfail($project->id_Address->department->id_Region);
                }
            }

            return response()->json($projectsByType, 200);
        }catch(\Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    public function showProjectsHomeView()
    {
        try {
            $projects = Project::where('id_Type_project', '2')->orWhere('id_Type_project', '3')->limit(20)->orderBy('updated_at', 'DESC')->get();
            foreach ($projects as $project) {
                $project->person = Person::findOrfail($project->id_Person);
                $project->id_PersonAgent = Person::findOrfail($project->id_PersonAgent); // "id_PersonAgent": 1
                $project->id_Type_project = Type_project::findOrfail($project->id_Type_project); // "id_Type_project": 1,
                $project->id_Statut_project = Status_project::findOrfail($project->id_Statut_project); // "id_Statut_project": 1,
                $project->id_Energy_index = Energy_index::find($project->id_Energy_index); // "id_Energy_index": 1,
                $project->id_Address = Address::find($project->id_Address); // "id_Address": 5,
                $project->option_project = Option_project::where('id_Project', $project->id)->get();
                foreach ($project->option_project as $key => $value) {
                    $project->option_project[$key]->name = Option::findOrFail($value->id_Option)->name;
                }
                $project->room_project = Room::where('id_Project', $project->id)->get();
                foreach ($project->room_project as $key => $value) {
                    $project->room_project[$key]->name = Type_room::findOrFail($value->id_Type_room)->name;
                }
                if (isset($project->id_Address)) {
                    $project->id_Address->city = City::findOrfail($project->id_Address->id_City); // "id_Address": 5,
                    $project->id_Address->department = Department::findOrfail($project->id_Address->city->id_Department);
                    $project->id_Address->region = Region::findOrfail($project->id_Address->department->id_Region);
                }
            }

            return response()->json($projects, 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }
}
