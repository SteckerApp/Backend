<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminTeamResource;
use App\Mail\AdminInvitationMail;
use App\Models\User;
use App\Models\Invite;
use App\Models\Company;
use App\Mail\InvitationMail;
use App\Models\CompanyUser;
use App\Models\ProjectRequest;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TeamController extends Controller
{
    use HandleResponse;

    public function index()
    {

        if(request()->company_id){
            $company_id =  request()->company_id;
        }
        else{
            $company_id = getActiveWorkSpace()->id;
        }

        $teams = Company::whereId($company_id)
            ->with('users.roles')
            ->first();

        return $this->successResponse($teams);
    }

    public function getAdminTeam()
    {

        $teams = User::where('user_type', 'admin', 'roles')->get();

        $teams = AdminTeamResource::collection($teams);

        return $this->successResponse($teams);
    }

    public function removeAdminTeammember(Request $request)
    {

        $teams = User::whereId($request->user_id)->first();

        $teams->user_type = "client";

        $teams->save();

        return $this->successResponse($teams);
    }



    public function show($id)
    {
        $invite = Invite::where('id', $id)->firstOrFail();

        return $this->successResponse($invite);
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'permission' => 'required|array|min:1',
            'permission.*' => 'sometimes|string|exists:permissions,name'
        ]);

        $request->user()->syncPermissions($request->permission);

        return $this->successResponse(null, 'team member permission updated successfully');
    }


    public function delete($id)
    {

        DB::table('company_user')->where('company_id', getActiveWorkSpace()->id)
            ->where('user_id', $id)->delete();
        return $this->successResponse(null, 'member deleted successfully!');
    }


    public function check(Request $request, $id)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:255|exists:invites,email'
        ]);

        $check = Invite::where('id', $id)->where(['status' => 'pending', 'email' => $request->email])->exists();

        return $this->successResponse(['status' => $check]);
    }

    public function link(Request $request, $id)
    {
        $check = Invite::where('id', $id)->where(['status' => 'pending', 'email' => $request->email, 'platform' => 'registered'])->first();

        if ($check) {
            $user = User::whereEmail($check->email)->firstOrFail();

            // $user->assignRole('client_team_member');

            CompanyUser::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'company_id' => $check->company_id,
                ],
                [
                    'role' => 'member',
                ]
            );

            $check->update([
                'status' => 'joined'
            ]);


            return $this->successResponse([null, 'User added to company workspace successfully']);
        } else {
            return $this->errorResponse(null, 'invitation not found', 404);
        }
    }


    public function invite(Request $request)
    {
        $this->validate($request, [
            'name' => 'sometimes|string',
            'email' => 'required|email',
            'role' => 'required|string',
            'preset' => 'sometimes|array',
            'preset.*' => 'sometimes|string|exists:permissions,name'
        ]);

        $emailCheck = Company::whereId(getActiveWorkSpace()->id)->first()->users()->where('email', $request->email)->exists();

        if ($emailCheck) {
            return $this->errorResponse(null, 'User already added to this workspace');
        }

        $register = (User::where('email', $request->email)->exists()) ?  'registered' : 'new-user';

       $invite = Invite::updateOrCreate(
            [
                'email' => $request->email,
                'company_id' => getActiveWorkSpace()->id,
            ],
            [
                'name' => $request->name,
                'role' => $request->role,
                'invite_by' => $request->user()->id,
                'status' => 'pending',
                'preset' => $request->preset,
                'platform' => $register
            ]
        );

        $this->sendInvitationMail($request->email, $request->name, getActiveWorkSpace()->name, $request->user()->first_name, 'client', $invite);

        return $this->successResponse(null, 'Invitation mail sent successfully');
    }


    protected function sendInvitationMail($email, $name, $company, $owner, $type, $invite)
    {
        $mail = Mail::to($email);
        // send mail of verification code
        if (env('APP_SYSTEM_STACK') == 'queue') {
            $mail->queue(new InvitationMail($name, $company, $owner, $type, $invite));
        } else {
            $mail->send(new InvitationMail($name, $company, $owner, $type, $invite));
        }
    }

    protected function inviteAdmin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'role' => 'required|string|exists:roles,name'
        ]);

        $emailCheck = User::where('email', $request->email)->exists();

        if ($emailCheck) {
            return $this->errorResponse(null, 'User already has an account with email address');
        }

        $register = (User::where('email', $request->email)->exists()) ?  'registered' : 'new-user';

        $invite = Invite::updateOrCreate(
            [
                'email' => $request->email,
            ],
            [
                'name' => 'Stecker Admin',
                'role' => $request->role,
                'invite_by' => $request->user()->id,
                'status' => 'pending',
                'type' => 'admin'
            ]
        );

        $this->sendInvitationMail($request->email, 'Stecker Admin', 'Stecker', $request->user()->first_name, 'admin', $invite);


        return $this->successResponse(null, 'Invitation mail sent successfully');

    }
}
