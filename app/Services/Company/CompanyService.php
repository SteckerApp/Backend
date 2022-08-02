<?php
namespace App\Services\Company;

use App\Models\Company;
use App\Trait\HandleResponse;

class CompanyService
{
    use HandleResponse;

    public function createCompany($request)
    {
        if($request->has('avatar')){
            $fileName = $request->name.time().'.'.$request->avatar->extension();  
            $request->avatar->move(public_path('companies/avatars'), $fileName);
        }

        if($request->has('phone_number')){
            $request->user()->phone_number = $request->phone_number;
            $request->user()->save();
        }

        $record = $request->user()->companies()->create([
            'avatar' => $fileName ?? null,
            'name' => $request->name,
            'description' => $request->description,
            'hear_about_us' => $request->hear_about_us,
        ]);

        return $this->successResponse($record, 'Company created successfully', 201);
        
    }
}
?>