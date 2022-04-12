<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id'    => 'audits.all',
        'url'   => getUrlByName('admin.audits'),
        'name'  => __('all'),
        'icon'  => 'bi-record-circle',
      ], [
        'id'    => 'audits.ban',
        'url'   => getUrlByName('admin.audits.ban'),
        'name'  => __('approved'),
        'icon'  => 'bi-x-circle',
      ], [
        'id'    => 'reports.all',
        'url'   => getUrlByName('admin.reports'),
        'name'  => __('reports'),
        'icon'  => 'bi-record-circle',
      ] 
    ]
  ]
); ?>

<div class="box-white">
  <?php if (!empty($data['audits'])) : ?>
    <table>
      <thead>
        <th class="w50">id</th>
        <th><?= __('info'); ?></th>
        <th><?= __('type'); ?></th>
        <th><?= __('action'); ?></th>
        <th><?= __('action'); ?></th>
      </thead>
      <?php foreach ($data['audits'] as $key => $audit) : ?>
        <tr>
          <td>
            <?= $audit['audit_id']; ?>
          </td>
          <td class="text-sm">
            <div class="content-telo">
              <?php $content = $audit['content'][$audit['action_type'] . '_content']; ?>
              <?= Html::fragment(Content::text($content, 'line'), 200); ?>
            </div>

            (id:<?= $audit['id']; ?>)
            <a href="<?= getUrlByName('profile', ['login' => $audit['login']]); ?>">
              <?= $audit['login']; ?>
            </a>
            <?php if ($audit['limiting_mode'] == 1) : ?>
              <span class="mr5 ml5 red"> audit </span>
            <?php endif; ?>
            <span class="mr5 ml5"> &#183; </span>
            <a class="mr5 ml5" href="<?= getUrlByName('admin.user.edit', ['id' => $audit['id']]); ?>">
              <i class="bi-pencil"></i>
            </a>
            <span class="mr5 ml5"> &#183; </span>
             <?= $audit['content'][$audit['action_type'] . '_date']; ?>
            <span class="mr5 ml5"> &#183; </span>

            <?= __('type'); ?>: <i><?= $audit['action_type']; ?></i>
            <?php if ($audit['content'][$audit['action_type'] . '_is_deleted'] == 1) : ?>
              <span class="red"><?= __('deleted'); ?> </span>
            <?php endif; ?>

            <?php if (!empty($audit['post'])) : ?>
              <?php if ($audit['post']['post_slug']) : ?>
                <a class="block" href="<?= getUrlByName('post', ['id' => $audit['post']['post_id'], 'slug' => $audit['post']['post_slug']]); ?>">
                  <?= $audit['post']['post_title']; ?>
                </a>
              <?php endif; ?>
            <?php endif; ?>
          </td>
          <th><?= __($audit['type_belonging']); ?></th>
          <td class="center">
            <a data-id="<?= $audit['content'][$audit['action_type'] . '_id']; ?>" data-type="<?= $audit['action_type']; ?>" class="type-action text-sm">
              <?php if ($audit['content'][$audit['action_type'] . '_is_deleted'] == 1) : ?>
                <span class="red">
                  <?= __('recover'); ?>
                </span>
              <?php else : ?>
                <?= __('remove'); ?>
              <?php endif; ?>
            </a>
            <div class="lowercase text-xs">
              <?= __('content'); ?>
            </div>
          </td>
          <td class="center">
            <?php if ($audit['type_belonging'] == 'audit') : ?>
                <?php if ($audit['read_flag'] == 1) : ?>
                  id:
                  <a href="<?= getUrlByName('admin.user.edit', ['id' => $audit['audit_id']]); ?>">
                    <?= $audit['user_id']; ?>
                  </a>
                <?php else : ?>
                  <a data-status="<?= $audit['action_type']; ?>" data-id="<?= $audit['content'][$audit['action_type'] . '_id']; ?>" class="audit-status text-sm">
                    <?= __('to.approve'); ?>
                  </a>
                  <div class="text-xs"><?= __('off.mute.mode'); ?></div>
                <?php endif; ?>
            <?php else : ?>
              <div class="<?php if ($audit['read_flag'] == 0) : ?> bg-red-200<?php endif; ?>">
                <span class="report-saw" data-id="<?= $audit['audit_id']; ?>">
                  <i class="bi-record-circle gray text-2xl"></i>
                </span>
              </div>  
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else : ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no'), 'icon' => 'bi-info-lg']); ?>
  <?php endif; ?>
</div>
<?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('admin.audits')); ?>
</main>
<?= includeTemplate('/view/default/footer'); ?>