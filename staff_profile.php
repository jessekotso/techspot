<div class="profile-section">
    <h2 class="section-title">Profile Information</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($profile['email']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($profile['phone']) ?></p>
    <a href="edit_profile.php" class="button">Edit Profile</a>
</div>
