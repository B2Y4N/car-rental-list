<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use DataTables;
use Carbon\Carbon;

class CarController extends Controller
{
    public function index(Request $request){
        if($request->ajax()) {
            // only get data with expiry dates that are one month or less away from the date of querying
            $startDate = Carbon::now();
            $endDate = $startDate->copy()->addMonth();

            // if got custom date filter, change startDate and endDate
            if(!empty($request->startDate)){
                $startDate = $request->startDate;
            }

            if(!empty($request->endDate)){
                $endDate = $request->endDate;
            }

            $data = Car::whereDate('expiryDate', '>=', $startDate)
                ->whereDate('expiryDate', '<=', $endDate)
                ->orderBy('expiryDate', 'asc')
                ->get();

            // $highlightedRows = [];

            // foreach($data as $index => $row){
            //     $expiryDate = Carbon::parse($row->expiryDate);
            //     $twoWeeksLater = now()->addWeeks(2);

            //     if($expiryDate->isBetween(now(), $twoWeeksLater)){
            //         $highlightedRows[] = $index;
            //     }
            // }

            // dd($highlightedRows);
            
            return DataTables::of($data)
                ->addIndexColumn()
                // ->addColumn('highlight', function($row) use($highlightedRows){
                //     return in_array($row->DT_RowIndex, $highlightedRows);
                // })
                ->addColumn('action', function($row){
                    $btn = '<button class="edit btn btn-secondary btn-sm" onclick="editCar('.$row->id.')">Edit</button>
                    <button class="delete btn btn-danger btn-sm" onclick="deleteCar('.$row->id.', \''.$row->carPlate.'\')">Delete</button>';
                    return $btn;
                })
                ->rawColumns(['highlight', 'action'])
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
        // validate input
        $validated = $request->validate([
            'carPlate' => ['required', 'regex:/^[A-Z]{3}[\d]{3,4}[A-Z]{1}$/'],
            'colour' => 'required',
            'propellant' => 'required',
            'seats' => 'required',
            'expiryDate' => ['required', 'date'],
        ]);

        Car::create($request->all());

        return redirect()->route('car.index');
    }

    public function update(Request $request, $id){
        // validate input
        $validated = $request->validate([
            'editCarPlate' => ['required', 'regex:/^[A-Z]{3}[\d]{3,4}[A-Z]{1}$/'],
            'editColour' => 'required',
            'editPropellant' => 'required',
            'editSeats' => 'required',
            'editExpiryDate' => ['required', 'date'],
        ]);

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
