<?php if ($alerts): ?>
    <div class="alert alert-success" role="alert">
        <?= implode('<br/>', $alerts) ?>
    </div>
<?php endif ?>