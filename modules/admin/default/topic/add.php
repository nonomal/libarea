<div class="wrap">
  <main class="admin">
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/admin', lang('Admin'), '/admin/topics', lang('Topics'), lang('Add topic')); ?>

      <div class="telo space">
        <div class="box create">
          <form action="/admin/topic/add" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="boxline">
              <label class="form-label" for="post_content">
                <?= lang('Title'); ?><sup class="red">*</sup>
              </label>
              <input class="form-input" minlength="3" type="text" name="topic_title" value="">
              <div class="box_h">3 - 64 <?= lang('characters'); ?></div>
            </div>
            <div class="boxline">
              <label class="form-label" for="post_content">
                <?= lang('Title'); ?> (SEO)<sup class="red">*</sup>
              </label>
              <input class="form-input" minlength="4" type="text" name="topic_seo_title" value="">
              <div class="box_h">4 - 225 <?= lang('characters'); ?></div>
            </div>
            <div class="boxline">
              <label class="form-label" for="post_content">
                <?= lang('Slug'); ?><sup class="red">*</sup>
              </label>
              <input class="form-input" minlength="3" type="text" name="topic_slug" value="">
              <div class="box_h">3 - 32 <?= lang('characters'); ?> (a-zA-Z0-9)</div>
            </div>
            <div class="boxline">
              <label for="post_content">
                <?= lang('Meta Description'); ?><sup class="red">*</sup>
              </label>
              <textarea rows="6" class="add" minlength="44" name="topic_description"></textarea>
              <div class="box_h">> 44 <?= lang('characters'); ?></div>
            </div>
            <input type="submit" name="submit" class="button" value="<?= lang('Add'); ?>" />
          </form>
        </div>
      </div>
    </div>
  </main>
</div>