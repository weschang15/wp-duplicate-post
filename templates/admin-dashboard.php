<div class="wrap">
  <h1 class="wrap__title">Dashboard</h1>
  <ul class="tabs-list">
    <?php if (!empty($tabs)): ?>
    <?php foreach ($tabs as $target => $tab): ?>
    <?php if ($target === "pane-1"): ?>
    <li class="tabs-list__item js-is-active" data-target="<?= $target ?>"><?= $tab ?></li>
    <?php else: ?>
    <li class="tabs-list__item" data-target="<?= $target ?>"><?= $tab ?></li>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endif; ?>
  </ul>
  <div class="pane-list">
    <?php if (!empty($panes)): ?>
    <?php foreach ($panes as $pane): ?>
    <?= $pane ?>
    <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>