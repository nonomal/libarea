<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="w-100">

    <h1><?php echo $data['h1']; ?></h1>

    <div class="box create">
        <form action="/post/create" method="post">
            <?= csrf_field() ?>
            <div class="boxline">
                <label for="post_title">Заголовок</label>
                <input id="title" class="add" type="text" name="post_title" /><br />
            </div>   
            <?php if($uid['trust_level'] == 5) { ?>
                <div class="boxline">
                    <label for="post_title">URL</label>
                    <input id="link" class="add-url" type="text" name="post_url" />
                    <input id="graburl" type="submit_url" name="submit_url" value="Извлечь" />
                    <br>
                </div> <?php } ?>
                <div class="boxline">
                    <label for="post_title">Превью</label>
                        <textarea class="content_preview" name="content_preview" placeholder=""></textarea>
                    <br />
                    <label for="post_title"></label>    
                    <div class="box_h">Около 160 символов</div>
                </div>
                
            <div class="boxline">
                <?php include TEMPLATE_DIR . '/post/md-forma.php'; ?>
            </div>
            
            <div class="boxline">
                <label for="post_content">Пространствa</label>
                <select name="space_id">
                    <?php foreach ($space as $sp) { ?>
                        <option value="<?= $sp['space_id']; ?>"><?= $sp['space_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <input type="submit" name="submit" value="Написать" />
        </form>
        <br>
        <details>
            <summary>Доступно форматирование Markdown</summary>
            <p> 
            <table>
              <tbody><tr>
                <td width="125"><em>курсив</em></td>
                <td>окружить текст <tt>*звездочками*</tt></td>
              </tr>
              <tr>
                <td><strong>жирный</strong></td>
                <td>окружить текст <tt>**двумя звездочками**</tt></td>
              </tr>
              <tr>
                <td><strike>зачеркнутый</strike></td>
                <td>окружить текст <tt>~~двумя тильдами~~</tt></td>
              </tr>
              <tr>
                <td><tt>од (строка)</tt></td>
                <td>окружить текст <tt>`обратными ковычками`</tt></td>
              </tr>
              <tr>
                <td><a href="http://example.com/">связаный текст</a></td>
                <td><tt>[связанный текст](http://example.com/)</tt> или просто URL-адрес для создания без заголовка</td>
              </tr>
              <tr>
                <td><blockquote> цитата</blockquote></td>
                <td><tt>&gt;</tt> текс цитаты </td>
              </tr>
              </tbody></table>
            </p>
        </details>
        <br>
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 