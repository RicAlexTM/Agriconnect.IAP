<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('title', 'Welcome to AgriconnectKE - Connecting Farmers and Buyers')

@section('styles')
<style>
    .hero-section {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('/images/hero-bg.jpg');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 100px 0;
    }
    
    .feature-card {
        transition: transform 0.3s;
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
    }
    
    .feature-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: #198754;
    }
    
    .product-card {
        transition: transform 0.3s;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
    }
    
    .testimonial-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
    }
    
    .stat-card {
        background: #198754;
        color: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container text-center">
        <h1 class="display-4 mb-4">Welcome to AgriconnectKE</h1>
        <p class="lead mb-4">Connecting farmers directly with buyers for fresher produce and better prices</p>
        @guest
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('register') }}" class="btn btn-success btn-lg">Get Started</a>
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Sign In</a>
        </div>
        @endguest
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Why Choose AgriconnectKE?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon">🌾</div>
                        <h5 class="card-title">Direct Farm-to-Table</h5>
                        <p class="card-text">Connect directly with local farmers for the freshest produce at the best prices.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon">🚚</div>
                        <h5 class="card-title">Real-Time Delivery Tracking</h5>
                        <p class="card-text">Track your orders in real-time with our advanced GPS tracking system.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon">💰</div>
                        <h5 class="card-title">Competitive Pricing</h5>
                        <p class="card-text">Place bids or buy directly at fair market prices.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Featured Products</h2>
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-md-3">
                <div class="card product-card h-100">
                    <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-success">KES {{ number_format($product->price, 2) }}</p>
                        <small class="text-muted">by {{ $product->farmer->name }}</small>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-success w-100">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-success">View All Products</a>
        </div>
    </div>
</section>

<!-- Statistics -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <h3 class="display-4">{{ $stats['farmers'] }}</h3>
                    <p class="mb-0">Farmers</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h3 class="display-4">{{ $stats['products'] }}</h3>
                    <p class="mb-0">Products</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h3 class="display-4">{{ $stats['buyers'] }}</h3>
                    <p class="mb-0">Buyers</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h3 class="display-4">{{ $stats['deliveries'] }}</h3>
                    <p class="mb-0">Deliveries</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">How It Works</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <div class="display-4 text-success mb-3">1</div>
                    <h4>For Farmers</h4>
                    <p>List your products, set prices, and accept bids from buyers across the country.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="display-4 text-success mb-3">2</div>
                    <h4>For Buyers</h4>
                    <p>Browse products, place bids or buy directly, and track your deliveries in real-time.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="display-4 text-success mb-3">3</div>
                    <h4>For Drivers</h4>
                    <p>Accept delivery requests, use our GPS navigation, and earn money delivering products.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">What Our Users Say</h2>
        <div class="row g-4">
            @foreach($testimonials as $testimonial)
            <div class="col-md-4">
                <div class="testimonial-card">
                    <p class="mb-3">{{ $testimonial->content }}</p>
                    <div class="d-flex align-items-center">
                        <img src="{{ $testimonial->user->avatar }}" alt="{{ $testimonial->user->name }}" 
                             class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0">{{ $testimonial->user->name }}</h6>
                            <small class="text-muted">{{ ucfirst($testimonial->user->role) }}</small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-success text-white">
    <div class="container text-center">
        <h2 class="mb-4">Ready to Get Started?</h2>
        <p class="lead mb-4">Join our growing community of farmers and buyers today!</p>
        @guest
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('register') }}" class="btn btn-light btn-lg">Create Account</a>
            <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg">Learn More</a>
        </div>
        @endguest
    </div>
</section>
@endsection