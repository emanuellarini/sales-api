<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Transformers\SaleTransformer;

class SalesController extends Controller
{
    /**
     * Display a listing of Sales.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rules = [
            'vendedor' => ['required'],
        ];

        $payload = app('request')->only('vendedor');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            throw new \Dingo\Api\Exception\ResourceException('Error while fetching records.', $validator->errors());
        }

            $sales = Sale::where('user_id', $payload['vendedor'])->first();

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
}
