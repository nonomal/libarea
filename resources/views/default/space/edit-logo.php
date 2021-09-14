<div class="wrap">
  <main class="white-box pt5 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('Home'), '/s/' . $data['space']['space_slug'], $data['space']['space_name'], lang('Logo') . ' - ' . $data['space']['space_slug']); ?>
    <?= returnBlock('/space-nav', ['data' => $data, 'uid' => $uid]); ?>

    <div class="box create setting space">
      <form action="/space/logo/edit" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <?= spase_logo_img($data['space']['space_img'], 'max', $data['space']['space_name'], 'ava'); ?>
        <div class="box-form-img">
          <div class="boxline">
            <div class="input-images"></div>
          </div>
        </div>
        <div class="clear mb15">
          <div class="gray size-14 mb15">
            <?= lang('Recommended size'); ?>: 120x120px (jpg, jpeg, png)
          </div>
          <input type="hidden" name="space_id" id="space_id" value="<?= $data['space']['space_id']; ?>">
          <input type="submit" class="button" name="submit" value="<?= lang('Edit'); ?>" />
        </div>
        <?php if ($data['space']['space_cover_art'] != 'space_cover_no.jpeg') { ?>
          <img class="cover" src="/uploads/spaces/cover/<?= $data['space']['space_cover_art']; ?>">
          <a class="right" href="/space/<?= $data['space']['space_slug']; ?>/delete/cover">
            <?= lang('Remove'); ?>
          </a>
        <?php } else { ?>
          <div class="gray size-14">
            <?= lang('no-cover'); ?>...
          </div>
        <?php } ?>
        <div class="box setting avatar">
          <div class="box-form-img">
            <div class="boxline">
              <div class="input-images-cover"></div>
            </div>
          </div>
          <div class="boxline gray size-14">
            <?= lang('Recommended size'); ?>: 1920x300px (jpg, jpeg, png)
          </div>
        </div>
        <div class="boxline">
          <input type="hidden" name="space_id" id="space_id" value="<?= $data['space']['space_id']; ?>">
          <input type="submit" class="button" name="submit" value="<?= lang('Edit'); ?>" />
        </div>
      </form>
    </div>
  </main>
  <aside>
    <div class="white-box p15">
      <?= lang('info-space-logo'); ?>
    </div>
  </aside>
</div>