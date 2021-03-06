@extends('layouts.master')

@section('title')
    {{"Product"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
@stop

@section('content')
    {!! Form::model($product,['method' => 'patch', 'action' => ['ProductController@update',$product->id]]) !!}
    @include('layouts.required')
    @include('product.form')
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('js/product.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.numeric.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script>
        $(document).ajaxStart(function() { Pace.restart(); });
        var activeVehicles = [
            @if($product->vehicle)
                @foreach($product->vehicle as $vehicle)
                    "{{$vehicle->modelId}},{{$vehicle->isManual}}",
                @endforeach
            @endif
        ];
        var products = [
            @foreach($products as $products)
                '{{$products->name}}',
            @endforeach
        ];
        $("#vehicle").val(activeVehicles);
        $(".select2").select2();
        $('#productName').autocomplete({source: products});
        @if($product)
            changeType({{$product->typeId}});
            $('#pt').val({{$product->typeId}}).trigger("change");
        @endif
        $("#isOriginal[value={{$product->isOriginal}}]").prop('checked',true);
        $(document).ajaxStop(function() {
            @if($product)
                $('#pb').val({{$product->brandId}}).trigger("change");
                $('#pv').val({{$product->varianceId}}).trigger("change");
            @endif
        });
    </script>
    <script>
        $(document).ready(function (){
            $('#maintenance').addClass('active');
            $('#mi').addClass('active');
            $('#mProduct').addClass('active');
            $("#price").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
                max: 500000,
            });
            $("#reorder").inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 0,
                max: 100,
            });
        });
    </script>
@stop