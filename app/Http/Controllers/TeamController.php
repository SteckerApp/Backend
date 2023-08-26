<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminTeamResource;
use App\Models\User;
use App\Models\Invite;
use App\Models\Company;
use App\Mail\InvitationMail;
use App\Models\CompanyUser;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TeamController extends Controller
{
    use HandleResponse;

    public function index()
    {

        $teams = Company::whereId(getActiveWorkSpace()->id)
            ->with('users')
            ->get();

        return $this->successResponse($teams);
    }

    public function getAdminTeam()
    {

        $teams = User::where('user_type', 'admin')->get();

        $teams = AdminTeamResource::collection($teams);

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

        Invite::updateOrCreate(
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

        $this->sendInvitationMail($request->email, $request->name, getActiveWorkSpace()->name, $request->user()->first_name);

        return $this->successResponse(null, 'Invitation mail sends successfully');
    }


    protected function sendInvitationMail($email, $name, $company, $owner)
    {
        $mail = Mail::to($email);
        // send mail of verification code
        if (env('APP_SYSTEM_STACK') == 'queue') {
            $mail->queue(new InvitationMail($name, $company, $owner));
        } else {
            $mail->send(new InvitationMail($name, $company, $owner));
        }
    }
}
