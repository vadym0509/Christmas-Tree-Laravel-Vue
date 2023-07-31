@component('mail::message')
# Dear **{{$data['data']['customer']['name']}}**,

Thank you for your order. Please find your order details enclosed below:

@component('mail::table')
    | # |   | Product | Quantity | Price    |
    |:-:|:----:|:----:|:--------:|:--------:|
    @foreach($data['included']['items'] as $item)
        | {{$loop->iteration}} | <img alt="{{$item['name']}}" src="{{$item['fullProduct']['relationships']['main_image']['link']['href']}}" width="70"/>  | {{$item['name']}} @if($item['selectedOption']) - {{$item['selectedOption']['name']}} @endif| {{$item['quantity']}} | {{$item['value']['amount']/100}} |
    @endforeach
@endcomponent

**Delivery: £{{$data['data']['delivery']['value']['amount']/100}}**

**Total: £{{$data['data']['meta']['display_price']['with_tax']['amount']/100}}**

@if($data['data']['shipping_address']['instructions'])
{{$data['data']['shipping_address']['instructions']}}
@endif

Thank you,<br>

{{ config('app.name') }}

@endcomponent
