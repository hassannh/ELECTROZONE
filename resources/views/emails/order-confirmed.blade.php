<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Confirmed – ELECTROZONE AKKA</title>
<style>
  body { font-family: 'Arial', sans-serif; background: #F8F8F8; margin: 0; padding: 0; color: #333; }
  .wrapper { max-width: 600px; margin: 0 auto; }
  .header { background: linear-gradient(135deg, #001a3d, #003080); padding: 40px 30px; text-align: center; }
  .brand { color: #fff; font-size: 24px; font-weight: 800; margin-bottom: 4px; }
  .brand em { color: #32CD32; font-style: normal; }
  .tagline { color: rgba(255,255,255,.6); font-size: 13px; }
  .body { background: #fff; padding: 30px; }
  .success-icon { text-align: center; font-size: 48px; margin-bottom: 16px; }
  h1 { text-align: center; font-size: 22px; color: #28a745; margin: 0 0 8px; }
  .subtitle { text-align: center; color: #666; margin-bottom: 24px; font-size: 14px; }
  .order-id { text-align: center; background: #e8f4ff; border-radius: 8px; padding: 12px; margin-bottom: 24px; }
  .order-id span { font-weight: 800; color: #007bff; font-size: 18px; }
  .section-title { font-weight: 700; margin: 20px 0 10px; font-size: 14px; color: #333; text-transform: uppercase; letter-spacing: .05em; }
  table.items { width: 100%; border-collapse: collapse; font-size: 14px; }
  table.items th { background: #F8F8F8; padding: 10px; text-align: left; font-weight: 700; font-size: 12px; color: #666; text-transform: uppercase; }
  table.items td { padding: 10px; border-bottom: 1px solid #eee; }
  .total-row td { font-weight: 800; font-size: 15px; padding-top: 14px; border-top: 2px solid #333; }
  .address-box { background: #F8F8F8; border-radius: 8px; padding: 14px 16px; font-size: 13px; line-height: 1.8; }
  .cta { text-align: center; margin: 28px 0; }
  .cta a { background: #007bff; color: #fff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: 700; font-size: 15px; }
  .footer { background: #0a0a1a; color: rgba(255,255,255,.5); text-align: center; padding: 20px; font-size: 12px; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <div class="brand">⚡ ELECTROZONE <em>AKKA</em></div>
    <div class="tagline">Your Trusted Electronics Store</div>
  </div>
  <div class="body">
    <div class="success-icon">✅</div>
    <h1>Order Confirmed!</h1>
    <p class="subtitle">Hi <strong>{{ $order->customer_name }}</strong>, thank you for your order.</p>
    <div class="order-id">
      Order ID: <span>#{{ strtoupper(substr($order->id, 0, 8)) }}</span>
    </div>

    <div class="section-title">Items Ordered</div>
    <table class="items">
      <thead><tr><th>Product</th><th>Qty</th><th>Price</th></tr></thead>
      <tbody>
        @foreach($order->items as $item)
        <tr>
          <td>{{ $item['name'] }}</td>
          <td>{{ $item['quantity'] }}</td>
          <td>{{ number_format($item['subtotal'], 2) }} MAD</td>
        </tr>
        @endforeach
        <tr>
          <td colspan="2">Shipping</td>
          <td>{{ $order->shipping_cost == 0 ? 'FREE' : number_format($order->shipping_cost, 2) . ' MAD' }}</td>
        </tr>
        <tr class="total-row">
          <td colspan="2">Total</td>
          <td>{{ number_format($order->total_amount, 2) }} MAD</td>
        </tr>
      </tbody>
    </table>

    <div class="section-title">Delivery Address</div>
    <div class="address-box">
      {{ $order->customer_name }}<br>
      {{ $order->shipping_address['address'] }}<br>
      {{ $order->shipping_address['city'] }}
      @if($order->shipping_address['postal_code'])
        {{ $order->shipping_address['postal_code'] }}
      @endif<br>
      📞 {{ $order->customer_phone }}
    </div>

    <p style="font-size:13px;color:#666;margin-top:20px;">
      We will call you at <strong>{{ $order->customer_phone }}</strong> to confirm your delivery details.
      Your order is being processed and will be shipped soon!
    </p>

    <div class="cta">
      <a href="{{ url('/') }}">Back to Store</a>
    </div>
  </div>
  <div class="footer">
    © {{ date('Y') }} ELECTROZONE AKKA – Akka, Morocco<br>
    This email was sent because you placed an order on our website.
  </div>
</div>
</body>
</html>
