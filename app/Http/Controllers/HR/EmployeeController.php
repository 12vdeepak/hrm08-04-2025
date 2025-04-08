<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Mail\SendCredentialsToEmployee;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Location;
use App\Models\Title;
use App\Models\User;
use App\Models\EmployeeType;
use App\Models\SourceOfHire;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;



class EmployeeController extends Controller
{

    /**
     * Display a listing of employees excluding role_id = 1.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $employees = User::whereNotIn('role_id', [1])->where('employee_status',1)->orderBy('id', 'desc')->paginate(10);
        return view('HR.employee.activeindex', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $departments = Department::all();
        $locations = Location::all();
        $sources = SourceOfHire::all();
        $titles = Title::all();
        $types = EmployeeType::all();
        $reporting_managers = User::where('role_id', '!=', 1)->get();
        return view('HR.employee.add', compact('departments', 'locations', 'sources', 'titles', 'types', 'reporting_managers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|unique:users,email',
            'Department' => 'required',
            'locations' => 'required',
            'Source' => 'required',
            'Title' => 'required',
            'date' => 'required',
            'employee_status' => 'required',
            'Type' => 'required',
            'role' => 'required',
            'experience' => 'required',
            'working_hours' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $raw_token = random_bytes(32);
        $token_to_set_password = bin2hex($raw_token);
        $password = Str::random(10);
        $user = new User;
        $user->name = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->work_phone = $request->work_phone;
        $user->title_id = $request->title;
        $user->department_id = $request->Department;
        $user->location_id = $request->locations;
        $user->title_id = $request->Title;
        $user->source_hire = $request->Source;
        $user->date_of_joining = $request->date;
        $user->employee_status = $request->employee_status;
        $user->employee_type_id = $request->Type;
        $user->role_id = $request->role;
        $user->reporting_to = $request->reporting_to;
        $user->experience = $request->experience;
        $user->address = $request->address;
        $user->other_email = $request->other_email;
        $user->token_to_set_password = $token_to_set_password;
        $user->working_hours = $request->working_hours;
        $user->view_password = $password;
        $user->password_set = 0;
        $user->password = Hash::make($password);
        $user->save();

        $link = url(route('user_register', ['token' => $token_to_set_password]));
        if($user->role_id == 2){
            $url=url('hr_login');
        }
        else{
            $url=url('user_login');
        }
        $data = [
            'name' => $request->firstname, 
            'email' => $user->email,
            'password' => $password,
            'link' => $link,
            'url' => $url
        ];

      Mail::to($user->email)->send(new SendCredentialsToEmployee($data));


        return redirect()->route('employee.index')->with('success', 'Employee Added Successfully');
    }
    
    
//     public function store(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'firstname' => 'required',
//         'lastname' => 'required',
//         'email' => 'required|unique:users,email',
//         'Department' => 'required',
//         'locations' => 'required',
//         'Source' => 'required',
//         'Title' => 'required',
//         'date' => 'required',
//         'employee_status' => 'required',
//         'Type' => 'required',
//         'role' => 'required',
//         'experience' => 'required',
//         'working_hours' => 'required',
//         'phone' => 'required',
//         'address' => 'required',
//     ]);

//     if ($validator->fails()) {
//         return redirect()->back()
//             ->withErrors($validator)
//             ->withInput();
//     }

//     try {
//         $raw_token = random_bytes(32);
//         $token_to_set_password = bin2hex($raw_token);
//         $password = Str::random(10);
//         $user = new User;
//         $user->name = $request->firstname;
//         $user->lastname = $request->lastname;
//         $user->email = $request->email;
//         $user->phone = $request->phone;
//         $user->work_phone = $request->work_phone;
//         $user->title_id = $request->title;
//         $user->department_id = $request->Department;
//         $user->location_id = $request->locations;
//         $user->title_id = $request->Title;
//         $user->source_hire = $request->Source;
//         $user->date_of_joining = $request->date;
//         $user->employee_status = $request->employee_status;
//         $user->employee_type_id = $request->Type;
//         $user->role_id = $request->role;
//         $user->reporting_to = $request->reporting_to;
//         $user->experience = $request->experience;
//         $user->address = $request->address;
//         $user->other_email = $request->other_email;
//         $user->token_to_set_password = $token_to_set_password;
//         $user->working_hours = $request->working_hours;
//         $user->view_password = $password;
//         $user->password_set = 0;
//         $user->password = Hash::make($password);
//         $user->save();

//         Log::info('User created successfully', ['user_id' => $user->id, 'email' => $user->email]);

//         $link = url(route('user_register', ['token' => $token_to_set_password]));
//         $url = ($user->role_id == 2) ? url('hr_login') : url('user_login');
//         $data = [
//             'name' => $request->firstname, 
//             'email' => $user->email,
//             'password' => $password,
//             'link' => $link,
//             'url' => $url
//         ];

//         try {
//             Mail::to($user->email)->send(new SendCredentialsToEmployee($data));
//             Log::info('Email sent successfully', ['email' => $user->email]);
//         } catch (\Exception $mailException) {
//             Log::error('Error sending email', ['error' => $mailException->getMessage()]);
//             return redirect()->route('employee.index')->with('warning', 'Employee added, but email sending failed.');
//         }

//     } catch (\Exception $e) {
//         Log::error('Error creating user', ['error' => $e->getMessage()]);
//         return redirect()->back()->with('error', 'There was an error processing your request. Please try again.');
//     }

//     return redirect()->route('employee.index')->with('success', 'Employee Added Successfully');
// }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $employee)
    {
        $departments = Department::all()->sortBy('name');
        $locations = Location::all()->sortBy('name');
        $sources = SourceOfHire::all()->sortBy('name');
        $titles = Title::all()->sortBy('name');
        $types = EmployeeType::all()->sortBy('name');
        $reporting_managers = User::where('role_id', '!=', 1)->get()->sortBy('name');
        return view('HR.employee.edit', compact('employee', 'departments', 'locations', 'sources', 'titles', 'types', 'reporting_managers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $employee)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'email' => 'required',
            'lastname' => 'required',
            'password' => 'required',
            'Department' => 'required',
            'locations' => 'required',
            'Source' => 'required',
            'Title' => 'required',
            'date' => 'required',
            'employee_status' => 'required',
            'Type' => 'required',
            'role' => 'required',
            'experience' => 'required',
            'working_hours' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $employee->name = $request->firstname;
        $employee->lastname = $request->lastname;
        $employee->phone = $request->phone;
        $employee->email = $request->email;
        $employee->work_phone = $request->work_phone;
        $employee->title_id = $request->title;
        $employee->department_id = $request->Department;
        $employee->location_id = $request->locations;
        $employee->title_id = $request->Title;
        $employee->reporting_to = $request->reporting_to;
        $employee->source_hire = $request->Source;
        $employee->date_of_joining = $request->date;
        $employee->employee_status = $request->employee_status;
        $employee->employee_type_id = $request->Type;
        $employee->role_id = $request->role;
        $employee->experience = $request->experience;
        $employee->address = $request->address;
        $employee->other_email = $request->other_email;
        $employee->working_hours = $request->working_hours;
        $employee->hr_remark = $request->hr_remark;
        $employee->view_password = $request->password;
        $employee->password = Hash::make($request->password);
        $employee->save();  
        if($employee->employee_status==1){
            return redirect()->route('employee.index')->with('success', 'Employee Updated Successfully');
        }
        else {
            return redirect()->route('employee.inactive')->with('success', 'Employee Updated Successfully');
        }   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $employee)
    {
        $employee->delete();
        return redirect()->route('employee.index')->with('success', 'Employee Deleted Successfully');
    }

    public function indexinactive()
    { 
        $employees = User::whereNotIn('role_id', [1])->where('employee_status', 0)->orderBy('id', 'desc')->paginate(10);
        return view('HR.employee.inactiveindex', compact('employees'));
    }
}
