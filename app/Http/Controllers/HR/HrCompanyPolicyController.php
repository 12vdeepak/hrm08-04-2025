<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyPolicy;
use Illuminate\Support\Facades\Validator;
use Mail;
use App\Models\User;
use App\Events\CompanyPolicy as EventsCompanyPolicy;
use App\Notifications\companypolicy as NotificationCompanyPolicy;
use Illuminate\Support\Facades\Notification;

class HrCompanyPolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $policy = CompanyPolicy::where('id', 1)->first();
        return view('HR.company-policy.index',compact('policy'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('HR.company-policy.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required',
        ]);

        if ($validator->fails()){
            return back()->withErrors($validator);
        }

        $company_policy = new CompanyPolicy;
        $company_policy->description = $request->description;
        $company_policy->save();

        $users = User::where('role_id', '!=', 1)->get();
        foreach ($users as $user) {
            Notification::send($user, new NotificationCompanyPolicy($company_policy));
        }

        //broadcast(new EventsCompanyPolicy());
        
        return redirect()->route('company-policy.index')->with('success','Company Policy Added Successfully');
    }

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
    public function edit(CompanyPolicy $company_policy)
    {
        return view('HR.company-policy.edit',compact('company_policy'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,CompanyPolicy $company_policy)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required',
        ]);

        if ($validator->fails()){
            return back()->withErrors($validator);
        }

        $company_policy->description = $request->description;
        $company_policy->save();
        broadcast(new EventsCompanyPolicy());

        $users = User::where('role_id', '!=', 1)->get();
        foreach ($users as $user) {
            Notification::send($user, new NotificationCompanyPolicy($company_policy));
        }
        return redirect()->route('company-policy.index')->with('success','Company Policy Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyPolicy $company_policy)
    {
        $company_policy->delete();
        return redirect()->route('company_policy.index')->with('success','Company Policy Deleted Successfully');
    }
}
