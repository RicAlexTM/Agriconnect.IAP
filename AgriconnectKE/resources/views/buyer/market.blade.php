@extends('layouts.app')

@section('title', 'Marketplace - AgriconnectKE')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Marketplace</h1>
                    <p class="text-muted mb-0">Discover fresh produce from local farmers</p>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?category=vegetables">Vegetables</a></li>
                        <li><a class="dropdown-item" href="?category=fruits">Fruits</a></li>
                        <li><a class="dropdown-item" href="?category=grains">Grains</a></li>
                        <li><a class="dropdown-item" href="?category=dairy">Dairy</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('buyer.market') }}">Show All</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Search Products</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" placeholder="Search for products..." id="searchInput">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Category</label>
                            <select class="form-select" id="categoryFilter">
                                <option value="">All Categories</option>
                                <option value="vegetables">Vegetables</option>
                                <option value="fruits">Fruits</option>
                                <option value="grains">Grains & Cereals</option>
                                <option value="dairy">Dairy Products</option>
                                <option value="poultry">Poultry</option>
                                <option value="livestock">Livestock</option>
                                <option value="herbs">Herbs & Spices</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row" id="productsContainer">
        @forelse($products as $product)
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4 product-item" 
             data-product-id="{{ $product->id }}" 
             data-name="{{ strtolower($product->name) }}" 
             data-category="{{ $product->category }}"
             data-price="{{ $product->price }}">
            
            <div class="card product-card h-100 border-0 shadow-sm hover-shadow">
                <!-- Product Image -->
                <div class="product-image-container position-relative">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             class="card-img-top product-image" 
                             alt="{{ $product->name }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top product-image-placeholder d-flex align-items-center justify-content-center bg-light"
                             style="height: 200px;">
                            <div class="text-center">
                                <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                <p class="small text-muted mb-0">No Image</p>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Status Badges -->
                    <div class="position-absolute top-0 start-0 m-2">
                        @if(!$product->is_available)
                            <span class="badge bg-danger">Out of Stock</span>
                        @elseif($product->quantity < 10)
                            <span class="badge bg-warning">Low Stock</span>
                        @endif
                    </div>
                    
                    @if($product->accepts_bids)
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-gavel me-1"></i>Bids
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Card Body -->
                <div class="card-body d-flex flex-column">
                    <!-- Product Info -->
                    <div class="mb-2">
                        <h6 class="card-title fw-semibold mb-1 text-dark">{{ $product->name }}</h6>
                        <p class="card-text text-muted small mb-2 line-clamp-2">
                            {{ Str::limit($product->description, 70) }}
                        </p>
                    </div>

                    <!-- Farmer Info -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center text-muted small">
                            <i class="fas fa-user me-1"></i>
                            <span class="fw-medium">{{ $product->farmer->name }}</span>
                        </div>
                        <div class="d-flex align-items-center text-muted small mt-1">
                            <i class="fas fa-tag me-1"></i>
                            <span class="text-capitalize">{{ $product->category }}</span>
                        </div>
                    </div>

                    <!-- Pricing and Stock -->
                    <div class="mt-auto">
                        

                        <!-- Action Buttons -->
                        <!-- Action Buttons -->
<div class="mt-auto">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <span class="h5 text-success fw-bold mb-0">Ksh {{ number_format($product->price, 2) }}</span>
            <small class="text-muted d-block">per unit</small>
        </div>
        <div class="text-end">
            <small class="{{ $product->quantity > 10 ? 'text-success' : 'text-warning' }} fw-medium">
                <i class="fas fa-box me-1"></i>{{ $product->quantity }} available
            </small>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-grid gap-2">
        @if($product->is_available && $product->quantity > 0)
            <div class="btn-group" role="group">
                @if($product->accepts_bids)
                <button class="btn btn-outline-warning btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#bidModal{{ $product->id }}">
                    <i class="fas fa-gavel me-1"></i>Bid
                </button>
                @endif
                
                <!-- Add to Cart Button -->
                <button class="btn btn-outline-primary btn-sm" 
                        onclick="addToCart({{ $product->id }})"
                        id="addToCartBtn{{ $product->id }}">
                    <i class="fas fa-cart-plus me-1"></i>Add to Cart
                </button>
                
                <!-- Buy Now Button -->
                <button class="btn btn-success btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#purchaseModal{{ $product->id }}">
                    <i class="fas fa-bolt me-1"></i>Buy Now
                </button>
            </div>
        @else
            <button class="btn btn-secondary btn-sm" disabled>
                <i class="fas fa-times me-1"></i>Out of Stock
            </button>
        @endif
    </div>
</div>
                    </div>
                </div>
            </div>

            <!-- Bid Modal -->
            <div class="modal fade" id="bidModal{{ $product->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <form action="{{ route('buyer.place-bid', $product) }}" method="POST">
                            @csrf
                            <div class="modal-header bg-light">
                                <h5 class="modal-title fw-semibold">Place Bid - {{ $product->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                @if($product->image)
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="img-fluid rounded" 
                                             style="max-height: 120px;">
                                    </div>
                                @endif
                                
                                <div class="alert alert-info border-0">
                                    <div class="d-flex">
                                        <i class="fas fa-info-circle me-2 mt-1"></i>
                                        <div class="small">
                                            The farmer will review your bid and may accept or reject it.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Current Price</label>
                                    <div class="form-control bg-light">Ksh {{ number_format($product->price, 2) }}</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="amount{{ $product->id }}" class="form-label fw-medium">Your Bid Amount (Ksh)</label>
                                    <input type="number" class="form-control" id="amount{{ $product->id }}" 
                                           name="amount" step="0.01" min="0.01" 
                                           value="{{ $product->price }}" required>
                                    <div class="form-text">Enter your proposed price for this product</div>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-paper-plane me-1"></i>Submit Bid
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Purchase Modal -->
            <div class="modal fade" id="purchaseModal{{ $product->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <form action="{{ route('buyer.purchase', $product) }}" method="POST" id="purchaseForm{{ $product->id }}">
                            @csrf
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title fw-semibold">Purchase - {{ $product->name }}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                @if($product->image)
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="img-fluid rounded" 
                                             style="max-height: 120px;">
                                    </div>
                                @endif
                                
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="border rounded p-2 text-center bg-light">
                                            <small class="text-muted d-block">Price</small>
                                            <strong class="text-success">Ksh {{ number_format($product->price, 2) }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="border rounded p-2 text-center bg-light">
                                            <small class="text-muted d-block">Available</small>
                                            <strong>{{ $product->quantity }} units</strong>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <label for="quantity{{ $product->id }}" class="form-label fw-medium">Quantity</label>
                                    <input type="number" class="form-control" id="quantity{{ $product->id }}" 
                                           name="quantity" min="1" max="{{ $product->quantity }}" 
                                           value="1" required>
                                    <div class="form-text">Maximum available: {{ $product->quantity }} units</div>
                                </div>
                                
                                <div class="alert alert-warning border-0 mt-3">
                                    <div class="d-flex">
                                        <i class="fas fa-truck me-2 mt-1"></i>
                                        <div class="small">
                                            Delivery cost will be calculated based on your location
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="border rounded p-3 bg-light">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Unit Price:</span>
                                        <span>Ksh {{ number_format($product->price, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Quantity:</span>
                                        <span id="quantityDisplay{{ $product->id }}">1</span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between fw-semibold">
                                        <span>Subtotal:</span>
                                        <span class="text-success">Ksh <span id="totalPrice{{ $product->id }}">{{ number_format($product->price, 2) }}</span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success" id="purchaseBtn{{ $product->id }}">
                                    <i class="fas fa-credit-card me-1"></i>Proceed to Checkout
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-search fa-4x text-muted mb-3"></i>
                    <h3 class="text-muted">No Products Available</h3>
                    <p class="text-muted mb-4">There are no products available in the marketplace at the moment.</p>
                    <a href="{{ route('buyer.market') }}" class="btn btn-success">
                        <i class="fas fa-refresh me-1"></i>Refresh Page
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .product-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }

    
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    
    .hover-shadow:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    
    .product-image-container {
        overflow: hidden;
    }
    
    .product-image {
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .modal-content {
        border-radius: 12px;
    }
    
    .btn {
        border-radius: 8px;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
    }
    
    .badge {
        border-radius: 6px;
        font-size: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    console.log('Marketplace loaded successfully');
