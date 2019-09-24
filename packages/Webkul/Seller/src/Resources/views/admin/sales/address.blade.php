{{ $address->name }}</br>
{{ $address->area }}</br>
{{ $address->block }}</br>
{{ $address->address1 }}</br>
{{ $address->building }}</br>
{{ $address->floor }} {{ $address->flat }}</br>

{{-- {{ core()->country_name($address->city) }}</br></br> --}}
{{ __('shop::app.checkout.onepage.contact') }} : {{ $address->phone }}