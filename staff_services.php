<div class="services-section">
    <h2 class="section-title">Assigned Services</h2>
    <?php if (empty($assigned_services)): ?>
        <p>No services assigned.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Requested By</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assigned_services as $service): ?>
                    <tr>
                        <td><?= htmlspecialchars($service['service_name']) ?></td>
                        <td><?= htmlspecialchars($service['description']) ?></td>
                        <td><?= htmlspecialchars($service['status']) ?></td>
                        <td><?= htmlspecialchars($service['requested_by']) ?></td>
                        <td><?= htmlspecialchars($service['created_at']) ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="service_id" value="<?= htmlspecialchars($service['id']) ?>">
                                <select name="status">
                                    <option value="Pending" <?= $service['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="In Progress" <?= $service['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                    <option value="Completed" <?= $service['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                </select>
                                <input type="submit" name="update_service_status" value="Update Status" class="button">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
