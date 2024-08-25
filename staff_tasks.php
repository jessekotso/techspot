<div class="tasks-section">
    <h2 class="section-title">Assigned Tasks</h2>
    <?php if (empty($tasks)): ?>
        <p>No tasks assigned.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Assigned By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?= htmlspecialchars($task['title']) ?></td>
                        <td><?= htmlspecialchars($task['description']) ?></td>
                        <td><?= htmlspecialchars($task['status']) ?></td>
                        <td><?= htmlspecialchars($task['created_at']) ?></td>
                        <td><?= htmlspecialchars($task['assigned_by']) ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="task_id" value="<?= htmlspecialchars($task['id']) ?>">
                                <select name="status">
                                    <option value="Pending" <?= $task['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="In Progress" <?= $task['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                    <option value="Completed" <?= $task['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                </select>
                                <input type="submit" name="update_task" value="Update Status" class="button">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
