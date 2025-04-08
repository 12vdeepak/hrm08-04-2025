<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\EmployeeType;
use App\Models\Location;
use App\Models\User;
use App\Models\Title;
use App\Models\SourceOfHire;
use Illuminate\Http\Request;

class EmployeeAttributes extends Controller
{
    public function addDepartment(Request $request){
        $department=new Department();
        $department->name=$request->department;
        $department->save();
        
        $response = [
            'success' => true,
            'message' => 'Department added successfully',
            'department' => $department,
        ];
    
        // Return the response as JSON
        return response()->json($response);
    }

    public function addSourceofHire(Request $request){
        $source_of_hire=new SourceOfHire();
        $source_of_hire->name=$request->source;
        $source_of_hire->save();
        
        $response = [
            'success' => true,
            'message' => 'Source of Hire added successfully',
            'source_of_hire' => $source_of_hire,
        ];
    
        // Return the response as JSON
        return response()->json($response);
    }

    public function addLocation(Request $request){
        $location=new Location();
        $location->name=$request->location;
        $location->save();
        $response = [
            'success' => true,
            'message' => 'Location added successfully',
            'location' => $location,
        ];
    
        // Return the response as JSON
        return response()->json($response);
    }

    public function addEmployeeType(Request $request){
        $employee_type = new EmployeeType();
        $employee_type->name= $request->employee_type;
        $employee_type->save();
        $response = [
            'success' => true,
            'message' => 'Employee added successfully',
            'employee_type' => $employee_type,
        ];
    
        // Return the response as JSON
        return response()->json($response);
    }

    public function addTitle(Request $request){
        $title=new Title();
        $title->name=$request->title;
        $title->save();
        $response = [
            'success' => true,
            'message' => 'Title added successfully',
            'title' => $title,
        ];
    
        // Return the response as JSON
        return response()->json($response);
    }
}
