@extends('layouts.master')

@section('title')
    {{"Discount"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
@stop

@section('content')
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>
                <div class="box-tools pull-right">
                    <a href="{{ URL::to('/discount/create') }}" class="btn btn-success btn-md">
                    <i class="glyphicon glyphicon-plus"></i> New Record</a>
                </div>
            </div>
            <div class="box-body dataTable_wrapper">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="activeTable">
                        <table id="list" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>Discount</th>
                                    <th>Type</th>
                                    <th>Item(s)</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($discounts as $discount)
                                    <tr>
                                        <?php
                                            $type = ($discount->isWhole ? 'Whole' : 'Individual');
                                        ?>
                                        <td>{{$discount->name}} - {{$discount->rate}} %</td>
                                        <td>{{$type}}</td>
                                        <td>
                                            @if($discount->product->isNotEmpty())
                                                <b>Products:</b>
                                                @foreach($discount->product as $product)
                                                    <li>{{$product->product->brand->name}} - {{$product->product->name}} ({{$product->product->variance->name}})</li>
                                                @endforeach
                                            @endif
                                            @if($discount->service->isNotEmpty())
                                                <b>Services:</b>
                                                @foreach($discount->service as $service)
                                                    <li>{{$service->service->name}} - {{$service->service->size}}</li>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a href="{{url('/discount/'.$discount->id.'/edit')}}" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Update record">
                                                <i class="glyphicon glyphicon-edit"></i>
                                            </a>
                                            <button onclick="deactivateShow({{$discount->id}})" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deactivate record">
                                                <i class="glyphicon glyphicon-trash"></i>
                                            </button>
                                            {!! Form::open(['method'=>'delete','action' => ['DiscountController@destroy',$discount->id],'id'=>'del'.$discount->id]) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="inactiveTable">
                        <table id="dlist" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>Discount</th>
                                    <th>Item(s)</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deactivate as $discount)
                                    <tr>
                                        <td>{{$discount->name}} - {{$discount->rate}} %</td>
                                        <td>
                                            @if($discount->product->isNotEmpty())
                                                <b>Products:</b>
                                                @foreach($discount->product as $product)
                                                    <li>{{$product->product->brand->name}} - {{$product->product->name}} ({{$product->product->variance->name}})</li>
                                                @endforeach
                                            @endif
                                            @if($discount->service->isNotEmpty())
                                                <b>Services:</b>
                                                @foreach($discount->service as $service)
                                                    <li>{{$service->service->name}} - {{$service->service->size}}</li>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <button onclick="reactivateShow({{$discount->id}})"type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Reactivate record">
                                                <i class="glyphicon glyphicon-refresh"></i>
                                            </button>
                                            {!! Form::open(['method'=>'patch','action' => ['DiscountController@reactivate',$discount->id],'id'=>'reactivate'.$discount->id]) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-group pull-right">
                    <label class="checkbox-inline"><input type="checkbox" id="showDeactivated"> Show deactivated records</label>
                </div>
                @include('layouts.reactivateModal')
                @include('layouts.deactivateModal')
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ URL::asset('js/record.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#list').DataTable({
                responsive: true,
            });
            $('#dlist').DataTable({
                responsive: true,
            });
            $('#maintenance').addClass('active');
            $('#mDiscount').addClass('active');
        });
    </script>
@stop