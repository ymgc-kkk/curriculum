<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Address;

class CompanyController extends Controller

{
    private $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = $this->company->all();  
        return ['companies' => $companies]; 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        DB::transaction(function () use ($request) {
            // 親テーブルの登録
            $company = $this->company->create([
                'company' => $request->company,
                'company_ruby' => $request->company_ruby,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'ceo' => $request->ceo,
                'ceo_ruby' => $request->ceo_ruby
            ]);
    
            // 子テーブルの登録
            $addresses = $request->input('addresses', []);
    
            foreach ($addresses as $addressData) {
                Address::create([
                    'company_id' => $company->id,
                    'billing' => $addressData['billing'],
                    'billing_ruby' => $addressData['billing_ruby'],
                    'address' => $addressData['address'],
                    'phone_number' => $addressData['phone_number'],
                    'department' => $addressData['department'],
                    'to' => $addressData['to'],
                    'to_ruby' => $addressData['to_ruby']
                ]);
            }
        });

        return ['message' => 'ok'];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $company = $this->company->findOrFail($id);
        return ['company' => $company];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUserRequest $request, string $id)
    {
        DB::transaction(function () use ($request, $id) {
            // 親テーブルの更新
            $company = $this->company->findOrFail($id);
            $company->update([
                'company' => $request->company,
                'company_ruby' => $request->company_ruby,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'ceo' => $request->ceo,
                'ceo_ruby' => $request->ceo_ruby
            ]);
    
            // 子テーブルの更新
            foreach ($company->addresses as $address) {
                $address->update([
                    'billing' => $request->input('billing', $address->billing),
                    'billing_ruby' => $request->input('billing_ruby', $address->billing_ruby),
                    'address' => $request->input('address', $address->address),
                    'phone_number' => $request->input('phone_number', $address->phone_number),
                    'department' => $request->input('department', $address->department),
                    'to' => $request->input('to', $address->to),
                    'to_ruby' => $request->input('to_ruby', $address->to_ruby)
                ]);
            }
        });    
    
        return ['message' => 'ok'];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->company->findOrFail($id)->delete();
        return ['message' => 'ok'];
    }

}
