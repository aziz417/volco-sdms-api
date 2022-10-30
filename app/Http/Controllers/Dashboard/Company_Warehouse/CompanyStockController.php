<?php

namespace App\Http\Controllers\Dashboard\Company_Warehouse;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Repositories\Common\CommonRepository;
use App\Http\Requests\CompanyStockRequest;

class CompanyStockController extends Controller
{
    
    use ResponseTrait;
    protected $common;

    public function __construct(CommonRepository $commonRepository)
    {
        $this->common = $commonRepository;
    }


    public function store(CompanyStockRequest $request){

        if($request->isMethod('post')){
            DB::beginTransaction();
            try{
                $companyStock = $this->common->storeInModel('App\Models\CompanyStock', $request->all());

                $companyStockHistoris = [
                    'company_stock_id' => $companyStock->id,
                    'date_time' => $companyStock->date,
                    'quantity_pisces' => $companyStock->quantity_pisces,
                    'type' => $companyStock->type,
                ];

                $this->common->storeInModel('App\Models\CompanyStockHistory', $companyStockHistoris);

                DB::commit();
                $message = "Stock Created Successfully";
                return $this->successResponse(Response::HTTP_CREATED, $message, $companyStock->toArray());

            }catch(QueryException $e){
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }

    }


    public function update(CompanyStockRequest $request, $id){

        if($request->isMethod('post')){
            DB::beginTransaction();
            try{
                $dealerStock = $this->common->findOnModel('App\Models\CompanyStock', 'id', $id);
                if(!$dealerStock){
                    return $this->errorResponse(404, 'Not FOund Your Targeted Data');
                }

                $dealerStock = $this->common->update($request->all(), $dealerStock);

                $dealerStockHistories = $this->common->findOnModel('App\Models\CompanyStockHistory', 'company_stock_id', $id);
                $subDealerStockHistoris = [
                    // 'sub_dealer_stock_id' => $subDealerStock->id,
                    'date_time' => $request->date,
                    'quantity_pisces' => $request->quantity_pisces,
                    'type' => $request->type,
                ];

                $this->common->update($subDealerStockHistoris, $dealerStockHistories);

                DB::commit();
                $message = 'Stock Updated Successfully';
                return $this->successResponse(200,$message,$dealerStock->toArray());

            }catch(QueryException $e){
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }

        }

    }

    public function destroy($id){
        DB::beginTransaction(); 
        try{
            $subDealerOrder = $this->common->findOnModel('App\Models\CompanyStock', 'id', $id);

            if($subDealerOrder){
                DB::table('company_stock_histories')->where('company_stock_id', $id)->delete();
                $subDealerOrder->delete();
            }else{
                return $this->errorResponse(404, 'Not Found Your Targeted Data', []);
            }

            DB::commit();
            $message = "Order Deleted Successfully";
            return $this->successResponse(200, $message, []);
           
        }catch(QueryException $e){
            DB::rollBack();
            return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
        }
    }



}
