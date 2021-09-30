<div class="cm_addentry">
  <?php if ($uid['user_id'] > 0) { ?>
    <form id="add_comm" class="new_comment" action="/comment/create" accept-charset="UTF-8" method="post">
      <?= csrf_field() ?>
      <textarea rows="5" minlength="6" placeholder="<?= lang('write-something'); ?>..." name="comment" id="comment"></textarea>
      <div class="boxline">
        <input type="hidden" name="post_id" id="post_id" value="<?= $data['post_id']; ?>">
        <input type="hidden" name="answer_id" id="answer_id" value="<?= $data['answer_id']; ?>">
        <input type="hidden" name="comment_id" id="comment_id" value="<?= $data['comment_id']; ?>">
        <input type="submit" class="button br-rd-5 white mt5" name="commit" value="<?= lang('comment'); ?>">
        <input id="cancel_comment" class="cancel gray" type="button" value="<?= lang('cancel'); ?>">
      </div>
    </form>
  <?php } else { ?>
    <textarea rows="5" disabled="disabled" class="bg-gray-000" placeholder="<?= lang('no-auth-comm'); ?>." name="content" id="content"></textarea>
    <div>
      <input type="hidden" name="post_id" id="post_id" value="<?= $data['post_id']; ?>">
      <input type="hidden" name="answer_id" id="answer_id" value="<?= $data['answer_id']; ?>">
      <input type="submit" class="button br-rd-5 white mt5" name="commit" value="<?= lang('comment'); ?>">
      <input id="cancel_comment" class="cancel gray" type="button" value="<?= lang('cancel'); ?>">
    </div>
  <?php } ?>
</div>