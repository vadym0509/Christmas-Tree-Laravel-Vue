@component('mail::message')
# New Order

Customer name: **{{$data['data']['customer']['name']}}**
Customer e-mail: {{$data['data']['customer']['email']}}

@component('mail::table')
    | # | Shipping Address | Billing Address |
    |:---:|:--------:|:--------:|
    | Name | {{ $data['data']['shipping_address']['first_name'] }} {{ $data['data']['shipping_address']['last_name'] }} | {{ $data['data']['billing_address']['first_name'] }} {{ $data['data']['billing_address']['last_name'] }} |
    | Line 1 | {{ $data['data']['shipping_address']['line_1'] }} | {{ $data['data']['billing_address']['line_1'] }} |
    | Line 2 | {{ $data['data']['shipping_address']['line_2'] }} | {{ $data['data']['billing_address']['line_2'] }} |
    | City | {{ $data['data']['shipping_address']['city'] }} | {{ $data['data']['billing_address']['city'] }} |
    | Postcode | {{ $data['data']['shipping_address']['postcode'] }} | {{ $data['data']['billing_address']['postcode'] }} |
    | County | {{ $data['data']['shipping_address']['county'] }} | {{ $data['data']['billing_address']['county'] }} |
    | Country | {{ $data['data']['shipping_address']['country'] }} | {{ $data['data']['billing_address']['country'] }} |
    | Phone | {{ $data['data']['shipping_address']['phone_number'] }} | |
@endcomponent

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

@component('mail::button', ['url' => 'https://dashboard.moltin.com/app/orders/'.$data['data']['id']])
Link to the Order
@endcomponent
@endcomponent
