<?php

namespace App\Services\Company;

use App\Models\User;
use App\Models\Company;
use App\Trait\HandleResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class CompanyService
{
    use HandleResponse;

    public function createCompany($request)
    {
/
        DB::beginTransaction();

        try {

            if ($request->has('avatar')) {
                $fileName = '/companies/avatars/'.$request->name . time() . '.' . $request->avatar->extension();
                $request->avatar->move(public_path('companies/avatars'), $fileName);
            }

            if ($request->has('phone_number')) {
                $request->user()->phone_number = $request->phone_number;
                $request->user()->save();
            }

            $accountManagerPermission = Permission::where('name', 'account manager')
            ->first();
            $accountManagers = User::permission($accountManagerPermission)->get();
            
            //  select the account manager with the least number of company assignments             
            $accountManagerIds = $accountManagers->pluck('id');

            $nextAccountManager = User::whereIn('users.id', $accountManagerIds)
                ->leftJoin('companies', 'users.id', '=', 'companies.account_manager')
                ->select('users.id as user_id', DB::raw('COUNT(companies.id) as companies_count'))
                ->groupBy('users.id')
                ->orderBy('companies_count', 'asc')
                ->first();

            $record = $request->user()->ownCompanies()->create([
                'avatar' => $fileName ?? null,
                'name' => $request->name,
                'description' => $request->description,
                'hear_about_us' => $request->hear_about_us,
                'account_manager' => $nextAccountManager->user_id
            ]);

            DB::table('company_user')->insert([
                'user_id' => $request->user()->id,
                'company_id' => $record->id,
                'role' => 'admin',
            ]);

            $request->user()->givePermissionTo('admin can manage subscription');

            DB::commit();

            return $this->successResponse($record, 'Company created successfully', 201);

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse(null);
        }
    }


    public function listCompany($request)
    {
            return $this->successResponse($request->user()->ownCompanies);
    }

    public function showCompany($company)
    {
        if ($company->user_id != auth()->user()->id && in_array(auth()->user()->user_type, config( 'auth.admin_start'))) {
            return abort(401);
        };

        return $this->successResponse($company);
    }

    public function updateCompany($company, $request)
    {
        if ($company->user_id != auth()->user()->id && in_array(auth()->user()->user_type, config( 'auth.admin_start'))) {
            return abort(401);
        };

        $company = $company->fill($request->only([
            'avatar',
            'name',
            'discription' ,
            'hear_about_us',
            'phone_number',
        ]));

        $company->update();

        return $this->successResponse($company);
    }


    public function deleteCompany($company)
    {
        if ($company->user_id != auth()->user()->id && in_array(auth()->user()->user_type, config( 'auth.admin_start'))) {
            return abort(401);
        };

        $company->delete();

        return $this->successResponse($company, 'Company delete successfully');
    }


}
