<?php
$bookId = $_GET['bookid'];
require_once __DIR__ . '/classes/book.php';
$book = new Book();
$bookInfo = $book->getBookInfo($bookId);
$bookTags = $book->getBookTags($bookId);
$tags = $book->getAllTag();
$genres = $book->getAllGenre();
$platforms = $book->getAllPlatform();

require_once __DIR__ . '/header.php';
?>
<h4>編集</h4>
<form method="POST" action="update_db.php" class="input-form">

    <div>
    <label for="title">タイトル：</label>
    <input type="text" id="title" name="title" value="<?= $bookInfo['title'] ?>">
    </div>

    <div>
    <label for="author">作者：</label>
    <input type="text" id="author" name="author" value="<?= $bookInfo['author'] ?>">
    </div>

    <div>
    <label for="genre">ジャンル：</label>
        <select id="genre" name="genre">
        <?php
        foreach ($genres as $genre) {
            if ($bookInfo['genre_id'] == $genre['ident']) {
                echo '<option value="' . $genre['ident'] . '" selected>' . $genre['name'] . '</option>';
            } else {
                echo '<option value="' . $genre['ident'] . '">' . $genre['name'] . '</option>';
            }
        }
        ?>
        <option value="new">新しいジャンル</option>
        </select>
    <input type="text" id="new_genre" name="new_genre" value="" style="display: none;">
    <input type="color" id="genre_color" name="genre_color" value="#FFFFFF" style="display: none;">
    </div>

    <div>
    <label>タグ：</label>
    <span id="tag_container">
    <div>
    &emsp;    
    <?php
    $bookArray = [];
    foreach ($bookTags as $bookTag) {
        array_push($bookArray, $bookTag['ident']);
    }
    $i = 0;
    foreach ($tags as $tag) {
        if (in_array($tag['ident'], $bookArray)) {
            echo '<span class="tag-span"><label style="width:auto;"><input type="checkbox" name="tag[]" value="' . $tag['ident'] . '" checked>' . $tag['name'] . '</label></span>&emsp;';
        } else {
            echo '<span class="tag-span"><label style="width:auto;"><input type="checkbox" name="tag[]" value="' . $tag['ident'] . '">' . $tag['name'] . '</label></span>&emsp;';
        }
        $i++;
        if ($i == 3) {
            echo '</div><div>&emsp;';
            $i = 0;
        }
    }
    ?>
    <button type="button" id="add_tag">＋</button>
    </div>
    <input type="text" id="new_tag" name="new_tag[]" style="display: none;">
    </span>
    </div>

    <div>
    <label for="word">文字数：</label>
    <input type="text" id="word" name="word" value="<?= $bookInfo['word_count'] ?>">万字
    </div>

    <div>
    <label for="url">リンク：</label>
    <input type="url" id="url" name="url" value="<?= $bookInfo['url'] ?>">
    </div>

    <div>
    <label>ステータス：</label>
    <?php 
    if ($bookInfo['status'] == 0) {
    ?>
    <label style="width:auto;"><input type="radio" id="unread" name="status" value="0" checked>未読</label>
    <label style="width:auto;"><input type="radio" id="progress" name="status" value="1">読書中</label>
    <label style="width:auto;"><input type="radio" id="read" name="status" value="2">完読</label><br>
    <?php
    } elseif ($bookInfo['status'] == 1) {
    ?>
    <label style="width:auto;"><input type="radio" id="unread" name="status" value="0">未読</label>
    <label style="width:auto;"><input type="radio" id="progress" name="status" value="1" checked>読書中</label>
    <label style="width:auto;"><input type="radio" id="read" name="status" value="2">完読</label><br>
    <?php
    } else {
    ?>
    <label style="width:auto;"><input type="radio" id="unread" name="status" value="0">未読</label>
    <label style="width:auto;"><input type="radio" id="progress" name="status" value="1">読書中</label>
    <label style="width:auto;"><input type="radio" id="read" name="status" value="2" checked>完読</label><br>
    <?php
    }
    ?>
    </div>

    <div>
    <label for="cp">主人公：</label><br>
    <input type="text" id="cp" name="cp" value="<?= $bookInfo['cp'] ?>">
    </div>

    <div>
    <label>アニメ/テーマ曲：</label>
    <?php
    if ($bookInfo['fandom'] == 0) {
    ?>
        <label style="width:auto;"><input type="radio" id="no_fandom" name="fandom" value="0" checked>✖</label>
        <label style="width:auto;"><input type="radio" id="with_fandom" name="fandom" value="1">🔵</label>
        &emsp;
        <button type="button" id="add_fandom" style="display: none;">＋</button>
        </div>
        <div id="fandom_container" style="display: none;">
            <div class="input_fandom" style="display: block;">
                <label for="fandom_platform" style="width:auto;">プラットフォーム：</label>
                <select class="fandom_platform" name="fandom_platform[]">
                <?php
                    foreach ($platforms as $platform) {
                        echo '<option value="' . $platform['ident'] . '">' . $platform['name'] . '</option>';
                    }
                ?>
                <option value="new_platform">新しいプラットフォーム</option>
                </select>
                &nbsp;
                <input type="text" class="new_fandom_platform" name="new_fandom_platform[]" value="" style="display: none;">
                &emsp;
                <label for="fandom_url" style="width:auto;">リンク：</label>
                <input type="text" name="fandom_url[]" size="30">
                &emsp;
                <label for="fandom_memo" style="width:auto;">メモ：</label>
                <input type="text" name="fandom_memo[]">
            </div>
        </div>
    <?php
    } else {
        $fandoms = $book->getFandom($bookInfo['ident'])
    ?>
        <label style="width:auto;"><input type="radio" id="no_fandom" name="fandom" value="0">✖</label>
        <label style="width:auto;"><input type="radio" id="with_fandom" name="fandom" value="1" checked>🔵</label>
        &emsp;
        <button type="button" id="add_fandom">＋</button>
        </div>
        <div id="fandom_container" style="display: block;">
            <?php
            foreach ($fandoms as $fandom) {
            ?>
                <div style="display: block;">
                <label for="fandom_platform" style="width:auto;">プラットフォーム：</label>
                <select class="fandom_platform" name="fandom_platform[]">
                <?php
                    foreach ($platforms as $platform) {
                        if ($fandom['platform_id'] == $platform['ident']) {
                            echo '<option value="' . $platform['ident'] . '" selected>' . $platform['name'] . '</option>';
                        } else {
                            echo '<option value="' . $platform['ident'] . '">' . $platform['name'] . '</option>';
                        }
                    }
                ?>
                <option value="new_platform">新しいプラットフォーム</option>
                </select>
                &nbsp;
                <input type="text" class="new_fandom_platform" name="new_fandom_platform[]" value="" style="display: none;">
                &emsp;
                <label for="fandom_url" style="width:auto;">リンク：</label>
                <input type="text" name="fandom_url[]" value="<?= $fandom['url'] ?>" size="30">
                &emsp;
                <label for="fandom_memo" style="width:auto;">メモ：</label>
                <input type="text" name="fandom_memo[]" value="<?= $fandom['memo'] ?>">
                </div>
            <?php
            }
            ?>
        </div>
    <?php
    }
    ?>

    <div>
    <label>朗読/ドラマCD：</label>
    <?php
    if ($bookInfo['dramacd'] == 0) {
    ?>
        <label style="width:auto;"><input type="radio" id="no_drama" name="dramacd" value="0" checked>✖</label>
        <label style="width:auto;"><input type="radio" id="with_drama" name="dramacd" value="1">🔵</label>
        &emsp;
        <button type="button" id="add_dramacd" style="display: none;">＋</button>
        </div>
        <div id="dramacd_container" style="display: none;">           
            <div class="input_dramacd" style="display: block;">
                <label for="dramacd_platform" style="width:auto;">プラットフォーム：</label>
                <select class="dramacd_platform" id="dramacd_platform" name="dramacd_platform[]">
                <?php
                    foreach ($platforms as $platform) {
                        echo '<option value="' . $platform['ident'] . '">' . $platform['name'] . '</option>';
                    }
                ?>
                <option value="new_platform">新しいプラットフォーム</option>
                </select>
                &nbsp;
                <input type="text" class="new_dramacd_platform" name="new_dramacd_platform[]" value="" style="display: none;">
                &emsp;
                <label for="dramacd_url" style="width:auto;">リンク：</label>
                <input type="text" name="dramacd_url[]" size="30">
                &emsp;
                <label for="dramacd_code" style="width:auto;">メモ：</label>
                <input type="text" name="dramacd_code[]">
            </div>
        </div>
    <?php
    } else {
        $dramacds = $book->getDramacd($bookInfo['ident']);
    ?>
        <label style="width:auto;"><input type="radio" id="no_drama" name="dramacd" value="0">✖</label>
        <label style="width:auto;"><input type="radio" id="with_drama" name="dramacd" value="1" checked>🔵</label>
        &emsp;
        <button type="button" id="add_dramacd">＋</button>
        </div>
        <div id="dramacd_container" style="display: block;">
            <?php
            foreach ($dramacds as $dramacd) {
            ?>
                <div style="display: block;">
                <label for="dramacd_platform" style="width:auto;">プラットフォーム：</label>
                <select class="dramacd_platform" name="dramacd_platform[]">
                <?php
                    foreach ($platforms as $platform) {
                        if ($dramacd['platform_id'] == $platform['ident']) {
                            echo '<option value="' . $platform['ident'] . '" selected>' . $platform['name'] . '</option>';
                        } else {
                            echo '<option value="' . $platform['ident'] . '">' . $platform['name'] . '</option>';
                        }
                    }
                ?>
                <option value="new_platform">新しいプラットフォーム</option>
                </select>
                &nbsp;
                <input type="text" class="new_dramacd_platform" name="new_dramacd_platform[]" value="" style="display: none;">
                &emsp;
                <label for="dramacd_url" style="width:auto;">リンク：</label>
                <input type="text" name="dramacd_url[]" value="<?= $dramacd['url'] ?>" size="30">
                &emsp;
                <label for="dramacd_code" style="width:auto;">メモ：</label>
                <input type="text" name="dramacd_code[]" value="<?= $dramacd['code'] ?>">
                </div>
            <?php
            }
            ?>
        </div>
    <?php
    }
    ?>

    <div>
    <label for="mascot">代表的なもの：</label><br>
    <input type="text" id="mascot" name="mascot" value="<?= $bookInfo['mascot'] ?>">
    </div>

    <div>
    <label for="remark">リマーク：</label><br>
    <input type="text" id="remark" name="remark" value="<?= $bookInfo['remark'] ?>">
    </div>

    <div>
    <label for="report">感想：</label><br>
    <textarea id="report" name="report" rows="4" cols="50"><?= $bookInfo['content'] ?></textarea>
    </div>

    <input type="hidden" name="bookid" value="<?= $bookInfo['ident'] ?>">
    <input type="submit" value="保存">

</form>

<div style="display: none;">

<div class="input_fandom" style="display: block;">
    <label for="fandom_platform" style="width:auto;">プラットフォーム：</label>
    <select class="fandom_platform" name="fandom_platform[]">
        <?php
            foreach ($platforms as $platform) {
                echo '<option value="' . $platform['ident'] . '">' . $platform['name'] . '</option>';
            }
        ?>
        <option value="new_platform">新しいプラットフォーム</option>
    </select>
    &nbsp;
    <input type="text" class="new_fandom_platform" name="new_fandom_platform[]" value="" style="display: none;">
    &emsp;
    <label for="fandom_url" style="width:auto;">リンク：</label>
    <input type="text" name="fandom_url[]" size="30">
    &emsp;
    <label for="fandom_memo" style="width:auto;">メモ：</label>
    <input type="text" name="fandom_memo[]">
</div>

<div class="input_dramacd" style="display: block;">
    <label for="dramacd_platform" style="width:auto;">プラットフォーム：</label>
    <select class="dramacd_platform" id="dramacd_platform" name="dramacd_platform[]">
        <?php
            foreach ($platforms as $platform) {
                echo '<option value="' . $platform['ident'] . '">' . $platform['name'] . '</option>';
            }
        ?>
        <option value="new_platform">新しいプラットフォーム</option>
    </select>
    &nbsp;
    <input type="text" class="new_dramacd_platform" name="new_dramacd_platform[]" value="" style="display: none;">
    &emsp;
    <label for="dramacd_url" style="width:auto;">リンク：</label>
    <input type="text" name="dramacd_url[]" size="30">
    &emsp;
    <label for="dramacd_code" style="width:auto;">メモ：</label>
    <input type="text" name="dramacd_code[]">
</div>

</div>


<script>
    var customGenre = document.getElementById("new_genre");
    var customColor = document.getElementById("genre_color");
    var selectOptions = document.getElementById("genre");
    var addTagButton = document.getElementById("add_tag");
    var selectFandom = document.getElementsByName("fandom");
    var customFandom = document.getElementById("fandom_container");
    var selectDramaCD = document.getElementsByName("dramacd");
    var customDramaCD = document.getElementById("dramacd_container");
    var addFandomButton = document.getElementById("add_fandom");
    var addDramaButton = document.getElementById("add_dramacd");

    selectOptions.addEventListener("change", function () {
        if (selectOptions.value === "new") {
            customGenre.style.display = "inline-block";
            customGenre.value = ""; 
            customColor.style.display = "inline-block";
            customColor.value = "#FFFFFF";
        } else {
            customGenre.style.display = "none";
            customColor.style.display = "none";
        }
    });

    function duplicateTag() {
        const tagInput = document.getElementById("new_tag");
        const clonedTagInput = tagInput.cloneNode(true);

        clonedTagInput.style.display = "inline-block";

        document.getElementById("tag_container").appendChild(clonedTagInput);
    }

    addTagButton.addEventListener("click", duplicateTag);
    
    selectFandom.forEach(function (selectFandom) {
        selectFandom.addEventListener("change", function () {
            if (selectFandom.value === "1") {
                customFandom.style.display = "block";
                addFandomButton.style.display = "inline-block";
            } else {
                customFandom.style.display = "none";
                addFandomButton.style.display = "none";
            }
        });
    });
    
    selectDramaCD.forEach(function (selectDramaCD) {
        selectDramaCD.addEventListener("change", function () {
            if (selectDramaCD.value === "1") {
                customDramaCD.style.display = "block";
                addDramaButton.style.display = "inline-block";
            } else {
                customDramaCD.style.display = "none";
                addDramaButton.style.display = "none";
            }
        });
    });

    function setupSelectFandom(selectElement) {
        selectElement.addEventListener("change", newFandomPlatform);
    }

    function setupSelectDrama(selectElement) {
        selectElement.addEventListener("change", newDramaPlatform);
    }

    function duplicateFandomRow() {
        const fandomRow = document.querySelector(".input_fandom");
        const clonedFandomRow = fandomRow.cloneNode(true);
        clonedFandomRow.style.display = "block";
        const clonedFandomSelect = clonedFandomRow.querySelector('select[name="fandom_platform[]"]');
        const customFandomPlatform = clonedFandomRow.querySelector('input[name="new_fandom_platform[]"]');

        clonedFandomSelect.value = "";
        customFandomPlatform.value = "";
        customFandomPlatform.style.display = "none";
    
        clonedFandomSelect.addEventListener("change", newFandomPlatform);
    
        document.getElementById("fandom_container").appendChild(clonedFandomRow);
    }
    
    function duplicateDramaRow() {
        const dramaRow = document.querySelector(".input_dramacd");
        const clonedDramaRow = dramaRow.cloneNode(true);
        clonedDramaRow.style.display = "block";
        const clonedDramaSelect = clonedDramaRow.querySelector('select[name="dramacd_platform[]"]');
        const customDramaPlatform = clonedDramaRow.querySelector('input[name="new_dramacd_platform[]"]');

        clonedDramaSelect.value = "";
        customDramaPlatform.value = "";
        customDramaPlatform.style.display = "none";
    
        clonedDramaSelect.addEventListener("change", newDramaPlatform);

        document.getElementById("dramacd_container").appendChild(clonedDramaRow);
    }

    addFandomButton.addEventListener("click", duplicateFandomRow);
    addDramaButton.addEventListener("click", duplicateDramaRow);
    
    function newFandomPlatform(event) {
        var selectValue = event.target.value;
        var customFandomPlatform = event.target.parentElement.querySelector('input[name="new_fandom_platform[]"]');
        if (selectValue === "new_platform") {
            customFandomPlatform.style.display = "inline-block";
            customFandomPlatform.value = ""; 
        } else {
            customFandomPlatform.style.display = "none";
        }
    }

    function newDramaPlatform() {
        var selectValue = event.target.value;
        var customDramaPlatform = event.target.parentElement.querySelector('input[name="new_dramacd_platform[]"]');
        if (selectValue === "new_platform") {
            customDramaPlatform.style.display = "inline-block";
            customDramaPlatform.value = ""; 
        } else {
            customDramaPlatform.style.display = "none";
        }
    }

    var originalSelectElements = document.querySelectorAll('select[name="fandom_platform[]"]');
    originalSelectElements.forEach(function (select) {
        setupSelectFandom(select);
    });

    var originalSelectElements = document.querySelectorAll('select[name="dramacd_platform[]"]');
    originalSelectElements.forEach(function (select) {
        setupSelectDrama(select);
    });

    function deleteDuplicate() {
        customGenre.style.display = "none";
        customColor.style.display = "none";
        customFandom.style.display = "none";
        customDramaCD.style.display = "none";
    }

</script>

<?php
require_once __DIR__ . '/footer.php';
?>
