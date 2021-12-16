<?= includeTemplate(
  '/content/admin/menu',
  [
    'type'     => $data['type'],
    'sheet'    => $data['sheet'],
    'user_id'  => $uid['user_id'],
    'add'     => false,
    'pages'   => true
  ]
); ?>

<div class="bg-white br-box-gray p15">
  <?php if (!empty($data['answers'])) { ?>
    <?php foreach ($data['answers'] as $answer) { ?>
      <a href="<?= getUrlByName('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>">
        <b><?= $answer['post_title']; ?></b>
      </a>
      <div id="answer_<?= $answer['answer_id']; ?>">
        <div class="size-13 gray">
          <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'w18 mr5'); ?>
          <a class="date mr5" href="<?= getUrlByName('user', ['login' => $answer['user_login']]); ?>">
            <?= $answer['user_login']; ?>
          </a>
          <span class="mr5">
            <?= $answer['date']; ?>
          </span>
          <a class="gray-light ml10" href="<?= getUrlByName('admin.logip', ['ip' => $answer['answer_ip']]); ?>">
            <?= $answer['answer_ip']; ?>
          </a>
          <?php if ($answer['post_type'] == 1) { ?>
            <i class="bi bi-question-lg green"></i>
          <?php } ?>
        </div>
        <div class="size-15 max-w780">
          <?= $answer['content']; ?>
        </div>
        <div class="br-bottom mb15 pb5 size-13 hidden gray">
          + <?= $answer['answer_votes']; ?>
          <span id="cm_dell" class="right comment_link size-13">
            <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action">
              <?php if ($data['sheet'] == 'answers.ban') { ?>
                <?= Translate::get('recover'); ?>
              <?php } else { ?>
                <?= Translate::get('remove'); ?>
              <?php } ?>
            </a>
          </span>
        </div>
      </div>
    <?php } ?>
  <?php } else { ?>
    <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
  <?php } ?>
</div>
<?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('admin.answers')); ?>
</main>