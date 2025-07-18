<tbody>
    <?php if (!empty($submissions)): ?>
        <?php foreach ($submissions as $row): ?>
            <!-- Display each field using <?= htmlspecialchars(...) ?> as shown above -->
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="13" class="text-center">No submissions found.</td></tr>
    <?php endif; ?>
</tbody>
