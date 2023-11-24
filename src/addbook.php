<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/classes/book.php';
$book = new Book();
$genres = $book->getAllGenre();
$tags = $book->getAllTag();
$platforms = $book->getAllPlatform();
?>
<!-- 入力フォーム -->
<h4>インフォメーション</h4>
<form method="POST" action="addbook_db.php" class="input-form">
    <div>
    <label for="title">タイトル：</label>
    <input type="text" id="title" name="title" size="30">
    </div>

    <div>
    <label for="author">作者：</label>
    <input type="text" id="author" name="author">
    </div>

    <div>
    <label for="genre">ジャンル：</label>
        <select id="genre" name="genre">
        <?php
        foreach ($genres as $genre) {
            echo '<option value="' . $genre['ident'] . '">' . $genre['name'] . '</option>';
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
    $i = 0;
    foreach ($tags as $tag) {
        echo '<span class="tag-span"><label style="width:auto;"><input type="checkbox" name="tag[]" value="' . $tag['ident'] . '">' . $tag['name'] . '</label></span>&emsp;';
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
    <input type="text" id="word" name="word" size="5">万字
    </div>

    <div>
    <label for="url">リンク：</label>
    <input type="url" id="url" name="url" size="50">
    </div>

    <div>
    <label>ステータス：</label>
    <label style="width:auto;"><input type="radio" id="unread" name="status" value="0">未読</label>
    <label style="width:auto;"><input type="radio" id="progress" name="status" value="1">読書中</label>
    <label style="width:auto;"><input type="radio" id="read" name="status" value="2">完読</label><br>
    </div>

    <div>
    <label for="cp">主人公：</label><br>
    <input type="text" id="cp" name="cp">
    </div>

    <div>
    <label>アニメ/テーマ曲：</label>
    <label style="width:auto;"><input type="radio" id="no_fandom" name="fandom" value="0" checked>✖</label>
    <label style="width:auto;"><input type="radio" id="with_fandom" name="fandom" value="1">🔵</label>
    &emsp;
    <button type="button" id="add_fandom" style="display: none;">＋</button>
    </div>
    <div id="fandom_container" style="display: none;">
        <div class="input_fandom" style="display: block;">
            <label for="fandom_platform" style="width:auto;">平台：</label>
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

    <div>
    <label>朗読/ドラマCD：</label>
    <label style="width:auto;"><input type="radio" id="no_drama" name="dramacd" value="0" checked>✖</label>
    <label style="width:auto;"><input type="radio" id="with_drama" name="dramacd" value="1">🔵</label>
    &emsp;
    <button type="button" id="add_dramacd" style="display: none;">＋</button>
    </div>
    <div id="dramacd_container" style="display: none;">
        <div class="input_dramacd" style="display: block;">
            <label for="dramacd_platform" style="width:auto;">平台：</label>
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

    <div>
    <label for="mascot">代表的なもの：</label><br>
    <input type="text" id="mascot" name="mascot">
    </div>

    <div>
    <label for="remark">リマーク：</label><br>
    <input type="text" id="remark" name="remark" size="30">
    </div>
    
    <div>
    <label for="report">感想：</label><br>
    <textarea id="report" name="report" rows="4" cols="50"></textarea><br>
    </div>

    <input type="reset" value="クリア" onclick="deleteDuplicate()">
    &emsp;
    <input type="submit" value="保存">

</form>


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

    // 新しいジャンルが選択されたら、ジャンル名の入力ボックスとカラーピッカーを表示
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

    // 新しいタグの入力ボックスを追加
    function duplicateTag() {
        const tagInput = document.getElementById("new_tag");
        const clonedTagInput = tagInput.cloneNode(true);

        clonedTagInput.style.display = "inline-block";

        document.getElementById("tag_container").appendChild(clonedTagInput);
    }

    addTagButton.addEventListener("click", duplicateTag);
    
    // 映像作品があると選択されたら、作品詳細入力ボックスを表示
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
    
    // 音声作品があると選択されたら、作品詳細入力ボックスを表示
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

    // 映像作品詳細を追加
    function duplicateFandomRow() {
        const fandomRow = document.querySelector(".input_fandom");
        const clonedFandomRow = fandomRow.cloneNode(true);
        const clonedFandomSelect = clonedFandomRow.querySelector('select[name="fandom_platform[]"]');
        const customFandomPlatform = clonedFandomRow.querySelector('input[name="new_fandom_platform[]"]');

        clonedFandomSelect.value = "";
        customFandomPlatform.value = "";
        customFandomPlatform.style.display = "none";
    
        clonedFandomSelect.addEventListener("change", newFandomPlatform);
    
        document.getElementById("fandom_container").appendChild(clonedFandomRow);
    }
    
    // 音声作品詳細を追加
    function duplicateDramaRow() {
        const dramaRow = document.querySelector(".input_dramacd");
        const clonedDramaRow = dramaRow.cloneNode(true);
        const clonedDramaSelect = clonedDramaRow.querySelector('select[name="dramacd_platform[]"]');
        const customDramaPlatform = clonedDramaRow.querySelector('input[name="new_dramacd_platform[]"]');

        clonedDramaSelect.value = "";
        customDramaPlatform.value = "";
        customDramaPlatform.style.display = "none";
    
        clonedDramaSelect.addEventListener("change", newDramaPlatform);

        document.getElementById("dramacd_container").appendChild(clonedDramaRow);
    }

    // ボタンに関数を割り当てる
    addFandomButton.addEventListener("click", duplicateFandomRow);
    addDramaButton.addEventListener("click", duplicateDramaRow);
    
    // 新しいプラットフォームが選択されたら、入力ボックスを表示（映像作品）
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

    // 新しいプラットフォームが選択されたら、入力ボックスを表示（音声作品）
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

    // ドロップダウンリストに関数を割り当てる
    function setupSelectFandom(selectElement) {
        selectElement.addEventListener("change", newFandomPlatform);
    }

    function setupSelectDrama(selectElement) {
        selectElement.addEventListener("change", newDramaPlatform);
    }

    var originalSelectElements = document.querySelectorAll('select[name="fandom_platform[]"]');
    originalSelectElements.forEach(function (select) {
        setupSelectFandom(select);
    });

    var originalSelectElements = document.querySelectorAll('select[name="dramacd_platform[]"]');
    originalSelectElements.forEach(function (select) {
        setupSelectDrama(select);
    });

    // 入力をクリア
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
