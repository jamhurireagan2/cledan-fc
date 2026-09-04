<?php
$currentPage = 'news';
$pageTitle = 'News';
require_once 'includes/functions.php';
require_once 'includes/database.php';

$db = getDB();

// Pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$perPage = 9;
$offset = ($page - 1) * $perPage;

// Get total count
$countStmt = $db->query("SELECT COUNT(*) as total FROM news WHERE is_published = 1");
$totalNews = $countStmt->fetch()['total'];
$totalPages = ceil($totalNews / $perPage);

// Get news
$stmt = $db->prepare("SELECT * FROM news WHERE is_published = 1 ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->execute([$perPage, $offset]);
$news = $stmt->fetchAll();

// Get categories for filter
$categories = ['match-report', 'transfer', 'academy', 'club-announcement', 'community', 'general'];

require_once 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Club News</h1>
        <p>The latest updates from CLEDAN FC</p>
    </div>
</section>

<section class="py-40">
    <div class="container">
        <?php if (count($news) > 0): ?>
            <div class="news-grid">
                <?php foreach ($news as $article): ?>
                    <article class="news-card">
                        <?php if ($article['featured_image']): ?>
                            <img src="/uploads/news/<?php echo $article['featured_image']; ?>" alt="<?php echo $article['title']; ?>" class="news-image">
                        <?php else: ?>
                            <div class="news-image" style="background: linear-gradient(135deg, var(--primary-blue), var(--light-blue)); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        <?php endif; ?>
                        <div class="news-content">
                            <div class="news-meta">
                                <span><i class="far fa-calendar-alt"></i> <?php echo timeAgo($article['created_at']); ?></span>
                                <span class="news-category"><?php echo str_replace('-', ' ', ucfirst($article['category'])); ?></span>
                            </div>
                            <h3 class="news-title"><a href="/news/<?php echo $article['slug']; ?>"><?php echo $article['title']; ?></a></h3>
                            <p class="news-excerpt"><?php echo $article['excerpt'] ?: substr(strip_tags($article['content']), 0, 150) . '...'; ?></p>
                            <a href="/news/<?php echo $article['slug']; ?>" class="btn btn-outline btn-sm mt-20">Read More <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>"><i class="fas fa-chevron-left"></i></a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>"><i class="fas fa-chevron-right"></i></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-newspaper"></i>
                <h3>No News Articles</h3>
                <p>Check back soon for the latest updates from CLEDAN FC.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.empty-state i {
    font-size: 4rem;
    color: var(--light-blue);
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 1.5rem;
    margin-bottom: 10px;
}
</style>

<?php require_once 'includes/footer.php'; ?>