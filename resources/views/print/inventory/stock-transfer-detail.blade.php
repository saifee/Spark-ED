@include('print.print-layout.header')
    <h2 style="text-align: center;">{{config('config.default_academic_session.name')}}</h2>
    <h2>{{trans('inventory_sale.stock_sale_detail')}}</h2>
    <table class="fancy-detail">
        <tbody>
            <tr>
                <td><strong>{{trans('student.student_name')}}</strong></td>
                <td>{{$stock_transfer->Student->name}}</td>
                <td><strong>{{trans('student.contact_number')}}</strong></td>
                <td>{{$stock_transfer->Student->contact_number}}</td>
            </tr>
            <tr>
                <td><strong>{{trans('inventory.stock_transfer_date')}}</strong></td>
                <td>{{showDate($stock_transfer->date)}}</td>
                <td><strong>{{trans('inventory.stock_transfer_description')}}</strong></td>
                <td>{{$stock_transfer->description}}</td>
            </tr>
        </tbody>
    </table>
    <h2>{{trans('inventory.stock_item')}}</h2>
    <table class="fancy-detail">
        <thead>
            <tr>
                <th>{{trans('inventory.stock_item')}}</th>
                <th>{{trans('inventory_sale.stock_sale_quantity')}}</th>
                <th>{{trans('inventory_sale.stock_sale_price')}}</th>
                <th>{{trans('inventory_sale.stock_sale_total')}}</th>
            </tr>
        </thead>
        <tbody>
        	@foreach($stock_transfer['details'] as $detail)
        		<tr>
                    <td>{{$detail['item']['name']}}</td>
                    <td>{{$detail['quantity']}}</td>
                    <td>{{$detail['price']}}</td>
                    <td>{{$detail['price']*$detail['quantity']}}</td>
        		</tr>
        	@endforeach
            <tr>
                <td></td>
                <td></td>
                <td><span class="font-weight-bold">{{trans('inventory_sale.stock_sale_sub_total')}}</span></td>
                <td>{{$stock_transfer['sub_total']}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><span class="font-weight-bold">{{trans('inventory_sale.stock_sale_discount')}}</span></td>
                <td>{{$stock_transfer['discount']}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><span class="font-weight-bold">{{trans('inventory_sale.stock_sale_total')}}</span></td>
                <td>{{$stock_transfer['total']}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><span class="font-weight-bold">{{trans('inventory_sale.stock_sale_paid')}}</span></td>
                <td>
                    {{$stock_transfer['paid']}}
                    <i>({{$stock_transfer['payment_method']}})</i>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><span class="font-weight-bold">{{trans('inventory_sale.stock_sale_balance')}}</span></td>
                <td>{{$stock_transfer['balance']}}</td>
            </tr>
        </tbody>
    </table>
@include('print.print-layout.footer')
