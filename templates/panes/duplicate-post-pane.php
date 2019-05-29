<div class="pane-list__item js-is-active" id="pane-1">
  <p><strong>Overview:</strong> Configure which Post Types support duplication.</p>
  <section class="pane-content">
    <div class="pane-item grid grid--wrap grid--align-start">
      <form class="form admin-form" action="" method="POST" id="form-duplicate-post-settings">
        <div class="form-group">
          <p><strong>General Settings</strong><br /></p>
          <div class="form-toggle">
            <span class="form-toggle__text">Activate</span>
            <input class="form-toggle__input" type="checkbox" name="status" id="status" value="1"
              <?= isset($settings) && $settings['status'] ? "checked" : null ?>>
            <label class="form-toggle__label" for="status"></label>
          </div>
        </div>
        <div class="form-group">
          <p><strong>Available Post Types</strong><br /><small>Activate the Duplicate Post feature for only the
              specified post types.</small></p>
          <?php if (!empty($post_types)): ?>
          <?php foreach ($post_types as $post_type): ?>
          <div class="form-pill">
            <input class="form-pill__input" type="checkbox" name="post_types[]" id="<?= $post_type ?>"
              value="<?= $post_type ?>" <?= isset($settings) &&
in_array($post_type, $settings['post_types'])
  ? "checked"
  : null ?>>
            <label class="form-pill__label" for="<?= $post_type ?>"><?= $post_type ?></label>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <div class="form-group">
          <?= wp_nonce_field('wp_rest', '_wpnonce', true, false) ?>
          <button class="admin-form__button" type="submit">
            <span>Update Settings</span>
          </button>
        </div>
      </form>
    </div>
  </section>
</div>
