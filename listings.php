<?php
require_once 'header.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'create_listing' && isLoggedIn()) {
        $stmt = $pdo->prepare("INSERT INTO listings (user_id, title, description, produce_type, quantity, unit, quality_grade, harvest_date, location, min_price) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            getCurrentUserId(),
            $_POST['title'],
            $_POST['description'],
            $_POST['produce_type'],
            $_POST['quantity'],
            $_POST['unit'],
            $_POST['quality_grade'],
            $_POST['harvest_date'],
            $_POST['location'],
            $_POST['min_price']
        ]);
        $success = "Listing created successfully!";
    }
}

// Determine view mode
$viewMode = isset($_GET['view']) ? $_GET['view'] : (isLoggedIn() ? 'my' : 'all');

// Fetch listings based on view mode
if ($viewMode === 'my' && isLoggedIn()) {
    $stmt = $pdo->prepare("SELECT l.*, 
                          (SELECT COUNT(*) FROM bids WHERE listing_id = l.id) as bid_count 
                          FROM listings l 
                          WHERE l.user_id = ? ORDER BY l.created_at DESC");
    $stmt->execute([getCurrentUserId()]);
    $listings = $stmt->fetchAll();
    $title = "My Listings";
} else {
    $stmt = $pdo->query("SELECT l.*, u.full_name as seller_name FROM listings l 
                         JOIN users u ON l.user_id = u.id 
                         WHERE l.status = 'active' ORDER BY l.created_at DESC");
    $listings = $stmt->fetchAll();
    $title = "Marketplace Listings";
}
?>

<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span><?php echo $title; ?></span>
            <div>
                <?php if (isLoggedIn()): ?>
                    <a href="listings.php?view=my" class="btn <?php echo $viewMode === 'my' ? 'btn-secondary' : ''; ?>">My Listings</a>
                <?php endif; ?>
                <a href="listings.php?view=all" class="btn <?php echo $viewMode === 'all' ? 'btn-secondary' : ''; ?>">All Listings</a>
                <?php if (isLoggedIn() && getCurrentUserType() === 'farmer'): ?>
                    <a href="listings.php?view=create" class="btn <?php echo $viewMode === 'create' ? 'btn-secondary' : ''; ?>">Create Listing</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card-body">
        <?php if ($viewMode === 'create'): ?>
            <?php if (getCurrentUserType() === 'farmer'): ?>
                <h2>Create New Listing</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="create_listing">
                    
                    <div class="form-group">
                        <label for="title">Listing Title</label>
                        <input type="text" id="title" name="title" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="produce_type">Produce Type</label>
                        <input type="text" id="produce_type" name="produce_type" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <div style="display: flex;">
                            <input type="number" id="quantity" name="quantity" class="form-control" step="0.01" required style="flex: 2;">
                            <select id="unit" name="unit" class="form-control" required style="flex: 1; margin-left: 10px;">
                                <option value="kg">kg</option>
                                <option value="lb">lb</option>
                                <option value="ton">ton</option>
                                <option value="bushel">bushel</option>
                                <option value="unit">unit</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="quality_grade">Quality Grade</label>
                        <select id="quality_grade" name="quality_grade" class="form-control">
                            <option value="">Select Grade</option>
                            <option value="Organic">Organic</option>
                            <option value="Premium">Premium</option>
                            <option value="Grade A">Grade A</option>
                            <option value="Grade B">Grade B</option>
                            <option value="Standard">Standard</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="harvest_date">Harvest Date</label>
                        <input type="date" id="harvest_date" name="harvest_date" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="min_price">Minimum Price ($)</label>
                        <input type="number" id="min_price" name="min_price" class="form-control" step="0.01" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Create Listing</button>
                </form>
            <?php else: ?>
                <div class="alert alert-error">Only farmers can create listings.</div>
            <?php endif; ?>
        <?php else: ?>
            <div class="listing-grid">
                <?php if (count($listings) > 0): ?>
                    <?php foreach ($listings as $listing): ?>
                        <div class="listing-card">
                            <div class="listing-card-header">
                                <h3><?php echo htmlspecialchars($listing['title']); ?></h3>
                            </div>
                            <div class="listing-card-body">
                                <p><strong>Produce:</strong> <?php echo htmlspecialchars($listing['produce_type']); ?></p>
                                <p><strong>Quantity:</strong> <?php echo htmlspecialchars($listing['quantity'] . ' ' . $listing['unit']); ?></p>
                                <p><strong>Quality:</strong> <?php echo htmlspecialchars($listing['quality_grade']); ?></p>
                                <p><strong>Harvest Date:</strong> <?php echo htmlspecialchars($listing['harvest_date']); ?></p>
                                <p><strong>Location:</strong> <?php echo htmlspecialchars($listing['location']); ?></p>
                                <p><strong>Minimum Price:</strong> $<?php echo htmlspecialchars($listing['min_price']); ?></p>
                                <?php if ($viewMode === 'all'): ?>
                                    <p><strong>Seller:</strong> <?php echo htmlspecialchars($listing['seller_name']); ?></p>
                                <?php else: ?>
                                    <p><strong>Bids:</strong> <?php echo $listing['bid_count']; ?></p>
                                    <p><strong>Status:</strong> <?php echo ucfirst($listing['status']); ?></p>
                                <?php endif; ?>
                                <p><?php echo htmlspecialchars($listing['description']); ?></p>
                            </div>
                            <div class="listing-card-footer">
                                <?php if ($viewMode === 'all' && canUserBid($listing['user_id'])): ?>
                                    <a href="bids.php?action=bid&listing_id=<?php echo $listing['id']; ?>" class="btn btn-primary">Place Bid</a>
                                <?php endif; ?>
                                <?php if ($viewMode === 'my' && canUserManageBids($listing['user_id'])): ?>
                                    <a href="bids.php?listing_id=<?php echo $listing['id']; ?>" class="btn btn-secondary">View Bids</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No listings found.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'footer.php'; ?>
