<?php include TEMPLATE_DIR . '/header.php'; ?>
<?php include TEMPLATE_DIR . '/menu.php'; ?>
<main>
    <div class="left-ots">
        <?php if (!empty($posts)) { ?>
        
            <?php foreach ($posts as  $post) { ?> 
                <div class="post-telo">
                    <?php if (!$uid['id']) { ?> 
                        <div id="vot<?= $post['post_id']; ?>" class="voters">
                            <a rel="nofollow" href="/login"><div class="post-up-id"></div></a>
                            <div class="score"><?= $post['post_votes']; ?></div>
                        </div>
                    <?php } else { ?> 
                        <?php if ($post['votes_post_user_id'] || $uid['id'] == $post['post_user_id']) { ?>
                            <div class="voters active">
                                <div class="post-up-id"></div>
                                <div class="score"><?= $post['post_votes']; ?></div>
                            </div>
                        <?php } else { ?>
                            <div id="up<?= $post['post_id']; ?>" class="voters">
                                <div data-id="<?= $post['post_id']; ?>" class="post-up-id"></div>
                                <div class="score"><?= $post['post_votes']; ?></div>
                            </div>
                        <?php } ?> 
                    <?php } ?> 
                    <div class="post-body">
                        <a class="u-url" href="/posts/<?= $post['post_slug']; ?>">
                            <h3 class="titl"><?= $post['post_title']; ?></h3>
                        </a>
                    <?php if ($post['post_content_preview']) { ?>
                        <div class="show">
                            <span class="show_add_<?= $post['post_id']; ?>">
                                <a data-post_id="<?= $post['post_id']; ?>" class="showpost">
                                    <span>&#9658;</span> 
                                    <?= $post['post_content_preview']; ?>... 
                                </a>
                            </span>
                        </div>
                    <?php } ?>
                    <div id="show_<?= $post['post_id']; ?>" class="show_detail"></div> 
                        <div class="footer">
                            <img class="ava" src="/uploads/avatar/small/<?= $post['avatar'] ?>">
                            <span class="user"> 
                                <a href="/u/<?= $post['login']; ?>"><?= $post['login']; ?></a> 
                            </span>
                            <span class="date">
                                <?= $post['post_date']; ?>
                            </span>
                            <?php if($post['post_comments'] !=0) { ?> 
                                <span class="otst"> | </span>
                                <a class="u-url" href="/posts/<?= $post['post_slug']; ?>">
                                    <?= $post['post_comments']; ?>  <?= $post['num_comments']; ?>
                                </a>                                
                            <?php } ?>
                        </div>  
                    </div>
                </div>    
            <?php } ?>

        <?php } else { ?>

            <div class="no-content"><?= lang('no-post'); ?>...</div>

        <?php } ?>
    </div>
</main>

<aside id="sidebar">
    <?php if(!$uid['id']) { ?> 
            <div class="right"> 
                <a href="/login"><div class="hide-space-id add-space">Подписаться</div></a>
            </div>
        <?php } else { ?>
            <div class="right"> 
                <?php if($data['space_hide'] == 1) { ?> 
                    <div data-id="<?= $space_info['space_id']; ?>" class="hide-space-id add-space">Подписаться</div>
                <?php } else { ?> 
                    <div data-id="<?= $space_info['space_id']; ?>" class="hide-space-id no-space">Отписаться</div>
                <?php } ?>   
            </div>  
    <?php } ?> 
    <br> <br> 
    <div class="space-text">
        <img class="space-img" src="/uploads/space/<?= $space_info['space_img']; ?>">
        <h2><?= $space_info['space_name']; ?></h2>  
        <div class="data"><?= $space_info['space_date']; ?></div> 
       
        <div class="space-text-bar">
            <?= $space_info['space_text']; ?>
        </div>
        <?php if($uid['trust_level'] == 5) { ?>
            <div class="edit-space">
                <a href="/space/<?= $space_info['space_slug']; ?>/edit">
                    <?= lang('Edit'); ?>
                </a>
                 <span class="otst"> | </span>
                <a href="/space/<?= $space_info['space_slug']; ?>/edit">
                    <?= lang('Метки'); ?>
                </a>
            </div> 
        <?php } ?>
    </div>
    
    <?php if (!empty($tags)) { ?>
        <div class="space-tags">
            <div class="menu-m">Метки</div>
            <?php foreach ($tags as  $tag) { ?> 
                <a href="/s/<?= $space_info['space_slug']; ?>/<?= $tag['st_id']; ?>"><?= $tag['st_title']; ?><a>
            <?php } ?>
        </div>
    <?php } ?> 
</aside> 

<?php include TEMPLATE_DIR . '/footer.php'; ?>