<?php include "base.php";

?>
<?php $st_keywords = []; ?>
<?php foreach ($s_keyWords as $keyWord) : ?>
    <?php $keyWord = json_decode($keyWord); ?>
    <?php array_push($st_keywords, strtolower($keyWord->word)); ?>
<?php endforeach; ?>

<div class="space-y-4 p-4 mt-6 ">
    <div class="mt-2 mx-auto">
        Searching for the following Keywords: <br><br>
        <?php foreach (array_slice($s_keyWords, 0, 11) as $keyWord) : ?>
            <?php $keyWord = json_decode($keyWord); ?>
            <button style="background-color:<?= $keyWord->color; ?>;" type="button" class="text-white focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-2 py-1 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"><?= ucfirst($keyWord->word); ?></button>
        <?php endforeach; ?>
        <div x-data="{ keywords: false }">
            <button @click.prevent="keywords = true" type="button" class="bg-green-700 text-white focus:outline-none focus:ring-4 focus:ring-blue-300 font-semibold italic rounded-full text-sm px-2 py-1 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">+<?= count($st_keywords) - 11; ?> more</button>
            <template x-if="keywords">
                <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center z-10" style="background-color: rgba(0,0,0,.5);">
                    <div class="bg-green-50 h-auto p-2 md:max-w-screen-lg md:p-2 lg:p-4 shadow-xl rounded mx-2 md:mx-0" @click.away="open = false">
                        <button @click="keywords = false" type="button" class="text-right mt-2 w-10 inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base mb-2 ">X</button>
                        <div class="text-left  mt-4">
                            <?php foreach ($s_keyWords as $keyWord) : ?>
                                <?php $keyWord = json_decode($keyWord); ?>
                                <button style="background-color:<?= $keyWord->color; ?>;" type="button" class="text-white focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-2 py-1 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"><?= ucfirst($keyWord->word); ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>






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
                <input type="url" name="url" id="base-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 " required>
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

        function wp_strip_all_tags($string, $remove_breaks = false) {
            $string = preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $string);
            $string = strip_tags($string);

            if ($remove_breaks) {
                $string = preg_replace('/[\r\n\t ]+/', ' ', $string);
            }

            return trim($string);
        }
        function highlightWords($text, $word) {
            $text = preg_replace(' # ' . preg_quote($word->word) . ' #i ', '<span name="keywords_found" class="underline rounded font-semibold text-white" style="background-color:' . $word->color . ';">\\0</span>', $text);
            return "<p class='font-normal text-gray-700'>$text</p>";
        }

        $html = file_get_contents($_GET['url']);

        $parser = new \Smalot\PdfParser\Parser();

        if (str_contains($_GET['url'], ".pdf")) {
            $text = $parser->parseContent($html)->getText();
        } else {
            $text = wp_strip_all_tags($html);
        }

        foreach ($s_keyWords as $keyWord) {
            $keyWord = json_decode($keyWord);
            $text =  highlightWords($text, $keyWord);
        }
    ?>

        <div>
            <p class="mb-2"><span id="keywords_found" class="text-red-500 hover:underline font-semibold italic"></span></p>
            <div class="border m-4 p-2 rounded-md">
                <?php echo $text ?>
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
        keywords_found.push(data.innerText.toLowerCase());
    });
    let unique = [...new Set(keywords_found)];

    if (unique.length > 0) {
        document.getElementById('keywords_found').innerText = "Found the following keywords:  " + unique.toString();
    } else {
        document.getElementById('keywords_found').innerText = "Oops, no Keywords were found!"
    }
</script>