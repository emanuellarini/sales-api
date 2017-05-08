<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Salesman;
use App\Transformers\SalesmanTransformer;
use App\Transformers\UserTransformer;

/**
 * Salesman resource representation.
 *
 * @Resource("Salesmen", uri="/vendedores")
 */
class SalesmenController extends Controller
{
    /**
     * Display a listing of Salesmen.
     *
     * @Get("/vendedores")
     * @Versions({"v1"})
     * @Transaction({
     *      @Response(200, body={{"id": 1, "name": "Dummy", "email": "dummy@dummy.com.br", "commission": "R$ 1.110,00"}}),
     *      @Response(422, body={"error": "Error while fetching records."})
     * })
     */
    public function index()
    {
        try {
            $salesmen = Salesman::with('user')->get();
        } catch(\Exception $e) {
            throw new \Dingo\Api\Exception\ResourceException('Error while fetching records.');
        }

        return $this->response
            ->collection($salesmen, new SalesmanTransformer)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->setStatusCode(200);
    }

    /**
     * Store a new Salesmen.
     *
     * @Post("/vendedores")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request({"name": "Dummy", "email": "dummy@dummy.com.br"}),
     *      @Response(200, body={"id": 1, "name": "Dummy", "email": "dummy@dummy.com.br"}),
     *      @Response(422, body={"error": "Error while creating a Salesman."})
     * })
     */
    public function store()
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
