<?php
require_once 'header.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'place_bid':
                if (isLoggedIn()) {
                    $stmt = $pdo->prepare("INSERT INTO bids (listing_id, user_id, bid_amount, message) VALUES (?, ?, ?, ?)");
                    $stmt->execute([
                        $_POST['listing_id'],
                        getCurrentUserId(),
                        $_POST['bid_amount'],
                        $_POST['message']
                    ]);
                    $success = "Bid placed successfully!";
                }
                break;
                
            case 'accept_bid':
                if (isLoggedIn()) {
                    // Update bid status to accepted
                    $stmt = $pdo->prepare("UPDATE bids SET status = 'accepted' WHERE id = ?");
                    $stmt->execute([$_POST['bid_id']]);
                    
                    // Update listing status to sold
                    $stmt = $pdo->prepare("UPDATE listings SET status = 'sold' WHERE id = ?");
                    $stmt->execute([$_POST['listing_id']]);
                    
                    // Reject all other bids for this listing
                    $stmt = $pdo->prepare("UPDATE bids SET status = 'rejected' WHERE listing_id = ? AND id != ?");
                    $stmt->execute([$_POST['listing_id'], $_POST['bid_id']]);
                    
                    $success = "Bid accepted successfully!";
                }
                break;
        }
    }
}

// Determine view mode
$viewMode = isset($_GET['view']) ? $_GET['view'] : (isLoggedIn() ? 'my' : 'all');

// Check if we're viewing bids for a specific listing
$listingId = isset($_GET['listing_id']) ? $_GET['listing_id'] : null;

// Check if we're placing a bid on a specific listing
$bidListingId = isset($_GET['action']) && $_GET['action'] === 'bid' && isset($_GET['listing_id']) ? $_GET['listing_id'] : null;

// Fetch data based on view mode
if ($listingId) {
    // View bids for a specific listing
    $stmt = $pdo->prepare("SELECT * FROM listings WHERE id = ?");
    $stmt->execute([$listingId]);
    $listing = $stmt->fetch();
    
    $stmt = $pdo->prepare("SELECT b.*, u.full_name FROM bids b 
                          JOIN users u ON b.user_id = u.id 
                          WHERE b.listing_id = ? ORDER BY b.bid_amount DESC");
    $stmt->execute([$listingId]);
    $bids = $stmt->fetchAll();
    $title = "Bids for: " . htmlspecialchars($listing['title']);
} elseif ($bidListingId) {
    // Place a bid on a specific listing
    $stmt = $pdo->prepare("SELECT * FROM listings WHERE id = ?");
    $stmt->execute([$bidListingId]);
    $listing = $stmt->fetch();
    $title = "Place Bid on: " . htmlspecialchars($listing['title']);
} elseif ($viewMode === 'my' && isLoggedIn()) {
    // View user's bids
    $stmt = $pdo->prepare("SELECT b.*, l.title as listing_title, l.user_id as seller_id 
                          FROM bids b 
                          JOIN listings l ON b.listing_id = l.id 
                          WHERE b.user_id = ? ORDER BY b.created_at DESC");
    $stmt->execute([getCurrentUserId()]);
    $userBids = $stmt->fetchAll();
    $title = "My Bids";
} else {
    // View all bids (admin view - not implemented for simplicity)
    $title = "All Bids";
}
?>

<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span><?php echo $title; ?></span>
            <div>
                <?php if (isLoggedIn()): ?>
                    <a href="bids.php?view=my" class="btn <?php echo !$listingId && !$bidListingId && $viewMode === 'my' ? 'btn-secondary' : ''; ?>">My Bids</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card-body">
        <?php if ($bidListingId): ?>
            <?php if (canUserBid($listing['user_id'])): ?>
                <h2>Place Bid</h2>
                <div class="listing-card" style="margin-bottom: 20px;">
                    <div class="listing-card-header">
                        <h3><?php echo htmlspecialchars($listing['title']); ?></h3>
                    </div>
                    <div class="listing-card-body">
                        <p><strong>Produce:</strong> <?php echo htmlspecialchars($listing['produce_type']); ?></p>
                        <p><strong>Quantity:</strong> <?php echo htmlspecialchars($listing['quantity'] . ' ' . $listing['unit']); ?></p>
                        <p><strong>Quality:</strong> <?php echo htmlspecialchars($listing['quality_grade']); ?></p>
                        <p><strong>Minimum Price:</strong> $<?php echo htmlspecialchars($listing['min_price']); ?></p>
                    </div>
                </div>
                
                <form method="POST">
                    <input type="hidden" name="action" value="place_bid">
                    <input type="hidden" name="listing_id" value="<?php echo $bidListingId; ?>">
                    
                    <div class="form-group">
                        <label for="bid_amount">Bid Amount ($)</label>
                        <input type="number" id="bid_amount" name="bid_amount" class="form-control" 
                               step="0.01" min="<?php echo $listing['min_price']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message to Seller (Optional)</label>
                        <textarea id="message" name="message" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Submit Bid</button>
                    <a href="listings.php?view=all" class="btn">Cancel</a>
                </form>
            <?php else: ?>
                <div class="alert alert-error">You cannot bid on this listing.</div>
            <?php endif; ?>
        <?php elseif ($listingId): ?>
            <?php if (canUserManageBids($listing['user_id'])): ?>
                <h2>Bid Management</h2>
                <div class="listing-card" style="margin-bottom: 20px;">
                    <div class="listing-card-header">
                        <h3><?php echo htmlspecialchars($listing['title']); ?></h3>
                    </div>
                    <div class="listing-card-body">
                        <p><strong>Produce:</strong> <?php echo htmlspecialchars($listing['produce_type']); ?></p>
                        <p><strong>Quantity:</strong> <?php echo htmlspecialchars($listing['quantity'] . ' ' . $listing['unit']); ?></p>
                        <p><strong>Quality:</strong> <?php echo htmlspecialchars($listing['quality_grade']); ?></p>
                        <p><strong>Minimum Price:</strong> $<?php echo htmlspecialchars($listing['min_price']); ?></p>
                        <p><strong>Status:</strong> <?php echo ucfirst($listing['status']); ?></p>
                    </div>
                </div>
                
                <?php if (count($bids) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Bidder</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bids as $bid): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($bid['full_name']); ?></td>
                                    <td>$<?php echo htmlspecialchars($bid['bid_amount']); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($bid['created_at'])); ?></td>
                                    <td><?php echo ucfirst($bid['status']); ?></td>
                                    <td>
                                        <?php if ($bid['status'] == 'pending' && $listing['status'] == 'active'): ?>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="action" value="accept_bid">
                                                <input type="hidden" name="bid_id" value="<?php echo $bid['id']; ?>">
                                                <input type="hidden" name="listing_id" value="<?php echo $listingId; ?>">
                                                <button type="submit" class="btn btn-primary">Accept</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if (!empty($bid['message'])): ?>
                                    <tr>
                                        <td colspan="5"><strong>Message:</strong> <?php echo htmlspecialchars($bid['message']); ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No bids have been placed for this listing yet.</p>
                <?php endif; ?>
                
                <a href="listings.php?view=my" class="btn">Back to My Listings</a>
            <?php else: ?>
                <div class="alert alert-error">You do not have permission to view these bids.</div>
            <?php endif; ?>
        <?php elseif ($viewMode === 'my' && isLoggedIn()): ?>
            <h2>My Bid History</h2>
            <?php if (count($userBids) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Listing</th>
                            <th>Bid Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userBids as $bid): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($bid['listing_title']); ?></td>
                                <td>$<?php echo htmlspecialchars($bid['bid_amount']); ?></td>
                                <td><?php echo ucfirst($bid['status']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($bid['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You haven't placed any bids yet.</p>
                <a href="listings.php?view=all" class="btn btn-primary">Browse Listings</a>
            <?php endif; ?>
        <?php else: ?>
            <p>Please select a view option.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'footer.php'; ?>
