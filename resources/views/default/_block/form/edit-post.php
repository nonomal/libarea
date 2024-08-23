<fieldset>
  <label for="post_title"><?= __('app.heading'); ?></label>
  <input minlength="6" maxlength="250" id="title" value="<?= htmlEncode($post['post_title']); ?>" type="text" required name="post_title">
  <div class="help">6 - 250 <?= __('app.characters'); ?></div>
</fieldset>

<?php if ($container->user()->admin()) : ?>
  <fieldset>
    <label for="post_slug">SLUG (URL)</label>
    <input minlength="6" maxlength="250" value="<?= $post['post_slug']; ?>" type="text" required name="post_slug">
    <div class="help">> 6 <?= __('app.characters'); ?></div>
  </fieldset>
<?php endif; ?>

<?= insert('/_block/form/select/topic', ['data' => $data, 'action' => 'edit']); ?>

<?php if (!empty($data['blog'])) : ?>
  <?= insert('/_block/form/select/blog', [
    'data'        => $data,
    'action'      => 'edit',
    'title'       => __('app.blogs'),
  ]); ?>
<?php endif; ?>

<?php if ($container->user()->admin()) : ?>
  <?= insert('/_block/form/select/section', ['data' => $data]); ?>
<?php endif; ?>

<?php if ($post['post_url']) : ?>
  <div class="mb20 2flex">
    <div class="mb5" for="post_title">URL:
      <a target="_blank" rel="noreferrer ugc" href="<?= $post['post_url']; ?>" class="text-sm">
        <?= $post['post_url']; ?>
      </a>
    </div>
    <?php if ($post['post_thumb_img']) : ?>
      <?= Img::image($post['post_thumb_img'], $post['post_title'], 'w94', 'post', 'thumbnails'); ?>
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php if ($post['post_type'] == 'post') : ?>
  <div class="file-upload mb20" id="file-drag">
    <div class="flex gap">
      <?php if ($post['post_content_img']) : ?>
        <div>
          <?= Img::image($post['post_content_img'], $post['post_title'], 'w160', 'post', 'cover'); ?>
          <input type="hidden" name="images" value="<?= $post['post_content_img']; ?>">
          <a class="img-remove text-sm" href="<?= url('delete.post.cover', ['id' => $post['post_id']]); ?>">
            <?= __('app.remove'); ?>
          </a>
        </div>
      <?php endif; ?>

      <img id="file-image" src="/assets/images/1px.jpg" alt="" class="w94 h94 br-gray">
    </div>
    <div id="start" class="mt10">
      <input id="file-upload" type="file" name="images" accept="image/*" />
      <div id="notimage" class="none"><?= __('app.select_image'); ?></div>
    </div>
    <div id="response" class="hidden">
      <div id="messages"></div>
    </div>
  </div>
<?php endif; ?>

<?= insert('/_block/form/editor/toolbar-img', ['height'  => '300px', 'content' => $post['post_content'], 'title' => __('app.post'), 'type' => 'post-telo', 'id' => $post['post_id']]); ?>

<?php if ($container->access()->limitTl(2)) : ?>
  <?php if ($post['post_draft'] == 1) : ?>
    <fieldset>
      <input type="checkbox" name="post_draft" <?php if ($post['post_draft'] == 1) : ?>checked <?php endif; ?>> <?= __('app.draft_post'); ?>
    </fieldset>
  <?php endif; ?>
<?php endif; ?>

<?php if ($post['post_type'] == 'post') : ?>
  <?= insert('/_block/form/content-tl', ['data' => $post['post_tl']]); ?>

  <?php if (config('general', 'qa_site_format') == true) : ?>
    <?php if ($container->user()->admin()) : ?>
      <fieldset>
        <input type="checkbox" name="post_feature" <?php if ($post['post_feature'] == 1) : ?>checked <?php endif; ?>> <?= __('app.format_Q&A'); ?>
      </fieldset>
    <?php endif; ?>
  <?php else : ?>
    <fieldset>
      <input type="checkbox" name="post_feature" <?php if ($post['post_feature'] == 1) : ?>checked <?php endif; ?>> <?= __('app.format_Q&A'); ?>
    </fieldset>
  <?php endif; ?>

  <fieldset>
    <input type="checkbox" name="closed" <?php if ($post['post_closed'] == 1) : ?>checked <?php endif; ?>> <?= __('app.post_closed'); ?>
  </fieldset>

  <fieldset>
    <input type="checkbox" name="translation" <?php if ($post['post_translation'] == 1) : ?>checked <?php endif; ?>> <?= __('app.post_translation'); ?>
  </fieldset>

  <?php if ($container->user()->admin()) : ?>
    <fieldset>
      <input type="checkbox" name="top" <?php if ($post['post_top'] == 1) : ?>checked <?php endif; ?>> <?= __('app.pin'); ?>
    </fieldset>
  <?php endif; ?>
<?php endif; ?>

<?php if (config('feed', 'nsfw')) : ?>
  <fieldset>
      <input type="checkbox" name="nsfw" <?php if ($post['post_nsfw'] == 1) : ?>checked <?php endif; ?>> <?= __('app.nsfw_post'); ?>
  </fieldset>
<?php endif; ?>

<fieldset>
  <input type="checkbox" name="hidden" <?php if ($post['post_hidden'] == 1) : ?>checked <?php endif; ?>> <?= __('app.hidden_post'); ?>
  <div class="help"><?= __('app.hidden_post_help'); ?></div>
</fieldset>

<?php if ($container->user()->admin()) : ?>
  <?= insert('/_block/form/select/user', ['user' => $data['user']]); ?>
<?php endif; ?>

<?php if ($post['post_type'] == 'post') : ?>
  <?= insert('/_block/form/select/related-posts', ['data' => $data]); ?>
<?php endif; ?>

<?php if ($container->user()->admin()) : ?>
  <fieldset>
    <label for="post_title"><?= __('app.id_merged_post'); ?></label>
    <input value="<?= $post['post_merged_id']; ?>" type="text" name="post_merged_id">
    <div class="help"><?= __('app.post_merged_info'); ?></div>
  </fieldset>
<?php endif; ?>

<?php if ($container->access()->limitTl(config('trust-levels', 'tl_add_poll'))) : ?>
  <?= insert('/_block/form/select/poll', ['poll' => $data['poll']]); ?>
<?php endif; ?>  

<p>
  <?php if ($post['post_draft'] == 1) : ?>
    <input type="hidden" name="draft" value="1">
  <?php endif; ?>
  <input type="hidden" name="post_id" value="<?= $post['post_id']; ?>">
  <?= Html::sumbit(__('app.edit')); ?>
</p>