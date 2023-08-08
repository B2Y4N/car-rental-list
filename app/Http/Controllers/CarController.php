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
                    $btn = '<button class="edit btn btn-secondary btn-sm" onclick="editCar('.$row->id.')">Edit</button>
                    <button class="delete btn btn-danger btn-sm" onclick="deleteCar('.$row->id.')">Delete</button>';
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

        return redirect()->route('car.index');
    }

    public function update(Request $request, $id){
        if(Car::where('id', $id)->exists()){
            $car = Car::find($id);
            // $car->update($request->all());
            $car->carPlate = $request->editCarPlate;
            $car->colour = $request->editColour;
            $car->propellant = $request->editPropellant;
            $car->seats = $request->editSeats;
            $car->expiryDate = $request->editExpiryDate;
            $car->save();
        }

        return redirect()->route('car.index');
    }

    public function destroy($id){
        if(Car::where('id', $id)->exists()){
            $car = Car::find($id);
            $car->delete();
        }

        return redirect()->route('car.index');
    }
}
