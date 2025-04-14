<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\CompanyMaster;
use App\Models\VendorMaster;
use DateTime;
use DateInterval;

class SuperAdminController extends Controller
{

    public function getAccessToken()
    {
        $url = "https://app.blackkitetech.com/api/v2/oauth/token";

        $client_id = "569b2c06bd0847ce8b14cd825d2a2ebe";
        $client_secret = "fc4935826234479cafc648e813482834";

        $data = [
            "grant_type" => "client_credentials",
            "client_id" => $client_id,
            "client_secret" => $client_secret
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/x-www-form-urlencoded"
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return "Curl Error: " . curl_error($ch);
        }

        curl_close($ch);

        $response_data = json_decode($response, true);

        if (isset($response_data['access_token'])) {
            return $response_data['access_token'];
        } else {
            return "Error: Unable to retrieve access token";
        }
    }
    public function dashboard()
    {
        $user = Auth::user();
        $name = $user->full_name ?? 'User';

        $access_token = $this->getAccessToken();

        $url = "https://app.blackkitetech.com/api/v2/companies?page_number=1&page_size=10000";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "accept: application/json",
            "Authorization: Bearer " . $access_token
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            session()->flash('error', 'Curl Error: ' . curl_error($ch));
            return redirect()->back();
        }

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $companies = [];
        if ($http_code == 200) {
            $companies = json_decode($response, true);
            session()->flash('success', 'Companies data retrieved successfully.');
        } elseif ($http_code == 401) {
            session()->flash('error', 'Unauthorized access. Please check your credentials.');
        } elseif ($http_code == 403) {
            session()->flash('error', 'Forbidden access. You do not have permission.');
        } elseif ($http_code == 404) {
            session()->flash('error', 'API endpoint not found.');
        } else {
            session()->flash('error', 'An unexpected error occurred. HTTP Code: ' . $http_code);
        }
        return view('superadmin.dashboard', compact('name',  'companies'));
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





    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////// Report Functions /////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



    public function companySummary($cmp_id)
    {
        $url = "https://app.blackkitetech.com/api/v2/companies/{$cmp_id}/summary";
        $token = $this->getAccessToken();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            "Authorization: Bearer $token"
        ]);

        $response = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            return response()->json(['error' => curl_error($ch)], 500);
        }

        curl_close($ch);

        if ($httpCode !== 200) {
            return response()->json(['error' => 'Failed to fetch company summary', 'status' => $httpCode], $httpCode);
        }

        $summary = json_decode($response, true);

        return view('superadmin.company_summary', compact('summary', 'cmp_id'));
    }

    public function report_section(Request $request, $cmp_id)
    {
        $query_cmp_id = $request->query('cmp_id');

        if (empty($cmp_id) || $cmp_id != $query_cmp_id) {
            return redirect()->back()->with('error', 'Company ID mismatch or missing');
        }

        return view('superadmin.report_section', compact('cmp_id'));
    }

    public function widget_section(Request $request, $cmp_id)
    {
        $query_cmp_id = $request->query('cmp_id');

        if (empty($cmp_id) || $cmp_id != $query_cmp_id) {
            return redirect()->back()->with('error', 'Company ID mismatch or missing');
        }
        $token = $this->getAccessToken();


        $information_url = "https://app.blackkitetech.com/api/v2/companies/{$cmp_id}/information";
        $information_response = $this->fetchData($information_url, $token);

        $compliance_widget_url = "https://app.blackkitetech.com/api/v2/widgets/compliance-rating?companyId={$cmp_id}&widgetWidth=256";
        $compliance_widget = $this->fetchData($compliance_widget_url, $token);
        $compliance_widget_base64 = base64_encode($compliance_widget['data']);
        $data = [
            'information_response' => json_decode($information_response['data'], true),
            'compliance_widget' => $compliance_widget_base64,

        ];
        return view('superadmin.widget_section', compact('cmp_id', 'data'));
    }

    public function company_trend($cmp_id)
    {
        $url = "https://app.blackkitetech.com/api/v2/companies/{$cmp_id}/trend/technical";
        $token = $this->getAccessToken();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            "Authorization: Bearer $token"
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            return response()->json(['error' => curl_error($ch)], 500);
        }

        curl_close($ch);

        if ($httpCode !== 200) {
            return response()->json(['error' => 'Failed to fetch company trend', 'status' => $httpCode], $httpCode);
        }

        $data['techincal_trend'] = json_decode($response, true);

        $url2 = "https://app.blackkitetech.com/api/v2/companies/{$cmp_id}/trend/financial";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            "Authorization: Bearer $token"
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            return response()->json(['error' => curl_error($ch)], 500);
        }

        curl_close($ch);

        if ($httpCode !== 200) {
            return response()->json(['error' => 'Failed to fetch Financial trend', 'status' => $httpCode], $httpCode);
        }

        $data['financial_trend'] = json_decode($response, true);

        return view('superadmin.company_trend', compact('data'));
    }

    public function digital_footprints($cmp_id)
    {
        $token = $this->getAccessToken();

        // Fetch Domains
        $domains_url = "https://app.blackkitetech.com/api/v2/companies/{$cmp_id}/domains?page_number=1&page_size=10000&classification=active&status=active";
        $domains_response = $this->fetchData($domains_url, $token);

        $subdomains_url = "https://app.blackkitetech.com/api/v2/companies/{$cmp_id}/subdomains?page_number=1&page_size=250&status=active";
        $subdomains_response = $this->fetchData($subdomains_url, $token);

        $dns_url = "https://app.blackkitetech.com/api/v2/companies/{$cmp_id}/dnsrecords/IpAddress?page_number=1&page_size=250";
        $dns_response = $this->fetchData($dns_url, $token);

        $social_url = "https://app.blackkitetech.com/api/v2/companies/{$cmp_id}/socialmedia?page_number=1&page_size=250";
        $social_response = $this->fetchData($social_url, $token);

        $email_url = "https://app.blackkitetech.com/api/v2/companies/{$cmp_id}/emails?page_number=1&page_size=20";
        $email_response = $this->fetchData($email_url, $token);

        $information_url = "https://app.blackkitetech.com/api/v2/companies/{$cmp_id}/information";
        $information_response = $this->fetchData($information_url, $token);


        $cloud_url = "https://app.blackkitetech.com/api/v2/companies/{$cmp_id}/clouds?page_number=1&page_size=250";
        $cloud_response = $this->fetchData($cloud_url, $token);

        $data = [
            'domains' => json_decode($domains_response['data'], true),
            'subdomains' => json_decode($subdomains_response['data'], true),
            'dns_records' => json_decode($dns_response['data'], true),
            'social_records' => json_decode($social_response['data'], true),
            'email_records' => json_decode($email_response['data'], true),
            'information_records' => json_decode($information_response['data'], true),
            'cloud_records' => json_decode($cloud_response['data'], true)
        ];

        return view('superadmin.company_domains', compact('data', 'cmp_id'));
    }

    /**
     * Helper function to fetch API data using cURL
     */
    private function fetchData($url, $token)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            "Authorization: Bearer $token"
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            curl_close($ch);
            return ['success' => false, 'status' => 500, 'data' => curl_error($ch)];
        }

        curl_close($ch);

        return ['success' => $httpCode === 200, 'status' => $httpCode, 'data' => $response];
    }

    public function findings(Request $request, $cmp_id)
    {
        $token = $this->getAccessToken();

        $current_date = new DateTime();
        $start_date = $current_date->sub(new DateInterval('P15D'))->format('Y-m-d\TH:i:s\Z'); // 15 days ago
        $end_date = (new DateTime())->format('Y-m-d\TH:i:s\Z'); // Current date

        $findings_url = "https://app.blackkitetech.com/api/v2/companies/{$cmp_id}/findings?page_number=1&page_size=5000&start_date={$start_date}&end_date={$end_date}&type=All";
        $findings = $this->fetchData($findings_url, $token);

        $decodedFindings = json_decode($findings['data'], true);

        // Group findings into categories
        $categories = [
            'All Findings',
            'Data Breach',
            'Ransomware',
            'Fraudulent Domain',
            'Patch Management',
            'Application Security',
        ];

        $data = [
            'findings' => $decodedFindings,
            'categories' => $categories,
        ];

        return view('superadmin.findings', compact('data', 'cmp_id'));
    }


    public function data_breach_finding(Request $request)
    {
        $token = $this->getAccessToken();

        // Step 1: Fetch the initial data breach findings
        $data_breach_url = "https://app.blackkitetech.com/api/v2/companies/{$request->cmp_id}/findings/databreach?page_number=1&page_size=5000";
        $data_breach_findings = $this->fetchData($data_breach_url, $token);

        // Step 2: Check if response contains valid data
        if (!isset($data_breach_findings['data'])) {
            return response()->json(['error' => 'Invalid data from API'], 500);
        }

        // Decode the data field (JSON string) into an array
        $findings_data = json_decode($data_breach_findings['data'], true);

        if (!is_array($findings_data)) {
            return response()->json(['error' => 'Failed to decode findings data'], 500);
        }

        $detailed_findings = [];

        // Step 3: Loop through each finding and fetch detailed data
        foreach ($findings_data as $finding) {
            if (isset($finding['Url'])) {
                $detailed_data = $this->fetchData($finding['Url'], $token);
                $detailed_data_array = is_array($detailed_data) ? $detailed_data : json_decode($detailed_data, true);

                if ($detailed_data_array) {
                    $detailed_findings[] = $detailed_data_array; // Collect detailed data
                }
            }
        }

        // Step 4: Return all detailed findings
        return response()->json(['data' => $detailed_findings]);
    }

    public function ransomware_findings(Request $request)
    {
        $token = $this->getAccessToken();

        dd($request->cmp_id);
        // Step 1: Fetch the initial ransomware findings
        $ransomware_url = "https://app.blackkitetech.com/api/v2/companies/{$request->cmp_id}/findings/ransomware?page_number=1&page_size=5000";
        $ransomware_findings = $this->fetchData($ransomware_url, $token);

        // Step 2: Check if response contains valid data
        if (!isset($ransomware_findings['data'])) {
            return response()->json(['error' => 'Invalid data from API'], 500);
        }

        // Decode the data field (JSON string) into an array
        $findings_data = json_decode($ransomware_findings['data'], true);
        dd($findings_data);
        if (!is_array($findings_data)) {
            return response()->json(['error' => 'Failed to decode findings data'], 500);
        }

        $detailed_findings = [];

        // Step 3: Loop through each finding and fetch detailed data
        foreach ($findings_data as $finding) {
            if (isset($finding['Url'])) {
                $detailed_data = $this->fetchData($finding['Url'], $token);
                $detailed_data_array = is_array($detailed_data) ? $detailed_data : json_decode($detailed_data, true);

                if ($detailed_data_array) {
                    $detailed_findings[] = $detailed_data_array; // Collect detailed data
                }
            }
        }

        // Step 4: Return all detailed findings
        return response()->json(['data' => $detailed_findings]);
    }



    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////// Report Functions /////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
