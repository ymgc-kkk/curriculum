<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Company;
use App\Http\Requests\AddressRequest;

class AddressController extends Controller

{
    private $address;
    private $company;

    public function __construct(Address $address, Company $company)
    {
        $this->address = $address;
        $this->company = $company;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addresses = $this->address->all();  
        return ['addresses' => $addresses]; 
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
    public function store(AddressRequest $request, int $id)
    {
        $validated = $request->validated();
        $company = $this->company->findOrFail($id);
        $company->address()->create($validated);

        return ['message' => 'ok'];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $address = $this->address->findOrFail($id);
        return ['address' => $address];
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
    public function update(AddressRequest $request, string $id)
    {
        $this->address->findOrFail($id)->update($request->validated());
    
        return ['message' => 'ok'];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->address->findOrFail($id)->delete();
        return ['message' => 'ok'];
    }

}
