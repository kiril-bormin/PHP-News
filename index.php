<?php
require_once 'db.php';

try {
    $selectedCategory = $_GET['category'] ?? null;

    if ($selectedCategory) {
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE category = ? ORDER BY date DESC");
        $stmt->execute([$selectedCategory]);
    } else {
        $stmt = $pdo->query("SELECT * FROM articles ORDER BY date DESC");
    }
    $articles = $stmt->fetchAll();
} catch (Exception $e) {
    $articles = [];
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>No idea</title>
    <link rel="stylesheet" href="/styles/style.css?v=<?php echo time(); ?>">
</head>
<body>
 <?php include 'header.php'; ?>
    <main>
    <h1>Articles</h1>
    
    <?php if (isset($error)): ?>
        <p style="color: red;">Error: <?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

<section class="articles">
<div class="articles-grid">
        <?php foreach ($articles as $article): ?>
            <a href="article.php?id=<?= htmlspecialchars($article['id']) ?>" class="article-link">
            <article class="article-card">
                <img src="<?= htmlspecialchars($article['image']) ?>" alt="Article Image" class="card-image">
                
                <div class="card-body">
                    <span class="card-tag"><?= htmlspecialchars($article['tag']) ?></span>
                    <h2 class="card-title"><?= htmlspecialchars($article['title']) ?></h2>
                    
                    <div class="card-meta">
                        By <strong><?= htmlspecialchars($article['author']) ?></strong> • 
                        <?= htmlspecialchars($article['date']) ?> • 
                        <?= htmlspecialchars($article['readingTime']) ?>
                    </div>
                    
                    <div class="card-excerpt">
                        <?= strip_tags($article['content']) ?> </div>
                </div>
            </article>
            </a>
        <?php endforeach; ?>
    </div>
</section>

    </main>
</body>
</html>