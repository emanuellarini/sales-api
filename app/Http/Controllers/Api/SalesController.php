<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\User;
use App\Transformers\SaleTransformer;
use DB;

class SalesController extends Controller
{
    /**
     * Display a salesman's listing of Sales.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
            $sales = Sale::where('user_id', $payload['vendedor'])->get();
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
     * @return \Illuminate\Http\Response
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
            'user_id' => $user->id,
            'amount' => round($payload['valor'] * 100),
        ];

        DB::beginTransaction();
        try {
                $sale = Sale::create($data);
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
