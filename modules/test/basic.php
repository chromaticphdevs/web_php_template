<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
    <link
        href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
        rel="stylesheet"
    />
</head>
<body>
    <h1>Upload Image Here.</h1>
    <div>
        <?php
            if(isset($_POST['submit'])) {
                dump(
                    $_FILES
                );
            }
        ?>
        <form method="post" action="#" enctype="multipart/form-data">
            <div id="submitForm" style="margin-bottom: 20px;">
                <input type="submit" name="submit">
            </div>
            <input type="file" class="my-pond" name="filepond[]" multiple/>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
    <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>

    <script>
        $(function(){
            $.fn.filepond.registerPlugin(FilePondPluginImagePreview);
            $.fn.filepond.setDefaults({
                server: {
                    url : 'http://localhost/website_archi/api/filepondupload.php',
                    process : {
                        method : 'post',
                        onload : function(response) {
                            console.log(response)
                        }
                    }
                },
                allowMultiple : true
            });
            // Turn input element into a pond with configuration options
            $('.my-pond').filepond();
        });
    </script>
</body>
</html>