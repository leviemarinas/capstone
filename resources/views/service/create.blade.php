@extends('layouts.master')

@section('title')
    {{"Service"}}
@stop

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
@stop

@section('content')
    {!! Form::open(['url' => 'service']) !!}
    @include('service.form')
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.numeric.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script>
        @if(old('categoryId'))
            $("#sc").val({{old('categoryId')}});
        @endif
        @if(old('size'))
            $("#sizeId[value={{old('size')}}]").prop('checked',true);
        @endif
        $(".select2").select2();
    </script>
    <script>
        $(document).ready(function (){
            $('#ms').addClass('active');
            $('#mService').addClass('active');
            $("#price").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
                max: 500000,
            });
        });
    </script>
@stop