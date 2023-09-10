<?php build('content') ?>
    <!-- We'll transform this input into a pond -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Image Upload</h4>
        </div>

        <div class="card-body">
            <section style="all:unset">
                <input type="file" class="my-pond" name="filepond"/>
            </section>
        </div>
    </div>
<?php endbuild()?>

<?php build('scripts')?>
<!-- include jQuery library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>

<!-- include FilePond library -->
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

<!-- include FilePond plugins -->
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>

<!-- include FilePond jQuery adapter -->
<script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>

<script>
  $(function(){
  
    // First register any plugins
    $.fn.filepond.registerPlugin(FilePondPluginImagePreview);

    // Turn input element into a pond
    $('.my-pond').filepond();

    // Set allowMultiple property to true
    $('.my-pond').filepond('allowMultiple', true);
  
    // Listen for addfile event
    $('.my-pond').on('FilePond:addfile', function(e) {
        alert('file added');
    });

    // Manually add a file using the addfile method
    // $('.my-pond').first().filepond('addFile', 'index.html').then(function(file){
    //   console.log('file added', file);
    // });
  
  });
</script>
<?php endbuild()?>

<?php loadTo()?>