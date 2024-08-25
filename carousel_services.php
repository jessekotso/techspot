<div class="carousel-container">
    <div class="carousel">
        <?php foreach ($services as $index => $service): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <img src="<?= htmlspecialchars($service['image']) ?>" alt="<?= htmlspecialchars($service['name']) ?>">
                <div class="item-info">
                    <h3><?= htmlspecialchars($service['name']) ?></h3>
                    <p class="card-text"><?= htmlspecialchars($service['description']) ?></p>
                    <a href="service_request.php?id=<?= htmlspecialchars($service['id']) ?>" class="button">Request Service</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="carousel-controls">
        <button id="prev">&lt;</button>
        <button id="next">&gt;</button>
    </div>
</div>
