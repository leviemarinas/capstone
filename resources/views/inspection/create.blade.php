@extends('layouts.master')

@section('title')
    {{"Inspection"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/formbuilder/form-builder.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/formbuilder/form-render.min.css') }}">
@stop

@section('content')
    {!! Form::open(['url' => 'inspection','id'=>'submit']) !!}
    @include('layouts.required')
    <div class="col-md-5">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Inspection Form</h3>
            </div>
            <div id="items" class="box-body">
                <div class="form-group">
                    {!! Form::label('type', 'Inspection Type') !!}<span>*</span>
                    {!! Form::input('text','type',null,[
                        'class' => 'form-control',
                        'placeholder'=>'Name',
                        'maxlength'=>'50',
                        'required'])
                    !!}
                </div>
                @if(old('item'))
                @foreach(old('item') as $key=>$item)
                    <div id="item" class="form-group">
                        @if($loop->index!=0)
                            <button id="removeItem" type="button" class="btn btn-flat btn-danger btn-xs pull-right" data-toggle="tooltip" data-placement="top" title="Remove">
                                <i class="glyphicon glyphicon-remove"></i>
                            </button>
                        @endif
                        <button id="pushItem" type="button" class="btn btn-flat btn-warning btn-xs pull-right" data-toggle="tooltip" data-placement="top" title="Create form">
                            <i class="glyphicon glyphicon-menu-right"></i>
                        </button>
                        {!! Form::label('item', 'Inspection Items') !!}<span>*</span>
                        <textarea class="hidden" name="inspectionForm[]" id="inspectionForm" required>{{old('inspectionForm.'.$key)}}</textarea>
                        {!! Form::input('text',null,$item,[
                            'class' => 'form-control ',
                            'id' => 'inspectionItem',
                            'name'=>'item[]',
                            'placeholder'=>'Inspection Item',
                            'maxlength'=>'50',
                            'required']) 
                        !!}
                    </div>
                @endforeach
                @else
                <div id="item" class="form-group">
                    <button id="pushItem" type="button" class="btn btn-flat btn-warning btn-xs pull-right" data-toggle="tooltip" data-placement="top" title="Create form">
                        <i class="glyphicon glyphicon-menu-right"></i>
                    </button>
                    {!! Form::label('item', 'Inspection Items') !!}<span>*</span>
                    <textarea class="hidden" name="inspectionForm[]" id="inspectionForm" required></textarea>
                    {!! Form::input('text',null,null,[
                        'class' => 'form-control ',
                        'id' => 'inspectionItem',
                        'name'=>'item[]',
                        'placeholder'=>'Inspection Item',
                        'maxlength'=>'50',
                        'required']) 
                    !!}
                </div>
                @endif
            </div>
            <div class="box-footer">
                <button type="button" id="save" class="btn btn-primary">Save</button>
                <button id="addItem" type="button" class="btn btn-md btn-primary pull-right" data-toggle="tooltip" data-placement="top" title="Add">
                    <i class="glyphicon glyphicon-plus"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div id="formbox" class="box">
            <div id="header" class="box-header with-border">
                <h3 class="box-title" id="itemComponent">Item Components</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div id="body" class="box-body"></div>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('assets/formbuilder/form-builder.min.js') }}"></script>
    <script src="{{ URL::asset('assets/formbuilder/form-render.min.js') }}"></script>
    <script src="{{ URL::asset('js/inspection.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#maintenance').addClass('active');
            $('#ms').addClass('active');
            $('#mInspection').addClass('active');
        });
    </script>
@stop