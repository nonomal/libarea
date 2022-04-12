<?php
echo includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]);
$form = new Forms();
$form->html_form($user['trust_level'], Config::get('form/catalog.site'));
?>

<div id="contentWrapper">
  <main>
    <?= Tpl::insert('/_block/navigation/breadcrumbs', [
        'list' => [
          [
            'name' => __('home'),
            'link' => getUrlByName('web')
          ], [
            'name' => __('site.add'),
            'link' => 'red'
          ],
        ]
      ]); ?>

    <form id="addUrl" class="max-w640">
      <?= csrf_field() ?>

      <?= includeTemplate('/view/default/_block/category', ['data' => ['topic' => false], 'action' => 'add']); ?>

      <?= $form->build_form(); ?>

      <?= $form->sumbit(__('add')); ?>
    </form>
  </main>
  <aside>
    <div class="box-white box-shadow-all text-sm">
      <h3 class="uppercase-box"><?= __('help'); ?></h3>
      <?= __('add.site.help'); ?>
      <div>
  </aside>
</div>

<?php $url = UserData::checkAdmin() ? 'web' : 'web.user.sites'; ?>
<?= includeTemplate('/view/default/_block/ajax', ['url' => 'web.create', 'redirect' => $url, 'id' => 'form#addUrl']); ?>

<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>