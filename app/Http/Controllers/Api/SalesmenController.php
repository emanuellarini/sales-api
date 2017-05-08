<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Salesman\SalesmanRepository;
use App\Transformers\SalesmanTransformer;
use App\Transformers\UserTransformer;
use DB;

/**
 * Salesman resource representation.
 *
 * @Resource("Salesmen", uri="/vendedores")
 */
class SalesmenController extends Controller
{
    private $salesmen;
    public function __construct(SalesmanRepository $salesmen)
    {
        $this->salesmen = $salesmen;
    }

    /**
     * Display a listing of Salesmen.
     *
     * @Get("/")
     * @Versions({"v1"})
     * @Transaction({
     *      @Response(200, body={{"id": 1, "name": "Dummy", "email": "dummy@dummy.com.br", "commission": "R$ 1.110,00"}}),
     *      @Response(422, body={"message": "Error while fetching records."})
     * })
     */
    public function index()
    {
        try {
            $salesmen = $this->salesmen->getAllWithUser();
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
     * @Post("/")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request({"name": "Dummy", "email": "dummy@dummy.com.br"}),
     *      @Response(200, body={"id": 1, "name": "Dummy", "email": "dummy@dummy.com.br"}),
     *      @Response(422, body={"message": "Error while creating a Salesman."})
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

        DB::beginTransaction();
        try {
            $salesman = $this->salesmen->createWithUser($payload);
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Error while creating record.');
        } catch (\Exception $e) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Error while creating record.');
        }

        return $this->response
            ->item($salesman->user, new UserTransformer)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->setStatusCode(200);
    }
}
