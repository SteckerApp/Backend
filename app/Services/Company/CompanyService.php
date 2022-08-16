<?php

namespace App\Services\Company;

use App\Models\Company;
use App\Trait\HandleResponse;
use Illuminate\Support\Facades\DB;

class CompanyService
{
    use HandleResponse;

    public function createCompany($request)
    {
        DB::transaction();

        try {

            if ($request->has('avatar')) {
                $fileName = $request->name . time() . '.' . $request->avatar->extension();
                $request->avatar->move(public_path('companies/avatars'), $fileName);
            }

            if ($request->has('phone_number')) {
                $request->user()->phone_number = $request->phone_number;
                $request->user()->save();
            }

            $record = $request->user()->ownCompanies()->create([
                'avatar' => $fileName ?? null,
                'name' => $request->name,
                'description' => $request->description,
                'hear_about_us' => $request->hear_about_us,
            ]);


            DB::table('company_user')->insert([
                'user_id' => $request->user()->id,
                'company_id' => $record->id,
                'role' => 'admin',
            ]);

            DB::commit();

            return $this->successResponse($record, 'Company created successfully', 201);

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse(null);
        }
    }
}
