<?php include "base.php"; ?>
<div class="space-y-4 p-4 mt-6 ">
    <p class="mt-2 mx-auto">
        Searching for the following Keywords: <br><br>
        <?php foreach ($s_keyWords as $keyWord) : ?>
            <?php $keyWord = json_decode($keyWord); ?>
            <span style="background-color:<?= $keyWord->color; ?>;" class="rounded-md p-1 font-semibold"><?= $keyWord->word; ?></span>
        <?php endforeach; ?>
    </p>
    <div class="p-6 mx-auto max-w-sm bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
        <h1 class="mb-2 text-2xl font-bold tracking-tight">
            <?php if (isset($_GET['error'])) : ?>
                <span class="text-red-500"><?= $_GET['error'] ?>
                </span>
            <?php endif; ?>
        </h1>
        <form action="" method="get">
            <div class="mb-6">
                <label for="base-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Input your URL</label>
                <input type="url" name="url" id="base-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
            </div>
            <button name="submit" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Go</button>
        </form>
    </div>
    <?php
    if (isset($_GET['submit'])) {
        if (empty($_GET['url'])) {
            $error = urlencode("No URL was provided!!");
            header("Location:index.php?error=$error");
        }

        require 'vendor/autoload.php';


        $web = new \spekulatius\phpscraper();


        try {
            $web->go($_GET['url']);
        } catch (\Exception $e) {
            echo "<h1 class='mb-2 text-2xl font-bold tracking-tight text-red-500'>" . $e->getMessage() . "</h1> ";
        }
        function highlightWords($text, $word) {
            $text = preg_replace('#' . preg_quote($word->word) . ' #i ', '<span name="keywords_found" class="underline rounded font-semibold" style="background-color:' . $word->color . ';">\\0</span>', $text);
            return "<p class='font-normal text-gray-700'>$text</p>";
        }
    ?>

        <div>
            <p class="text-lg"><?= "This page contains " . count($web->paragraphs) . " paragraphs."; ?></p>
            <p class="mb-2"> Found the following keywords <span id="keywords_found" class="text-red-500 hover:underline font-semibold italic"></span></p>

            <div class="border m-4 p-2 rounded-md">
                <?php foreach ($web->paragraphs as $paragraph) : ?>
                    <?php foreach ($s_keyWords as $keyWord) : ?>
                        <?php $keyWord = json_decode($keyWord); ?>
                        <?php $paragraph =  highlightWords($paragraph, $keyWord); ?>
                    <?php endforeach; ?>
                    <?= $paragraph; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php
    }
    ?>
</div>
</body>

</html>
<script>
    let keywords_found = [];
    document.getElementsByName('keywords_found').forEach(data => {
        keywords_found.push(data.innerHTML.toLowerCase());
    });
    let unique = [...new Set(keywords_found)];
    // console.log(unique);
    document.getElementById('keywords_found').innerText = unique.toString();
</script>