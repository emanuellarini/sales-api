<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Salesman;
use App\Transformers\SalesmanTransformer;
use App\Transformers\UserTransformer;

class SalesmenController extends Controller
{
    /**
     * Display a listing of Salesmen.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $salesmen = Salesman::with('user')->get();

        return $this->response
            ->collection($salesmen, new SalesmanTransformer)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->setStatusCode(200);
    }

    /**
     * Store a newly Salesman.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users']
        ];

        $payload = app('request')->only('name', 'email');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Error while creating a Salesman.', $validator->errors());
        }

        $salesman = Salesman::create();
        $salesman->user()->create($request->all());

        return $this->response
            ->item($salesman->user, new UserTransformer)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->setStatusCode(200);
    }
}
