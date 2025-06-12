<!DOCTYPE html>
<html>
    <head>
        <meta charset='UTF-8'>
        <title>Structured JSON Tables</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    </head>
    <body>
        <h2>JSON</h2>
        <hr>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="jsonFile" accept=".json" required>
            <input type="submit" value="Upload and Read">
        </form>

        <?php
            require("read_json.php");

            function render() {
                if(isset($_FILES['jsonFile'])) {
                    $jsonContent = file_get_contents($_FILES['jsonFile']['tmp_name']);
                    $data = json_decode($jsonContent, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        echo "<p style='color:red;'>Invalid JSON file.</p>";
                        return;
                    } 

                    $json_render = new jsonRender();
                    $json_render->renderJson($data);
                }
            }

            if ($_SERVER['REQUEST_METHOD']) {
                render();
            }
        ?>

        <hr>
    </body>
</html>
