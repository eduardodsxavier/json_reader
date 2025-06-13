<!DOCTYPE html>
<html>
    <head>
        <meta charset='UTF-8'>
        <title>Structured JSON Tables</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            h2 { margin-top: 40px; }
            table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
            th, td { border: 1px solid #ccc; padding: 8px; vertical-align: top; }
            th { background-color: #f2f2f2; text-align: left; }
            pre { margin: 0; white-space: pre-wrap; word-wrap: break-word; }
            .error { color: red; font-weight: bold; }
            .nested-table { margin: 10px 0; }
        </style>
    </head>
    <body>
        <h2>JSON</h2>
        <hr>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="jsonFile" accept=".json" required>
            <input type="submit" value="Upload and Read">
        </form>
        <hr>
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
