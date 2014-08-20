(function() {
    /**
     * Focuses the caret on the input field and scrolls it to end when switching between browser tabs.
     */
    $(window).blur(function() {
        var $popover = $('.popover.in');

        if ($popover.length > 0) {
            var $popoverTextField = $popover.find('input[type="text"]', 'textarea'),
                $textField = $popoverTextField[0],
                originalInput = $popoverTextField.val();

            $textField.setSelectionRange(originalInput.length, originalInput.length);
            $textField.scrollLeft = $textField.scrollWidth;
        }
    });
})();
