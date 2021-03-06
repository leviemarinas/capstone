<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\VehicleMake;
use App\VehicleModel;
use Validator;
use Redirect;
use Session;
use DB;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $makes = VehicleMake::where('isActive',1)->get();
        $deactivate = VehicleMake::where('isActive',0)->get();
        return View('vehicle.index',compact('makes','deactivate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('vehicle.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:vehicle_make|max:50',
            'model.*' => 'required|max:50',
            'year.*' => 'required'
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'name' => 'Vehicle Make',
            'model.*' => 'Vehicle Model',
            'year.*' => 'Vehicle Year',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $make = VehicleMake::create([
                    'name' => trim($request->name),
                    'isActive' => 1
                ]);
                $models = $request->model;
                $years = $request->year;
                $autos = $request->hasAuto;
                $manuals = $request->hasManual;
                foreach ($models as $key=>$model) {
                    VehicleModel::updateOrCreate(
                        ['makeId' => $make->id,
                        'name' => $model,
                        'year' => $years[$key],
                        'hasAuto' => $autos[$key],
                        'hasManual' => $manuals[$key],
                        ],
                        ['isActive' => 1]
                    );
                }
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors("Oops! This has not been developed yet");
            }
            $request->session()->flash('success', 'Successfully added.');  
            return Redirect('vehicle');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return View('layouts.404');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $make = VehicleMake::findOrFail($id);
        return View('vehicle.edit',compact('make'));
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
        $rules = [
            'name' => ['required','max:50',Rule::unique('vehicle_make')->ignore($id)],
            'model.*' => 'required|max:50',
            'year.*' => 'required'
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'name' => 'Vehicle Make',
            'model.*' => 'Vehicle Model',
            'year.*' => 'Vehicle Year',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        else{
            try{
                DB::beginTransaction();
                $make = VehicleMake::findOrFail($id);
                $make->update([
                    'name' => trim($request->name),
                ]);
                $make = VehicleMake::all()->last();
                VehicleModel::where('makeId',$id)->update(['isActive'=>0]);
                $models = $request->model;
                $years = $request->year;
                $autos = $request->hasAuto;
                $manuals = $request->hasManual;
                foreach ($models as $key=>$model) {
                    VehicleModel::updateOrCreate(
                        ['makeId' => $make->id,
                        'name' => $model,
                        'year' => $years[$key],
                        'hasAuto' => $autos[$key],
                        'hasManual' => $manuals[$key],
                        ],
                        ['isActive' => 1]
                    );
                }
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors("Oops! This has not been developed yet");
            }
            $request->session()->flash('success', 'Successfully updated.');  
            return Redirect('vehicle');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $checkProduct = DB::table('product_vehicle as pv')
                ->join('vehicle_model as vd','vd.id','pv.modelId')
                ->join('vehicle_make as vk','vk.id','vd.makeId')
                ->where('pv.isActive',1)
                ->where('vk.id',$id)
                ->get();
            $checkVehicle = DB::table('vehicle as v')
                ->join('vehicle_model as vd','vd.id','v.modelId')
                ->join('vehicle_make as vk','vk.id','vd.makeId')
                ->where('vk.id',$id)
                ->get();
            if(count($checkProduct) > 0 || count($checkVehicle) > 0){
                $request->session()->flash('error', 'It seems that the record is still being used in other items. Deactivation failed.');
            }else{
                $vehicle = VehicleMake::findOrFail($id);
                $vehicle->update([
                    'isActive' => 0
                ]);
                VehicleModel::where('makeId',$id)->update(['isActive'=>0]);
                $request->session()->flash('success', 'Successfully deactivated.');  
            }   
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            $errMess = $e->getMessage();
            return Redirect::back()->withErrors("Oops! This has not been developed yet");
        }
        return Redirect('vehicle');
    }
    
    public function reactivate(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $vehicle = VehicleMake::findOrFail($id);
            $vehicle->update([
                'isActive' => 1
            ]);
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            $errMess = $e->getMessage();
            return Redirect::back()->withErrors("Oops! This has not been developed yet");
        }
        $request->session()->flash('success', 'Successfully reactivated.');  
        return Redirect('vehicle');
    }

    public function remove(Request $request, $id){
        $checkProduct = DB::table('product_vehicle as pv')
            ->join('vehicle_model as vd','vd.id','pv.modelId')
            ->join('vehicle_make as vk','vk.id','vd.makeId')
            ->where('pv.isActive',1)
            ->where('pv.modelId',$id)
            ->get();
        $checkVehicle = DB::table('vehicle as v')
            ->join('vehicle_model as vd','vd.id','v.modelId')
            ->join('vehicle_make as vk','vk.id','vd.makeId')
            ->where('v.modelId',$id)
            ->get();
        if(count($checkProduct) > 0 || count($checkVehicle) > 0){
            return response()->json(['message'=>'It seems that the record is still being used in other items. Discarding failed.']);
        }else{
            return response()->json(['message'=>0]);
        }
    }
}
