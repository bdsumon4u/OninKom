<!-- New Address Modal -->
<div class="modal fade" id="new-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate('New Address') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-default" role="form" action="{{ route('addresses.store') }}" method="POST">
                @csrf
                <div class="modal-body c-scrollbar-light">
                    <div class="p-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('Customer Name')}}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('Customer name')}}"name="name" required>
                            </div>
                        </div>
                        <!-- Address -->
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('Address')}}</label>
                            </div>
                            <div class="col-md-8">
                                <textarea class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Address')}}" rows="2" name="address" required></textarea>
                            </div>
                        </div>
                        
                        @php($country = \App\Models\Country::where('name', 'Bangladesh')->firstOrFail())
                        <input type="hidden" name="country_id" value="{{ $country->id }}">
                        <!-- Country -->
                        {{-- <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('Country')}}</label>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <select class="form-control aiz-selectpicker rounded-0" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="country_id" required>
                                        <option value="">{{ translate('Select your country') }}</option>
                                        @foreach (\App\Models\Country::where('status', 1)->get() as $key => $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> --}}

                        <!-- State -->
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('State')}}</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="state_id" required>
                                    <option value="">{{ translate('Select State') }}</option>
                                    @foreach (\App\Models\State::where('country_id', $country->id)->where('status', 1)->get() as $key => $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- City -->
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('City')}}</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="city_id" required>

                                </select>
                            </div>
                        </div>

                        @if (get_setting('google_map') == 1)
                            <!-- Google Map -->
                            <div class="row mt-3 mb-3">
                                <input id="searchInput" class="controls" type="text" placeholder="{{translate('Enter a location')}}">
                                <div id="map"></div>
                                <ul id="geoData">
                                    <li style="display: none;">Full Address: <span id="location"></span></li>
                                    <li style="display: none;">Postal Code: <span id="postal_code"></span></li>
                                    <li style="display: none;">Country: <span id="country"></span></li>
                                    <li style="display: none;">Latitude: <span id="lat"></span></li>
                                    <li style="display: none;">Longitude: <span id="lon"></span></li>
                                </ul>
                            </div>
                            <!-- Longitude -->
                            <div class="row">
                                <div class="col-md-4" id="">
                                    <label for="exampleInputuname">{{ translate('Longitude')}}</label>
                                </div>
                                <div class="col-md-8" id="">
                                    <input type="text" class="form-control mb-3 rounded-0" id="longitude" name="longitude" readonly="">
                                </div>
                            </div>
                            <!-- Latitude -->
                            <div class="row">
                                <div class="col-md-4" id="">
                                    <label for="exampleInputuname">{{ translate('Latitude')}}</label>
                                </div>
                                <div class="col-md-8" id="">
                                    <input type="text" class="form-control mb-3 rounded-0" id="latitude" name="latitude" readonly="">
                                </div>
                            </div>
                        @endif
                        
                        <!-- Postal code -->
                        {{-- <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('Postal code')}}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Postal Code')}}" name="postal_code" value="" required>
                            </div>
                        </div> --}}

                        <!-- Phone -->
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('Phone')}}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('+880')}}" name="phone" value="" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate(config('app.name').' Delivery Charge')}}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3" name="app_delivery_charge" required readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('Your Customer Delivery Charge')}}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3" name="customer_delivery_charge" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('Courier')}}</label>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <select class="form-control aiz-selectpicker" data-placeholder="{{ translate('Select Preferred Courier') }}" name="courier" required>
                                        <option value="any">{{ translate('Select Courier') }}</option>
                                        @foreach (config('other.couriers') as $courier)
                                            <option value="{{ $courier }}">{{ $courier }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('Instruction')}}</label>
                            </div>
                            <div class="col-md-8">
                                <textarea class="form-control mb-3" placeholder="{{ translate('Share Order Related Instruction If You Have Any.')}}" rows="2" name="instruction"></textarea>
                            </div>
                        </div>
                        <!-- Save button -->
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary rounded-0 w-150px">{{translate('Save')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="edit-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate('New Address') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body c-scrollbar-light" id="edit_modal_body">

            </div>
        </div>
    </div>
</div>

@section('script')
    <script type="text/javascript">
        function add_new_address(){
            $('#new-address-modal').modal('show');
        }

        function edit_address(address) {
            var url = '{{ route("addresses.edit", ":id") }}';
            url = url.replace(':id', address);
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: 'GET',
                success: function (response) {
                    $('#edit_modal_body').html(response.html);
                    $('#edit-address-modal').modal('show');
                    AIZ.plugins.bootstrapSelect('refresh');

                    @if (get_setting('google_map') == 1)
                        var lat     = -33.8688;
                        var long    = 151.2195;

                        if(response.data.address_data.latitude && response.data.address_data.longitude) {
                            lat     = parseFloat(response.data.address_data.latitude);
                            long    = parseFloat(response.data.address_data.longitude);
                        }

                        initialize(lat, long, 'edit_');
                    @endif
                }
            });
        }
        
        $(document).on('change', '[name=country_id]', function() {
            var country_id = $(this).val();
            get_states(country_id);
        });

        $(document).on('change', '[name=state_id]', function() {
            var state_id = $(this).val();
            get_city(state_id);
        });
        $(document).on('change', '[name=city_id]', function() {
            var city_id = $(this).val();
            delivery_charge(city_id);
        });
        function get_states(country_id) {
            $('[name="state"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-state')}}",
                type: 'POST',
                data: {
                    country_id  : country_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="state_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }
        function delivery_charge(city_id) {
            $('[name="app_delivery_charge"]').val("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('delivery-charge')}}",
                type: 'POST',
                data: {
                    city_id: city_id
                },
                success: function (response) {
                    $('[name="app_delivery_charge"]').val(response);
                }
            });
        }
        function get_city(state_id) {
            $('[name="city"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-city')}}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="city_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }
    </script>

    
    @if (get_setting('google_map') == 1)
        @include('frontend.partials.google_map')
    @endif
@endsection