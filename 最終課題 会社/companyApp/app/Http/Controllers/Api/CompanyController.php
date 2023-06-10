<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Address;
use App\Http\Requests\PostSameTimeRequest;

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
        $validated = $request->validated();

        $this->company->fill($validated)->save();

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
        $this->company->findOrFail($id)->update($request->validated());
    
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

    public function storeSameTime(PostSameTimeRequest $request)
    {
        $params = $request->validated();
    
            $company = DB::transaction(function() use ($params) {
            $company = $this->company->create($params);
            $company->address()->create($params['address']);


            return $company->load('address');
        });
    
        return $company;
        
    }

}
