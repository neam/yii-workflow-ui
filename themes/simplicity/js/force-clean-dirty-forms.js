/**
 * Forcefully cleans a form the dirty forms.
 */
(function() {
    $('form').on('change', function() {
        $('form').dirtyForms('setClean');
    });
})();
