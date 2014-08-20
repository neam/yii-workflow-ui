/**
 * Shows/hides the import and upload buttons on the subtitle translation page.
 */
(function() {
    var $fileField =  $('#VideoFile_subtitles_import_media_id');

    if (typeof $fileField !== 'undefined') {
        var $form = $fileField.parents('form'),
            $uploadButton = $('a.btn', '.controls'),
            $importButton = $('input[type="submit"]');

        // Toggle buttons on page load
        if ($fileField.val() === '') {
            $importButton.hide();
        } else {
            $uploadButton.hide();
        }

        // Toggle buttons on form state change
        $form.on('change', function() {
            if ($fileField.val() === '') {
                $importButton.hide();
                $uploadButton.show();
            } else {
                $importButton.show();
                $uploadButton.hide();
            }
        });
    }
})();
