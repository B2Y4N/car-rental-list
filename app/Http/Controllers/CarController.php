<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use DataTables;

class CarController extends Controller
{
    public function index(Request $request){
        if($request->ajax()) {
            $data = Car::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-secondary btn-sm">Edit</a>
                    <a href="'.route('car.destroy', ['id' => $row->id]).'" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('car');
    }

    public function show($id){
        $car = Car::find($id);
        if(!empty($car)){
            return response()->json($car);
        }else{
            return response()->json(["message" => "Car not found"], 404);
        }
    }

    public function store(Request $request){
        Car::create($request->all());
    }

    public function update(Request $request, $id){
        if(Car::where('id', $id)->exists()){
            $car = Car::find($id);
            $car->update($request->all());
        }
    }

    public function destroy($id){
        if(Car::where('id', $id)->exists()){
            $car = Car::find($id);
            $car->delete();
        }
    }
}
