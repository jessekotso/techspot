<div class="carousel-container general-carousel">
    <div class="carousel">
        <?php foreach ($featuredItems as $index => $item): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <img src="<?= htmlspecialchars($item['product_image'] ?? $item['image'] ?? $item['course_image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                <div class="item-info">
                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                    <p class="card-text"><?= htmlspecialchars($item['description']) ?></p>
                    <?php if (isset($item['product_image'])): ?>
                        <a href="product_details.php?id=<?= htmlspecialchars($item['id']) ?>" class="button">Buy Now</a>
                    <?php elseif (isset($item['image'])): ?>
                        <a href="service_request.php?id=<?= htmlspecialchars($item['id']) ?>" class="button">Request Service</a>
                    <?php elseif (isset($item['course_image'])): ?>
                        <a href="enroll_course.php?id=<?= htmlspecialchars($item['id']) ?>" class="button">Enroll Now</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="carousel-controls">
        <button class="prev">&lt;</button>
        <button class="next">&gt;</button>
    </div>
</div>
