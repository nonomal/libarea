<?php include TEMPLATE_DIR . '/header.php'; ?>

<?php if ($space_info['space_is_delete'] == 0) { ?>

    <?php if ($space_info['space_cover_art'] != 'space_cover_no.jpeg') { ?>
        <div class="space-cover-box" style="background-image: url(/uploads/spaces/cover/<?= $space_info['space_cover_art']; ?>); background-position: 50% 50%;min-height: 300px;">
            <div class="wrap">
            <?php } else { ?>
                <div class="space-box" style="background:<?= $space_info['space_color']; ?>;">
                    <div class="wrap">
                    <?php } ?>

                    <?php if (!$uid['id']) { ?>
                        <div class="right">
                            <a href="/login">
                                <div class="hide-space-id yes-space">+ <?= lang('Read'); ?></div>
                            </a>
                        </div>
                    <?php } else { ?>
                        <?php if ($space_info['space_id'] != 1) { ?>
                            <?php if ($space_info['space_user_id'] != $uid['id']) { ?>
                                <div class="right">
                                    <?php if ($space_signed == 1) { ?>
                                        <div data-id="<?= $space_info['space_id']; ?>" class="hide-space-id no-space">
                                            <i class="light-icon-check middle"></i>
                                            <span class="middle"><?= lang('Unsubscribe'); ?></span>
                                        </div>
                                    <?php } else { ?>
                                        <div data-id="<?= $space_info['space_id']; ?>" class="hide-space-id yes-space">
                                            <i class="light-icon-plus middle"></i>
                                            <span class="middle"><?= lang('Read'); ?></span>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                    <div class="space-text">

                        <?= spase_logo_img($space_info['space_img'], 'max', $space_info['space_name'], 'space-box-img'); ?>

                        <a title="<?= $space_info['space_name']; ?>" href="/s/<?= $space_info['space_slug']; ?>">
                            <h1 class="size-21"><?= $space_info['space_name']; ?></h1>
                        </a>
                        <div class="space-slug">
                            s/<?= $space_info['space_slug']; ?>
                        </div>

                    </div>
                    </div>
                </div>

                <div class="wrap">
                    <main>
                        <ul class="nav-tabs">
                            <?php if ($data['sheet'] == 'feed') { ?>
                                <li class="active">
                                    <span><?= lang('Feed'); ?></span>
                                </li>
                                <li>
                                    <a href="/s/<?= $space_info['space_slug']; ?>/top">
                                        <span>Top</span>
                                    </a>
                                </li>
                            <?php } else { ?>
                                <li>
                                    <a href="/s/<?= $space_info['space_slug']; ?>">
                                        <span><?= lang('Feed'); ?></span>
                                    </a>
                                </li>
                                <li class="active">
                                    <span>Top</span>
                                </li>
                            <?php } ?>
                            <?php if ($uid['trust_level'] == 5 || $space_info['space_user_id'] == $uid['id']) { ?>
                                <li class="right">
                                    <a class="edit-space" href="/space/edit/<?= $space_info['space_id']; ?>">
                                        <span><?= lang('Edit'); ?></span>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>

                        <?php include TEMPLATE_DIR . '/_block/post.php'; ?>

                        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/s/' . $space_info['space_slug']); ?>

                    </main>

                    <aside>
                        <div class="info-space white-box">
                            <div class="inner-padding">
                                <div class="sb-space-short">
                                    <?= $space_info['space_short_text']; ?>
                                </div>

                                <div class="sb-space-stat">
                                    <div class="_bl">
                                        <p class="bl-n"><a href="/u/<?= $space_info['login']; ?>"><?= $space_info['login']; ?></a></p>
                                        <p class="bl-t"><?= lang('Created by'); ?></p>
                                    </div>
                                    <div class="_bl">
                                        <?php if ($space_info['space_id'] != 1) { ?>
                                            <p class="bl-n"><?= $space_info['users']; ?></p>
                                        <?php } else { ?>
                                            <p class="bl-n">***</p>
                                        <?php } ?>
                                        <p class="bl-t"><?= lang('Reads'); ?></p>
                                    </div>
                                </div>

                                <hr>

                                <div class="sb-created">
                                    <i class="light-icon-calendar middle"></i>
                                    <span class="middle"><?= $space_info['space_date']; ?></span>
                                </div>

                                <?php if (!$uid['id']) { ?>
                                    <div class="sb-add-space-post center">
                                        <a class="add-space-post" href="/login">
                                            <i class="light-icon-edit middle"></i>
                                            <?= lang('Create Post'); ?>
                                        </a>
                                    </div>
                                <?php } else { ?>
                                    <div class="sb-add-space-post center">
                                        <?php if ($space_info['space_user_id'] == $uid['id']) { ?>
                                            <a class="add-space-post" href="/post/add/space/<?= $space_info['space_id']; ?>">
                                                <?= lang('Create Post'); ?>
                                            </a>
                                        <?php } else { ?>
                                            <?php if ($space_signed == 1) { ?>
                                                <?php if ($space_info['space_permit_users'] == 1) { ?>
                                                    <?php if ($uid['trust_level'] == 5 || $space_info['space_user_id'] == $uid['id']) { ?>
                                                        <a class="add-space-post" href="/post/add/space/<?= $space_info['space_id']; ?>">
                                                            <?= lang('Create Post'); ?>
                                                        </a>
                                                    <?php } else { ?>
                                                        <span class="restricted"><?= lang('The owner restricted the publication'); ?></span>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <a class="add-space-post" href="/post/add/space/<?= $space_info['space_id']; ?>">
                                                        <?= lang('Create Post'); ?>
                                                    </a>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="space-text-sb white-box">
                            <div class="inner-padding">
                                <?= $space_info['space_text']; ?>
                            </div>
                        </div>

                    </aside>
                </div>
            <?php } else { ?>
                <main class="w-100">
                    <p class="no-content gray">
                        <i class="light-icon-info-square middle"></i>
                        <span class="middle"><?= lang('ban-space'); ?>...</span>
                    </p>
                </main>
            <?php } ?>
            <?php include TEMPLATE_DIR . '/footer.php'; ?>