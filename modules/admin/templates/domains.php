<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <a class="right" title="<?= lang('Add'); ?>" href="/web/add">
                    <i class="light-icon-plus middle"></i>
                </a>
                <?= breadcrumb('/admin', lang('Admin'), null, null, $data['meta_title']); ?>

                <div class="domains">
                    <?php if (!empty($domains)) { ?>

                        <?php foreach ($domains as $key => $link) { ?>
                            <div class="domain-box">
                                <span class="add-favicon right size-13" data-id="<?= $link['link_id']; ?>">
                                    +фавикон
                                </span>
                                <div class="title">
                                    <?php if ($link['link_title']) { ?>
                                        <?= $link['link_title']; ?>
                                    <?php } else { ?>
                                        Add title...
                                    <?php } ?>
                                </div>
                                <div class="content-telo">
                                    <?php if ($link['link_content']) { ?>
                                        <?= $link['link_content']; ?>
                                    <?php } else { ?>
                                        Add content...
                                    <?php } ?>
                                </div>

                                <div class="content-footer">
                                    <a class="green" rel="nofollow noreferrer" href="<?= $link['link_url']; ?>">
                                        <?= favicon_img($link['link_id'], $link['link_url_domain']); ?>
                                        <span class="green"><?= $link['link_url']; ?></span>
                                    </a> |
                                    id<?= $link['link_id']; ?>
                                    <span class="indent"> &#183; </span>
                                    <?= $link['link_url_domain']; ?>

                                    <span class="indent"> &#183; </span>
                                    <?php if ($link['link_is_deleted'] == 0) { ?>
                                        active
                                    <?php } else { ?>
                                        <span class="red">Ban</span>
                                    <?php } ?>
                                    <span class="indent"> &#183; </span>
                                    <a href="/web/edit/<?= $link['link_id']; ?>"><?= lang('Edit'); ?></a>
                                    <span class="right heart-link red">
                                        +<?= $link['link_count']; ?>
                                    </span>
                                </div>
                            </div>
                        <?php } ?>

                    <?php } else { ?>
                        <div class="no-content"><?= lang('No'); ?>...</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>