@extends('layouts.app')

@section('title', 'Checkout – ELECTROZONE AKKA')

@section('content')
<div class="container">
    <div class="page-header-simple">
        <h1>💳 Checkout</h1>
        <div class="checkout-steps">
            <span class="step active">1. Shipping</span>
            <span>›</span>
            <span class="step">2. Review & Pay</span>
        </div>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST" class="checkout-layout" id="checkoutForm">
        @csrf
        <!-- Shipping Form -->
        <div class="checkout-form">
            <div class="form-section">
                <h3>📦 Shipping Information</h3>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="full_name">Full Name *</label>
                        <input type="text" id="full_name" name="full_name" 
                               value="{{ old('full_name') }}" 
                               class="input {{ $errors->has('full_name') ? 'input-error' : '' }}"
                               placeholder="Ahmed El Mansouri" required>
                        @error('full_name')<span class="error-msg">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" 
                               value="{{ old('phone') }}"
                               class="input {{ $errors->has('phone') ? 'input-error' : '' }}"
                               placeholder="+212 6XX-XXXXXX" required>
                        @error('phone')<span class="error-msg">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" 
                           value="{{ old('email') }}"
                           class="input {{ $errors->has('email') ? 'input-error' : '' }}"
                           placeholder="ahmed@example.com" required>
                    @error('email')<span class="error-msg">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="address">Street Address *</label>
                    <textarea id="address" name="address" rows="2"
                              class="input textarea {{ $errors->has('address') ? 'input-error' : '' }}"
                              placeholder="Street, Building, Apartment..." required>{{ old('address') }}</textarea>
                    @error('address')<span class="error-msg">{{ $message }}</span>@enderror
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="city">City *</label>
                        <input type="text" id="city" name="city" 
                               value="{{ old('city') }}"
                               class="input {{ $errors->has('city') ? 'input-error' : '' }}"
                               placeholder="Akka" required>
                        @error('city')<span class="error-msg">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="postal_code">Postal Code</label>
                        <input type="text" id="postal_code" name="postal_code" 
                               value="{{ old('postal_code') }}"
                               class="input" placeholder="e.g. 85450">
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">Order Notes (Optional)</label>
                    <textarea id="notes" name="notes" rows="2" class="input textarea"
                              placeholder="Special delivery instructions...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="form-section">
                <h3>💳 Payment Method</h3>
                <div class="payment-options">
                    <label class="payment-option selected">
                        <input type="radio" name="payment_method" value="cod" checked>
                        <div class="payment-option-content">
                            <span class="payment-icon">💵</span>
                            <div>
                                <strong>Cash on Delivery</strong>
                                <small>Pay when your order arrives</small>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="checkout-summary">
            <h3>📋 Order Review</h3>
            @foreach($cart as $id => $item)
            <div class="checkout-item">
                <div class="checkout-item-qty">{{ $item['quantity'] }}×</div>
                <div class="checkout-item-info">
                    <div class="checkout-item-name">{{ $item['name'] }}</div>
                    <div class="checkout-item-brand">{{ $item['brand'] }}</div>
                </div>
                <div class="checkout-item-price">{{ number_format($item['price'] * $item['quantity'], 2) }} MAD</div>
            </div>
            @endforeach

            <div class="checkout-divider"></div>

            <div class="summary-row">
                <span>Subtotal</span>
                <span>{{ number_format($subtotal, 2) }} MAD</span>
            </div>
            <div class="summary-row">
                <span>Shipping</span>
                <span>{{ $shippingCost == 0 ? '🎉 FREE' : number_format($shippingCost, 2) . ' MAD' }}</span>
            </div>
            <div class="summary-total">
                <span>Total</span>
                <span>{{ number_format($total, 2) }} MAD</span>
            </div>

            <button type="submit" class="btn btn-accent btn-lg btn-block">
                ✅ Place Order
            </button>
            <p class="checkout-note">🔒 Your personal information is safe with us</p>
        </div>
    </form>
</div>
@endsection
