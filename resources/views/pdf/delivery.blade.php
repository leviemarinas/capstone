<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{$util->name}} | Receive Delivery</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <style type="text/css">
        @page{
            margin-top: 1cm;
            margin-bottom: 0.25cm;
        }
        body{
            font-family: "SegoeUI","Sans-serif";
            font-size: 12px;
        }
        .header{
            font-size: 20px!important;
        }
        .page-break {
            page-break-after: always;
        }
        .center{
            text-align: center;
        }
        .col-md-12{
            width: 100%;
        }
        .col-md-6{
            width: 50%;
        }
        .border{
            border: 1px solid black;
        }
        .text-right{
            text-align: right;
        }
        table{
            clear: both;
            border: 1px solid black
        }
        tbody tr{
            border: 1px solid black;
        }
        tr:nth-child(even) {
            background-color: #e6e6e6
        }
        thead th{
            background-color: black;
            color: white;
        }
        .footer{
            position: absolute;
            bottom: 0;
        }
        .footerd{
            font-size: 0.8em;
        }
    </style>
    <body>
        <div style="float:left">
            <img src="{{$util->image}}" width="50px" height="50px">
        </div>
        <div class="center header">
            {{$util->name}}
        </div>
        <label style="float:right;color:red">{{$delivery->id}}</label>
        <div style="clear:both"></div>
        <div class="center">
            <label>AUTO SERVICE CENTER</label>
        </div>
        <div class="col-md-12 border center">
            RECEIVE DELIVERY
        </div><br>
        <div style="float:left" class="col-md-6">
            Supplier: {{$delivery->supplier->name}}<br>
            Address: {{$delivery->supplier->street}} {{$delivery->supplier->brgy}} {{$delivery->supplier->city}}
        </div>
        <div style="float:right"  class="col-md-6">
            Date: {{date('F j, Y',strtotime($delivery->dateMake))}}<br>
            Reference Orders: 
            @foreach($delivery->order as $order)
                {{$order->purchaseId}}|
            @endforeach
        </div>
        <div style="clear:both"></div>
        <br>
        <table width="100%">
            <thead>
                <tr>
                    <th>Product</th>
                    <th width="20%" class="text-right">Quantity Delivered</th>
                </tr>
            </thead>
            <tbody>
                @foreach($delivery->detail as $product)
                <tr>
                    <?php
                        if($product->product->isOriginal!=null){
                            $type = ($product->product->isOriginal=="type1" ? $util->type1 : $util->type2);
                        }else{
                            $type = "";
                        }
                    ?>
                    <td>{{$product->product->brand->name}} - {{$product->product->name}} {{$type}} ({{$product->product->variance->name}})</td>
                    <td class="text-right">{{number_format($product->quantity)}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="footer">
            <div class="col-md-6">
                Received by &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ______________________<br>
                Counter Checked by: ______________________<br> 
            </div>
            <div class="col-md-6">
                Returns: {{$attachments}}
            </div>
            <br><br>
            <div class="footerd">Printed by: Admin {{$date}}</div>
        </div>
    </body>
</html>