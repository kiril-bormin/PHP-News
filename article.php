<?php
require_once 'db.php';

try {
    $articleId = $_GET['id'] ?? null;

    if (!$articleId) {
        header('Location: index.php');
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$articleId]);
    $article = $stmt->fetch();

    if (!$article) {
        http_response_code(404);
        $notFound = true;
    }
} catch (Exception $e) {
    http_response_code(500);
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= isset($article) ? htmlspecialchars($article['title']) : 'Article Not Found' ?> - PHP News</title>
    <link rel="stylesheet" href="/styles/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <?php if (isset($notFound)): ?>
            <div class="article-container">
                <h1>Article Not Found</h1>
                <p>The article you're looking for doesn't exist.</p>
                <a href="index.php" class="btn-back">Back to Articles</a>
            </div>
        <?php elseif (isset($error)): ?>
            <div class="article-container">
                <h1>Error</h1>
                <p style="color: red;">Error: <?= htmlspecialchars($error) ?></p>
                <a href="index.php" class="btn-back">Back to Articles</a>
            </div>
        <?php elseif (isset($article)): ?>
            <article class="article-full">
                <div class="article-container">
                    <div class="article-header">
                        <a href="index.php" class="btn-back">&larr; Back</a>
                        <span class="article-tag"><?= htmlspecialchars($article['tag']) ?></span>
                    </div>

                    <h1 class="article-title"><?= htmlspecialchars($article['title']) ?></h1>

                    <div class="article-meta">
                        <div class="meta-left">
                            By <strong><?= htmlspecialchars($article['author']) ?></strong> •
                            <time><?= htmlspecialchars($article['date']) ?></time>
                        </div>
                        <div class="meta-right">
                            <?= htmlspecialchars($article['readingTime']) ?>
                        </div>
                    </div>

                    <img src="<?= htmlspecialchars($article['image']) ?>" alt="Article Image" class="article-image">

                    <div class="article-content">
                        <?= $article['content'] ?>
                    </div>

                    <div class="article-footer">
                        <a href="index.php?category=<?= urlencode($article['category']) ?>" class="category-link">
                            More from <?= htmlspecialchars($article['category']) ?>
                        </a>
                    </div>
                </div>
            </article>
        <?php endif; ?>
    </main>
</body>
</html>
