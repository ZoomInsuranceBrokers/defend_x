<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\CompanyMaster;
use App\Models\VendorMaster;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $name = $user->full_name ?? 'User';

        return view('superadmin.dashboard', compact('name'));
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////// Company Functions /////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function company_details()
    {
        $data['companies'] = CompanyMaster::get();

        $data['user'] = Auth::user();

        return view('superadmin.company_details', compact('data'));
    }

    public function add_company()
    {
        $data['user'] = Auth::user();

        return view('superadmin.add_company', compact('data'));
    }

    public function add_company_post(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'comp_name' => 'required|string|max:100',
                'business_type' => 'required|string|max:255',
                'comp_addr' => 'required|string|max:200',
                'comp_city' => 'required|string|max:45',
                'comp_state' => 'required|string|max:45',
                'comp_pincode' => 'required|string|max:45',
                'comp_logo' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $fileDir = null;
            $compIconUrl = null;

            if ($request->hasFile('comp_logo')) {
                $file = $request->file('comp_logo');

                $uniqueDirName = preg_replace('/\s+/', '_', strtolower($validatedData['comp_name']));
                $directory = 'logos/' . $uniqueDirName;

                $filePath = $file->store($directory, 'public');

                $fileDir = $directory;
                $compIconUrl = 'storage/' . $filePath;
            }

            CompanyMaster::create([
                'comp_name' => $validatedData['comp_name'],
                'business_type' => $validatedData['business_type'],
                'comp_addr' => $validatedData['comp_addr'],
                'comp_city' => $validatedData['comp_city'],
                'comp_state' => $validatedData['comp_state'],
                'comp_pincode' => $validatedData['comp_pincode'],
                'file_dir' => $fileDir,
                'comp_icon_url' => $compIconUrl,
                'status' => 1,
                'is_approved' => 0,
                'updated_by' => auth()->id(),
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('superadmin.company_details')->with('success', 'Company added successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error adding company: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again.');
        }
    }

    public function edit_company($id)
    {
        try {
            $decodedId = base64_decode(string: $id);
            $data['company'] = CompanyMaster::findOrFail($decodedId);

            $data['user'] = Auth::user();
            return view('superadmin.edit_company', compact('data'));
        } catch (\Exception $e) {
            \Log::error('Error loading edit company form: ' . $e->getMessage());
            return redirect()->route('superadmin.company_details')->with('error', 'Company not found!');
        }
    }

    public function update_company(Request $request, $id)
    {
        $request->validate([
            'comp_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'comp_addr' => 'required|string|max:255',
            'comp_pincode' => 'required|numeric',
            'comp_city' => 'required|string|max:255',
            'comp_state' => 'required|string|max:255',
            'comp_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|string|max:255',
        ]);

        $company = CompanyMaster::findOrFail($id);

        $company->comp_name = $request->comp_name;
        $company->business_type = $request->business_type;
        $company->comp_addr = $request->comp_addr;
        $company->comp_pincode = $request->comp_pincode;
        $company->comp_city = $request->comp_city;
        $company->comp_state = $request->comp_state;
        $company->status = $request->status === '1' ? 1 : 0;

        if ($request->hasFile('comp_logo')) {
            $file = $request->file('comp_logo');

            $directory = storage_path('app/public/' . $company->file_dir);

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($directory, $filename);

            $company->comp_icon_url = 'storage/' . $company->file_dir . '/' . $filename;
        }

        $company->save();

        return redirect()->route('superadmin.company_details')->with('success', 'Company updated successfully.');
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////// Company Functions /////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////  User Functions /////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function user_details()
    {
        $data['users'] = User::whereNot('role_id', 1)
            ->with('company')
            ->with('role')
            ->orderBy('created_at', 'desc')
            ->get();

        $data['user'] = Auth::user();

        return view('superadmin.user_details', compact('data'));
    }

    public function add_user()
    {
        $data['user'] = Auth::user();

        $data['companies'] = CompanyMaster::where('status', 1)->get();

        return view('superadmin.add_user', compact('data'));
    }

    public function add_user_post(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'gender' => 'required|string|in:male,female',
                'email' => 'required|email|unique:users,email|max:255',
                'mobile' => 'required|digits:10|unique:users,mobile',
                'role' => 'required|integer|in:2,3',
                'company' => 'required',
            ]);

            $fullName = $validatedData['first_name'] . ' ' . $validatedData['last_name'];

            $user = User::create([
                'role_id' => (int) $validatedData['role'],
                'company_id' => (int) $validatedData['company'],
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'full_name' => $fullName,
                'gender' => $validatedData['gender'],
                'email' => $validatedData['email'],
                'mobile' => $validatedData['mobile'],
                'password' => Hash::make('password'),
                'photo' => 'assets/images/faces/face5.jpg',
                'is_active' => 1,
                'is_delete' => 0,
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('superadmin.user_details')->with('success', 'User added successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error adding user: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit_user($id)
    {
        try {
            $decodedId = base64_decode(string: $id);
            $data['users'] = User::findOrFail($decodedId);

            $data['user'] = Auth::user();

            $data['companies'] = CompanyMaster::where('status', 1)->get();

            return view('superadmin.edit_user', compact('data'));
        } catch (\Exception $e) {
            \Log::error('Error loading edit user form: ' . $e->getMessage());
            return redirect()->route('superadmin.user_details')->with('error', 'User not found!');
        }
    }

    public function update_user(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile' => 'required|numeric|digits:10',
            'company' => 'required|exists:company_master,comp_id',
            'role' => 'required|in:2,3',
            'is_active' => 'required|in:0,1',
        ]);

        $user = User::findOrFail($id);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->mobile = $request->mobile;
        $user->company_id = $request->company;
        $user->role_id = $request->role;
        $user->is_active = $request->is_active;

        $user->save();

        return redirect()->route('superadmin.user_details')->with('success', 'User updated successfully.');
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////// User Functions /////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////// Vendor Functions /////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function vendor_details()
    {
        $data['vendors'] = VendorMaster::orderBy('created_at', 'desc')
            ->get();

        $data['user'] = Auth::user();

        return view('superadmin.vendor_details', compact('data'));
    }

    public function add_vendor()
    {
        $data['user'] = Auth::user();

        return view('superadmin.add_vendor', compact('data'));
    }

    public function add_vendor_post(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'vendor_name' => 'required|string|max:100',
                'support_email' => 'required|email|max:255',
                'support_mobile' => 'required|string|max:15',
                'address' => 'required|string|max:500',
                'logo' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $fileDir = null;
            $logoUrl = null;

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');

                $uniqueDirName = preg_replace('/\s+/', '_', subject: strtolower($validatedData['vendor_name']));
                $directory = 'logos/' . $uniqueDirName;

                $filePath = $file->store($directory, 'public');

                $logoUrl = 'storage/' . $filePath;
            }

            VendorMaster::create([
                'vendor_name' => $validatedData['vendor_name'],
                'support_email' => $validatedData['support_email'],
                'support_mobile' => $validatedData['support_mobile'],
                'address' => $validatedData['address'],
                'logo' => $logoUrl,
            ]);

            return redirect()->route('superadmin.vendor_details')->with('success', 'Vendor added successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error adding vendor: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again.');
        }
    }

    public function edit_vendor($id)
    {
        try {
            $decodedId = base64_decode(string: $id);
            $data['vendor'] = VendorMaster::findOrFail($decodedId);

            $data['user'] = Auth::user();

            return view('superadmin.edit_vendor', compact('data'));
        } catch (\Exception $e) {
            \Log::error('Error loading edit user form: ' . $e->getMessage());
            return redirect()->route('superadmin.user_details')->with('error', 'Vendor not found!');
        }
    }

    public function update_vendor(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'vendor_name' => 'required|string|max:100',
                'support_email' => 'required|email|max:255',
                'support_mobile' => 'required|string|max:15',
                'address' => 'nullable|string|max:500',
                'logo' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'required|in:0,1',
            ]);

            $vendor = VendorMaster::findOrFail($id);

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $uniqueDirName = preg_replace('/\s+/', '_', strtolower($validatedData['vendor_name']));
                $directory = 'logos/vendors/' . $uniqueDirName;
                $filePath = $file->store($directory, 'public');

                if ($vendor->logo) {
                    Storage::disk('public')->delete($vendor->logo);
                }

                $validatedData['logo'] = 'storage/' . $filePath;
            } else {
                unset($validatedData['logo']);
            }

            $vendor->update($validatedData);

            return redirect()->route('superadmin.vendor_details')->with('success', 'Vendor updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error updating vendor: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again.');
        }
    }


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////// Vendor Functions /////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
