<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\User;
use App\Transformers\SaleTransformer;
use DB;

/**
 * Sale resource representation.
 *
 * @Resource("Sales", uri="/vendas")
 */
class SalesController extends Controller
{

    /**
     * Display a salesman's listing of Sales.
     *
     *
     * @Post("/vendas")
     * @Versions({"v1"})
     * @Parameter("vendedor", description="The ID of a salesman.")
     * @Transaction({
     *      @Response(200, body={{"id": 1, "name": "Dummy", "email": "dummy@dummy.com.br", "amount": "R$ 100,00", "commission": "R$ 8,50", "date": "15/01/2017 22:10:00"}}),
     *      @Response(422, body={"error": {"Error while fetching records."}})
     * })
     */
    public function index()
    {
        $rules = [
            'vendedor' => ['required', 'integer'],
        ];

        $payload = app('request')->only('vendedor');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            throw new \Dingo\Api\Exception\ResourceException('Error while fetching records.', $validator->errors());
        }

        try {
            $sales = User::find($payload['vendedor'])->sales()->get();
        } catch (\Exception $e) {
            throw new \Dingo\Api\Exception\ResourceException('Error while fetching records.');
        }

        return $this->response
            ->collection($sales, new SaleTransformer)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->setStatusCode(200);
    }

    /**
     * Store a Saleman's new Sale.
     *
     * @Post("/vendas")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request({"vendedor": 1, "valor": 100}),
     *      @Response(200, body={"id": 1, "name": "Dummy", "email": "dummy@dummy.com.br", "amount": "R$ 100,00", "commission": "R$ 8,50", "date": "15/01/2017 22:10:00"}),
     *      @Response(422, body={"error": "Error while fetching records."})
     * })
     */
    public function store()
    {
        $rules = [
            'vendedor' => ['required', 'integer'],
            'valor' => ['required', 'numeric'],
        ];

        $payload = app('request')->only(['vendedor', 'valor']);

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Error while fetching records.', $validator->errors());
        }

        $user = User::find($payload['vendedor']);

        if (!$user) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Salesman does not exist.');
        }

        $data = [
            'amount' => round($payload['valor'] * 100),
        ];

        DB::beginTransaction();
        try {
                $sale = $user->sales()->create($data);
                $user->userable->commission = $user->userable->commission + ($sale->amount * ($sale->commission_pct/100));
                $user->userable->save();
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Error while creating record.');
        } catch (\Exception $e) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Error while creating record.');
        }

        return $this->response
            ->item($sale, new SaleTransformer)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->setStatusCode(200);
    }
}
