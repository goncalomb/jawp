<?php

require '../vendor/autoload.php';

define('DEFAULT_FILE', implode(DIRECTORY_SEPARATOR, [__DIR__, 'default.md']));
define('PAGE_FILE', implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'data', 'page.md']));

$content_raw = '';

if (isset($_POST['content'])) {
    $content_raw = $_POST['content'];
    file_put_contents(PAGE_FILE, $content_raw);
} else if (is_file(PAGE_FILE)) {
    $content_raw = file_get_contents(PAGE_FILE);
} else {
    $content_raw = file_get_contents(DEFAULT_FILE);
}

$config = [];
$content = '';
$error = '';

if (!empty($content_raw)) {
    $parser = new Mni\FrontYAML\Parser();
    try {
        $document = $parser->parse($content_raw);
        $config = $document->getYAML();
        $content = $document->getContent();
    } catch (Exception $e) {
        $error = 'Malformed YAML Front Matter or Markdown';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="markdown.css" rel="stylesheet">
        <title><?php echo isset($config['title']) ? htmlspecialchars($config['title']) : '-'; ?></title>
        <style>
            body {
                font-family: sans-serif;
                width: 750px;
                margin: 0 auto;
            }
            #edit {
                display: none;
            }
            form textarea {
                width: 100%;
                height: 85vh;
                resize: none;
            }
            .error {
                color: red;
            }
        </style>
    </head>
    <body>
        <header>
            <p>
                <small><a href="javascript:void(0);" onclick="edit()">EDIT</a></small>
                <small><a href="javascript:void(0);" onclick="location.reload()">RELOAD</a></small>
            </p>
        </header>
        <main>
            <?php echo $error ? '<p class="error">' . $error . '</p>' . "\n" : "<!-- -->\n"; ?>
            <div id="content" class="markdown"><?php echo $content; ?></div>
            <form id="edit" method="post">
                <textarea name="content"><?php echo $content_raw; ?></textarea>
                <button type="submit">SAVE</button>
            </form>
        </main>
        <script>
            (function() {
                let editing = false;
                window.edit = () => {
                    editing = !editing;
                    document.querySelector("#content").style.display = editing ? "none" : null;
                    document.querySelector("#edit").style.display = editing ? "block" : null;
                };
                if (<?php echo $config['a-target-blank'] ? 'true' : 'false'; ?>) {
                    document.querySelectorAll("#content a").forEach(el => el.setAttribute("target", "_blank"));
                }
            })();
        </script>
    </body>
</html>
