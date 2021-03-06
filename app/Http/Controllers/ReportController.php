<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $data = DB::table('product as p')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->join('inventory as i','p.id','i.productId')
            ->select('p.*','pt.name as type','pb.name as brand','pv.name as variance','i.quantity as quantity','p.name as name')
            ->get();
        // $results = DB::select( DB::raw("select * from product join inventory on product.id = inventory.productId JOIN product_type on product.typeId = product_type.id JOIN product_brand on product.brandId = product_brand.id JOIN product_variance on product.varianceId = product_variance.id") );

        $inventory  = DB::select(DB::raw("select delivery_detail.created_at as datecreated from return_delivery join return_header on return_delivery.returnId = return_header.id join supplier on return_header.supplierId = supplier.id join delivery_header on delivery_header.supplierId = supplier.id join delivery_detail on delivery_detail.deliveryId = delivery_header.id join product on product.id = delivery_detail.productId join product_type on product_type.id = product.typeId join product_brand on product_brand.id = product.brandId join product_variance on product_variance.id = product.varianceId
            UNION ALL
            select sales_header.created_at as salescreated from sales_header join customer on customer.id = sales_header.customerId join sales_product on sales_product.salesId = sales_header.id join product on sales_product.id = product.id join product_type on product_type.id = product.typeId join product_brand on product_brand.id = product.brandId join product_variance on product_variance.id = product.varianceId
            UNION ALL
            Select job_header.total from job_header join customer on job_header.customerId = customer.id"));

        $services = DB::table('job_header as j')
            ->join('customer as c','c.id','j.customerId')
            ->join('job_service as js','j.id','js.jobId')
            ->join('service as s','s.id','js.serviceId')
            ->join('service_category as sc','sc.id','s.categoryId')
            ->select('j.*','j.id as jobId','c.*','s.*','s.name as name','sc.name as category','s.size as size', 's.name as service')
            ->get();

        $pos = DB::table('customer as c')
            ->join('sales_header as sh','c.id','sh.customerId')
            ->join('sales_product as sp','sp.salesId','sh.id')
            ->select('c.*','sh.id as invoId','sp.*','sh.*')
            ->get();

        $return = DB::select(DB::raw("SELECT supplier.*, return_detail.quantity as qtyre , product.*,return_header.id as id, return_header.*, product.name as product, product_brand.name as brand, product_type.name as type, product_variance.name as variance, supplier.name as supplier FROM supplier join return_header on supplier.id = return_header.supplierId join return_detail on return_detail.returnId = return_header.id join product on product.id = return_detail.productId join product_type on product_type.id = product.typeId join product_brand on product_brand.id = product.brandId join product_variance on product_variance.id = product.varianceId"));


        $jobs = DB::table('job_header as j')
            ->join('customer as c','c.id','j.customerId')
            ->join('vehicle as v','v.id','j.vehicleId')
            ->join('vehicle_model as vd','vd.id','v.modelId')
            ->join('vehicle_make as vk','vk.id','vd.makeId')
            ->join('job_product as jp','j.id','jp.jobId')
            ->join('product as p','p.id','jp.productId')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->join('job_service as js','j.id','js.jobId')
            ->join('service as s','s.id','js.serviceId')
            ->join('service_category as sc','sc.id','s.categoryId')
            ->select('j.*','j.id as jobId','c.*','v.*','vd.name as model','vd.year as year','vk.name as make','p.*','pt.name as type','pb.name as brand','pv.name as variance','s.*','s.name as name','sc.name as category','s.size as size', 'v.plate as plate', 'v.isManual as transmission', 's.name as service', 'p.name as product')
            ->get();

        $products = DB::table('job_header as j')
            ->join('customer as c','c.id','j.customerId')
            ->join('job_product as jp','j.id','jp.jobId')
            ->join('product as p','p.id','jp.productId')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->select('j.*','j.id as jobId','p.price as pprices','c.*','p.*','pt.name as type','pb.name as brand','pv.name as variance', 'p.name as product')
            ->get();

        $customer = DB::select(DB::raw("
            Select *, job_header.id as jobId from job_header JOIN customer on job_header.customerId = customer.id 
            WHERE job_header.total <> job_header.paid"));

        return view('report.index',compact('data','services','jobs','products','customer','pos','return','inventory'));
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function report($id)
    {
       
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

     public function where(Request $request)
     {
        $id = $request->id;
        $dates = explode('-',$request->date); // two dates MM/DD/YYYY-MM/DD/YYYY
        $startDate = explode('/',$dates[0]); // MM[0] DD[1] YYYY[2] 
        $finalStartDate = "$startDate[2]-$startDate[0]-$startDate[1]";
        $endDate = explode('/',$dates[1]); // MM[0] DD[1] YYYY[2] 
        $finalEndDate = "$endDate[2]-$endDate[0]-$endDate[1]";
        if($id == 1)
        {
             $data = DB::table('product as p')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->join('inventory as i','p.id','i.productId')
            ->where('i.updated_at','>=',$finalEndDate)
            ->select('p.*','pt.name as type','pb.name as brand','pv.name as variance','i.quantity as quantity','p.name as name')
            ->get();
             return response()->json(['data'=>$data]);
         }
        // $results = DB::select( DB::raw("select * from product join inventory on product.id = inventory.productId JOIN product_type on product.typeId = product_type.id JOIN product_brand on product.brandId = product_brand.id JOIN product_variance on product.varianceId = product_variance.id") );
        else if($id == 2)
        {
            $data = DB::table('job_header as j')
            ->join('customer as c','c.id','j.customerId')
            ->join('job_service as js','j.id','js.jobId')
            ->join('service as s','s.id','js.serviceId')
            ->join('service_category as sc','sc.id','s.categoryId')
            ->where('j.updated_at','>=',$finalEndDate)
            ->select('j.*','j.id as jobId','c.*','s.*','s.name as name','sc.name as category','s.size as size', 's.name as service')
            ->get();
             return response()->json(['data'=>$data]);
        }
        else if($id == 3)
        {
             $data = DB::table('job_header as j')
            ->join('customer as c','c.id','j.customerId')
            ->join('vehicle as v','v.id','j.vehicleId')
            ->join('vehicle_model as vd','vd.id','v.modelId')
            ->join('vehicle_make as vk','vk.id','vd.makeId')
            ->join('job_product as jp','j.id','jp.jobId')
            ->join('product as p','p.id','jp.productId')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->join('job_service as js','j.id','js.jobId')
            ->join('service as s','s.id','js.serviceId')
            ->join('service_category as sc','sc.id','s.categoryId')
            ->where('j.updated_at','>=',$finalEndDate)
            ->select('j.*','j.id as jobId','c.*','v.*','vd.name as model','vd.year as year','vk.name as make','p.*','pt.name as type','pb.name as brand','pv.name as variance','s.*','s.name as name','sc.name as category','s.size as size', 'v.plate as plate', 'v.isManual as transmission', 's.name as service', 'p.name as product')
            ->get();
             return response()->json(['data'=>$data]);
            }

        else if($id == 4)
        {
             $data = DB::table('job_header as j')
            ->join('customer as c','c.id','j.customerId')
            ->where('j.total','<>','j.paid')
            ->where('j.updated_at','>=',$finalEndDate)
            ->select('*')
            ->get();
            return response()->json(['data'=>$data]);
        }
       

     }
}
