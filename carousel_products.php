<div class="carousel-container">
    <div class="carousel">
        <?php foreach ($products as $index => $product): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <img src="<?= htmlspecialchars($product['product_image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <div class="item-info">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                    <p class="card-price">$<?= htmlspecialchars(number_format($product['price'], 2)) ?></p>
                    <a href="product_details.php?id=<?= htmlspecialchars($product['id']) ?>" class="button">Buy Now</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="carousel-controls">
        <button id="prev">&lt;</button>
        <button id="next">&gt;</button>
    </div>
</div>
