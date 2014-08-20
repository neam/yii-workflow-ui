/**
 * Toggles the Add Selected button when checking items in modal forms.
 */
(function() {
    var $form = $('#item-form'),
        $addSelectedBtn = $('input#add-selected');

    $addSelectedBtn.attr('disabled', 'disabled');

    $form.on('change', function() {
        if ($form.hasClass('dirty')) {
            $addSelectedBtn.removeAttr('disabled');
        }

        $form.dirtyForms('setClean'); // disable dirty forms
    });
})();
