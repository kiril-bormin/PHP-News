
<header class="main-header">
    <div class="header-container">
        <a href="index.php" class="logo-section">
            <img src="/images/logo.png" alt="PHP News Logo" class="site-logo">
            <span class="site-name">PHP News </span>
        </a>

        <nav class="header-nav">

        </nav>
    </div>
</header>

<?php
require_once 'db.php';

try {
    // Get top 4 most common categories
    $stmt = $pdo->query("
        SELECT category, COUNT(*) as count
        FROM articles
        WHERE category IS NOT NULL AND category != ''
        GROUP BY category
        ORDER BY count DESC
        LIMIT 4
    ");
    $topCategories = $stmt->fetchAll();

    // Get all other categories for dropdown
    $stmt = $pdo->query("
        SELECT DISTINCT category
        FROM articles
        WHERE category IS NOT NULL AND category != ''
        ORDER BY category
    ");
    $allCategories = $stmt->fetchAll();

} catch (Exception $e) {
    $topCategories = [];
    $allCategories = [];
}

$selectedCategory = $_GET['category'] ?? null;
?>

<div class="category-filter">
    <div class="filter-container">
        <?php foreach ($topCategories as $cat): ?>
            <a href="index.php?category=<?= urlencode($cat['category']) ?>"
               class="category-btn <?= $selectedCategory === $cat['category'] ? 'active' : '' ?>">
                <?= htmlspecialchars($cat['category']) ?>
            </a>
        <?php endforeach; ?>

        <!-- Dropdown for other categories -->
        <div class="category-dropdown">
            <button class="category-btn dropdown-toggle <?= $selectedCategory && !in_array(['category' => $selectedCategory], $topCategories) ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="5" r="1"></circle>
                    <circle cx="12" cy="12" r="1"></circle>
                    <circle cx="12" cy="19" r="1"></circle>
                </svg>
            </button>
            <div class="dropdown-menu">
                <a href="index.php" class="dropdown-item <?= !$selectedCategory ? 'active' : '' ?>">All Categories</a>
                <?php
                $topCategoryNames = array_column($topCategories, 'category');
                foreach ($allCategories as $cat):
                    if (!in_array($cat['category'], $topCategoryNames)):
                ?>
                    <a href="index.php?category=<?= urlencode($cat['category']) ?>"
                       class="dropdown-item <?= $selectedCategory === $cat['category'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($cat['category']) ?>
                    </a>
                <?php endif; endforeach; ?>
            </div>
        </div>
    </div>
</div>