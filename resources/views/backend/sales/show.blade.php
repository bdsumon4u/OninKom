@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">{{ translate('Order Details') }}</h1>
        </div>
        <div class="card-body">
            <div class="row gutters-5">
                <div class="col text-md-left text-center">
                </div>
                @php
                    $delivery_status = $order->delivery_status;
                    $payment_status = $order->payment_status;
                    $admin_user_id = App\Models\User::where('user_type', 'admin')->first()->id;
                @endphp

                <!--Assign Delivery Boy-->
                @if ($order->seller_id == $admin_user_id || get_setting('product_manage_by_admin') == 1)
                    @php $obj = json_decode($order->shipping_address) @endphp
                    @if (addon_is_activated('delivery_boy'))
                        <div class="col-md-3 ml-auto mb-2">
                            <label for="assign_deliver_boy">{{ translate('Assign Deliver Boy') }}</label>
                            @if (($delivery_status == 'pending' || $delivery_status == 'confirmed' || $delivery_status == 'picked_up') && auth()->user()->can('assign_delivery_boy_for_orders'))
                                <select class="form-control aiz-selectpicker" data-live-search="true"
                                    data-minimum-results-for-search="Infinity" id="assign_deliver_boy">
                                    <option value="">{{ translate('Select Delivery Boy') }}</option>
                                    @foreach ($delivery_boys as $delivery_boy)
                                        <option value="{{ $delivery_boy->id }}"
                                            @if ($order->assign_delivery_boy == $delivery_boy->id) selected @endif>
                                            {{ $delivery_boy->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" class="form-control" value="{{ optional($order->delivery_boy)->name }}"
                                    disabled>
                            @endif
                        </div>
                    @endif

                    <div class="col-md-3 ml-auto mb-2">
                        <label for="update_payment_status">{{ translate('Payment Status') }}</label>
                        @if (auth()->user()->can('update_order_payment_status'))
                            <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                                id="update_payment_status">
                                <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>
                                    {{ translate('Unpaid') }}
                                </option>
                                <option value="paid" @if ($payment_status == 'paid') selected @endif>
                                    {{ translate('Paid') }}
                                </option>
                            </select>
                        @else
                            <input type="text" class="form-control" value="{{ $payment_status }}" disabled>
                        @endif
                    </div>
                    <div class="col-md-3 ml-auto mb-2">
                        <label for="update_delivery_status">{{ translate('Delivery Status') }}</label>
                        @if (auth()->user()->can('update_order_delivery_status') && $delivery_status != 'delivered' && $delivery_status != 'cancelled')
                            <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                                id="update_delivery_status">
                                <option value="pending" @if ($delivery_status == 'pending') selected @endif>
                                    {{ translate('Pending') }}
                                </option>
                                <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>
                                    {{ translate('Confirmed') }}
                                </option>
                                <option value="picked_up" @if ($delivery_status == 'picked_up') selected @endif>
                                    {{ translate('Picked Up') }}
                                </option>
                                <option value="on_the_way" @if ($delivery_status == 'on_the_way') selected @endif>
                                    {{ translate('On The Way') }}
                                </option>
                                <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>
                                    {{ translate('Delivered') }}
                                </option>
                                <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>
                                    {{ translate('Cancel') }}
                                </option>
                            </select>
                        @else
                            <input type="text" class="form-control" value="{{ $delivery_status }}" disabled>
                        @endif
                    </div>
                    <div class="col-md-3 ml-auto mb-2">
                        <label for="update_commission_status">{{ translate('Commission Status') }}</label>
                        @if (auth()->user()->can('update_order_payment_status') && $delivery_status == 'delivered' && $payment_status == 'paid' && $order->commission_status != 'paid')
                            <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                                id="update_commission_status">
                                <option value="unpaid" @if ($order->commission_status == 'unpaid') selected @endif>
                                    {{ translate('Unpaid') }}
                                </option>
                                <option value="paid" @if ($order->commission_status == 'paid') selected @endif>
                                    {{ translate('Paid') }}
                                </option>
                            </select>
                        @else
                            <input type="text" class="form-control" value="{{ $order->commission_status }}" disabled>
                        @endif
                    </div>
                    <div class="col-md-3 ml-auto mb-2">
                        <label for="update_commission_ref">
                            {{ translate('Commission Reference') }}
                        </label>
                        <input type="text" class="form-control" id="update_commission_ref"
                            value="{{ $order->commission_ref }}" @unless (auth()->user()->can('update_order_payment_status') && $delivery_status == 'delivered' && $payment_status == 'paid' && $order->commission_status != 'paid') disabled @endunless>
                    </div>
                    <div class="col-md-3 ml-auto mb-2">
                        <label for="update_courier">{{ translate('Courier') }}</label>
                        @if (auth()->user()->can('update_order_delivery_status') && $delivery_status != 'delivered' && $delivery_status != 'cancelled')
                            <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                                id="update_courier">
                                <option value="">Select Courier</option>
                                @foreach (config('other.couriers') as $courier)
                                    <option value="{{ $courier }}" @if(($obj->courier ?? null) == $courier) selected @endif>{{ translate($courier) }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" class="form-control" value="{{ $order->courier ?? null }}" disabled>
                        @endif
                    </div>
                    <div class="col-md-3 ml-auto mb-2">
                        <label for="update_tracking_code">
                            {{ translate('Tracking Code (optional)') }}
                        </label>
                        <input type="text" class="form-control" id="update_tracking_code"
                            value="{{ $order->tracking_code }}">
                    </div>
                    <div class="col-md-3 ml-auto mb-2">
                        <label for="instruction">
                            {{ translate('Instruction') }}
                        </label>
                        <div>{{ $obj->instruction ?? null }}</div>
                    </div>
                @endif
            </div>
            <div class="mb-3">
                @php
                    $removedXML = '<?xml version="1.0" encoding="UTF-8"?>';
                @endphp
                {!! str_replace($removedXML, '', QrCode::size(100)->generate($order->code)) !!}
            </div>
            <div class="row gutters-5">
                <div class="col text-md-left text-center">
                    @if($obj)
                        <address>
                            <strong class="text-main">
                                {{ $obj->name }}
                            </strong><br>
                            {{ $obj->email }}<br>
                            {{ $obj->phone }}<br>
                            {{ $obj->address }}, {{ $obj->city }}, @if(isset($obj->state)) {{ $obj->state }} - @endif {{ $obj->postal_code }}<br>
                            {{ $obj->country }}
                        </address>
                    @else
                        <address>
                            <strong class="text-main">
                                {{ $order->user->name }}
                            </strong><br>
                            {{ $order->user->email }}<br>
                            {{ $order->user->phone }}<br>
                        </address>
                    @endif
                    @if ($order->manual_payment && is_array(json_decode($order->manual_payment_data, true)))
                        <br>
                        <strong class="text-main">{{ translate('Payment Information') }}</strong><br>
                        {{ translate('Name') }}: {{ json_decode($order->manual_payment_data)->name }},
                        {{ translate('Amount') }}:
                        {{ single_price(json_decode($order->manual_payment_data)->amount) }},
                        {{ translate('TRX ID') }}: {{ json_decode($order->manual_payment_data)->trx_id }}
                        <br>
                        <a href="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" target="_blank">
                            <img src="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" alt=""
                                height="100">
                        </a>
                    @endif
                </div>
                <div class="col-md-4 ml-auto">
                    <table>
                        <tbody>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order #') }}</td>
                                <td class="text-info text-bold text-right"> {{ $order->code }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order Status') }}</td>
                                <td class="text-right">
                                    @if ($delivery_status == 'delivered')
                                        <span class="badge badge-inline badge-success">
                                            {{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}
                                        </span>
                                    @else
                                        <span class="badge badge-inline badge-info">
                                            {{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order Date') }} </td>
                                <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">
                                    {{ translate('Total amount') }}
                                </td>
                                <td class="text-right">
                                    {{ single_price($order->grand_total) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Payment method') }}</td>
                                <td class="text-right">
                                    {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Additional Info') }}</td>
                                <td class="text-right">{{ $order->additional_info }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="new-section-sm bord-no">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table class="table-bordered aiz-table invoice-summary table">
                        <thead>
                            <tr class="bg-trans-dark">
                                <th data-breakpoints="lg" class="min-col">#</th>
                                <th width="10%">{{ translate('Photo') }}</th>
                                <th class="text-uppercase">{{ translate('Description') }}</th>
                                <th data-breakpoints="lg" class="text-uppercase">{{ translate('Delivery Type') }}</th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                    {{ translate('Buying Total') }}
                                </th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                    {{ translate('Selling Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                            <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank">
                                                <img height="50" src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}">
                                            </a>
                                        @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <a href="{{ route('auction-product', $orderDetail->product->slug) }}" target="_blank">
                                                <img height="50" src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}">
                                            </a>
                                        @else
                                            <strong>{{ translate('N/A') }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                            <strong>
                                                <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank"
                                                    class="text-muted">
                                                    {{ $orderDetail->product->getTranslation('name') }}
                                                </a>
                                            </strong>
                                            <small>
                                                {{ $orderDetail->variation }}
                                            </small>
                                            <br>
                                            <small>
                                                @php
                                                    $product_stock = json_decode($orderDetail->product->stocks->first(), true);
                                                @endphp
                                                {{translate('SKU')}}: {{ $product_stock['sku'] }}
                                            </small>
                                        @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <strong>
                                                <a href="{{ route('auction-product', $orderDetail->product->slug) }}" target="_blank"
                                                    class="text-muted">
                                                    {{ $orderDetail->product->getTranslation('name') }}
                                                </a>
                                            </strong>
                                        @else
                                            <strong>{{ translate('Product Unavailable') }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                            {{ translate('Home Delivery') }}
                                        @elseif ($order->shipping_type == 'pickup_point')
                                            @if ($order->pickup_point != null)
                                                {{ $order->pickup_point->getTranslation('name') }}
                                                ({{ translate('Pickup Point') }})
                                            @else
                                                {{ translate('Pickup Point') }}
                                            @endif
                                        @elseif($order->shipping_type == 'carrier')
                                            @if ($order->carrier != null)
                                                {{ $order->carrier->name }} ({{ translate('Carrier') }})
                                                <br>
                                                {{ translate('Transit Time').' - '.$order->carrier->transit_time }}
                                            @else
                                                {{ translate('Carrier') }}
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $orderDetail->quantity }} x {{ single_price($orderDetail->price / $orderDetail->quantity) }} = {{ single_price($orderDetail->price) }}
                                    </td>
                                    <td class="text-center">
                                        {{ $orderDetail->quantity }} x {{ single_price($orderDetail->selling_price / $orderDetail->quantity) }} = {{ single_price($orderDetail->selling_price) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="clearfix float-right">
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Buying</th>
                            <th>Selling</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Sub Total') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('price')) }}
                            </td>
                            <td>
                                {{ single_price($order->selling_total - ($obj->shipping ?? 0)) }}
                            </td>
                        </tr>
                        @if ($tax = $order->orderDetails->sum('tax'))
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Tax') }} :</strong>
                            </td>
                            <td colspan="2">
                                {{ single_price($tax) }}
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Shipping') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('shipping_cost')) }}
                            </td>
                            <td>
                                {{ single_price($obj->shipping ?? 0) }}
                            </td>
                        </tr>
                        @if (($discount = $order->coupon_discount) != '0.00')
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Coupon') }} :</strong>
                            </td>
                            <td colspan="2">
                                {{ single_price($discount) }}
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('TOTAL') }} :</strong>
                            </td>
                            <td class="text-muted h5">
                                {{ single_price($order->grand_total) }}
                            </td>
                            <td class="text-muted h5">
                                {{ single_price($order->selling_total) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('COMMISSION') }} :</strong>
                            </td>
                            <td colspan="2" class="text-muted h5">
                                {{ single_price($order->selling_total - $order->grand_total) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="no-print text-right">
                    <a href="{{ route('invoice.download', $order->id) }}" type="button" class="btn btn-icon btn-light"><i
                            class="las la-print"></i></a>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#assign_deliver_boy').on('change', function() {
            var order_id = {{ $order->id }};
            var delivery_boy = $('#assign_deliver_boy').val();
            $.post('{{ route('orders.delivery-boy-assign') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                delivery_boy: delivery_boy
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Delivery boy has been assigned') }}');
            });
        });
        $('#update_delivery_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            $.post('{{ route('orders.update_delivery_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Delivery status has been updated') }}');
            });
        });
        $('#update_courier').on('change', function() {
            var order_id = {{ $order->id }};
            var courier = $('#update_courier').val();
            $.post('{{ route('orders.update_delivery_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                courier: courier
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Courier has been updated') }}');
            });
        });
        $('#update_payment_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_payment_status').val();
            $.post('{{ route('orders.update_payment_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
            });
        });
        $('#update_tracking_code').on('change', function() {
            var order_id = {{ $order->id }};
            var tracking_code = $('#update_tracking_code').val();
            $.post('{{ route('orders.update_tracking_code') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                tracking_code: tracking_code
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Order tracking code has been updated') }}');
            });
        });
        $('#update_commission_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_commission_status').val();
            $.post('{{ route('orders.update_payment_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                commission: true,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Commission status has been updated') }}');
            });
        });
        $('#update_commission_ref').on('change', function() {
            var order_id = {{ $order->id }};
            var ref = $('#update_commission_ref').val();
            $.post('{{ route('orders.update_payment_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                commission: true,
                ref: ref
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Commission reference has been updated') }}');
            });
        });
    </script>
@endsection