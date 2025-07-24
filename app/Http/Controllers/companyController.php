<?php

namespace App\Http\Controllers;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class companyController extends BaseController
{
    public function storeCompany(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'subscription_plan' => 'required|in:Free,Pro,Enterprise',
        'users' => 'nullable|array',
        'users.*.name' => 'required_with:users|string|max:255',
        'users.*.email' => 'required_with:users|email|unique:users,email',
        'users.*.role' => 'required_with:users|in:Admin,HR,Employee',
    ]);

    
    $company = Company::create([
        'name' => $data['name'],
        'subscription_plan' => $data['subscription_plan'],
    ]);

    
    if (!empty($data['users'])) {
        foreach ($data['users'] as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => bcrypt('password123'), 
                'company_id' => $company->id,
                'role' => $userData['role'],
                'is_active' => true,
            ]);

           
            $user->assignRole($userData['role']);
        }
    }

    return response()->json([
        'message' => 'Company and users created successfully',
        'company' => $company->load('users'), 
    ]);
}
public function updateCompany(Request $request, $id)
{
    $company = Company::findOrFail($id);

    $data = $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'subscription_plan' => 'sometimes|required|in:Free,Pro,Enterprise',
        'users' => 'sometimes|array',
        'users.*.id' => 'sometimes|exists:users,id',
        'users.*.name' => 'required_with:users|string|max:255',
        'users.*.email' => 'required_with:users|email|unique:users,email,{ignore_id}',
        'users.*.role' => 'required_with:users|in:Admin,HR,Employee',
    ]);
    $company->update([
        'name' => $data['name'] ?? $company->name,
        'subscription_plan' => $data['subscription_plan'] ?? $company->subscription_plan,
    ]);

   
    if (!empty($data['users'])) {
        foreach ($data['users'] as $userData) {
            if (isset($userData['id'])) {
                $user = User::where('company_id', $company->id)->find($userData['id']);
                if ($user) {
                    if ($user->email !== $userData['email']) {
                        $request->validate([
                            'users.*.email' => 'unique:users,email',
                        ]);
                    }

                    $user->update([
                        'name' => $userData['name'],
                        'email' => $userData['email'],
                        'role' => $userData['role'],
                    ]);
                    $user->syncRoles($userData['role']); 
                }
            } else {
                
                $request->validate([
                    'users.*.email' => 'unique:users,email',
                ]);
                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => bcrypt('password123'),
                    'company_id' => $company->id,
                    'role' => $userData['role'],
                    'is_active' => true,
                ]);
                $user->assignRole($userData['role']);
            }
        }
    }

    return response()->json([
        'message' => 'Company and users updated successfully',
        'company' => $company->load('users'),
    ]);
}

public function blockCompany($id)
{
    $company = Company::findOrFail($id);
    $company->is_active = false;
    $company->save();

    return response()->json([
        'message' => 'Company blocked successfully',
        'company' => $company,
    ]);
}
}
