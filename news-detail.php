<?php
require_once 'includes/functions.php';
require_once 'includes/database.php';

$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';

if (!$slug) {
    header('Location: /news');
    exit();
}

$db = getDB();

// Get article
$stmt = $db->prepare("SELECT * FROM news WHERE slug = ? AND is_published = 1");
$stmt->execute([$slug]);
$article = $stmt->fetch();

if (!$article) {
    header('Location: /news');
    exit();
}

// Update view count
$stmt = $db->prepare("UPDATE news SET view_count = view_count + 1 WHERE id = ?");
$stmt->execute([$article['id']]);

// Get related news
$stmt = $db->prepare("SELECT * FROM news WHERE category = ? AND id != ? AND is_published = 1 ORDER BY created_at DESC LIMIT 3");
$stmt->execute([$article['category'], $article['id']]);
$relatedNews = $stmt->fetchAll();

$pageTitle = $article['title'];
$currentPage = 'news';
require_once 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1><?php echo $article['title']; ?></h1>
        <p><?php echo formatDate($article['created_at']); ?></p>
    </div>
</section>

<section class="py-40">
    <div class="container">
        <div class="news-detail">
            <?php if ($article['featured_image']): ?>
                <img src="/uploads/news/<?php echo $article['featured_image']; ?>" alt="<?php echo $article['title']; ?>" class="news-detail-image">
            <?php endif; ?>
            
            <div class="news-detail-meta">
                <span><i class="far fa-calendar-alt"></i> <?php echo formatDate($article['created_at']); ?></span>
                <span><i class="far fa-folder"></i> <?php echo str_replace('-', ' ', ucfirst($article['category'])); ?></span>
                <span><i class="far fa-eye"></i> <?php echo $article['view_count']; ?> views</span>
            </div>
            
            <div class="news-detail-content">
                <?php echo nl2br($article['content']); ?>
            </div>
        </div>
        
        <?php if (count($relatedNews) > 0): ?>
            <div class="related-news">
                <h3>Related News</h3>
                <div class="news-grid">
                    <?php foreach ($relatedNews as $related): ?>
                        <article class="news-card">
                            <?php if ($related['featured_image']): ?>
                                <img src="/uploads/news/<?php echo $related['featured_image']; ?>" alt="<?php echo $related['title']; ?>" class="news-image">
                            <?php else: ?>
                                <div class="news-image" style="background: linear-gradient(135deg, var(--primary-blue), var(--light-blue)); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                            <?php endif; ?>
                            <div class="news-content">
                                <h3 class="news-title"><a href="/news/<?php echo $related['slug']; ?>"><?php echo $related['title']; ?></a></h3>
                                <p class="news-excerpt"><?php echo $related['excerpt'] ?: substr(strip_tags($related['content']), 0, 100) . '...'; ?></p>
                                <a href="/news/<?php echo $related['slug']; ?>" class="btn btn-outline btn-sm mt-20">Read More</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.news-detail {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 40px;
    box-shadow: var(--shadow);
}

.news-detail-image {
    width: 100%;
    max-height: 400px;
    object-fit: cover;
    border-radius: var(--border-radius);
    margin-bottom: 25px;
}

.news-detail-meta {
    display: flex;
    gap: 20px;
    font-size: 0.9rem;
    color: var(--dark-gray);
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--light-gray);
    flex-wrap: wrap;
}

.news-detail-meta i {
    margin-right: 5px;
}

.news-detail-content {
    font-size: 1.05rem;
    line-height: 1.9;
    color: var(--text-dark);
}

.news-detail-content p {
    margin-bottom: 20px;
}

.related-news {
    margin-top: 50px;
}

.related-news h3 {
    font-size: 1.5rem;
    margin-bottom: 25px;
    padding-bottom: 10px;
    border-bottom: 3px solid var(--gold);
}

@media (max-width: 768px) {
    .news-detail {
        padding: 20px;
    }
    
    .news-detail-image {
        max-height: 250px;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>