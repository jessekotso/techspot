<div class="carousel-container">
    <div class="carousel">
        <?php foreach ($courses as $index => $course): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <img src="<?= htmlspecialchars($course['course_image']) ?>" alt="<?= htmlspecialchars($course['name']) ?>">
                <div class="item-info">
                    <h3><?= htmlspecialchars($course['name']) ?></h3>
                    <p class="card-text"><?= htmlspecialchars($course['description']) ?></p>
                    <a href="enroll_course.php?id=<?= htmlspecialchars($course['id']) ?>" class="button">Enroll Now</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="carousel-controls">
        <button id="prev">&lt;</button>
        <button id="next">&gt;</button>
    </div>
</div>
