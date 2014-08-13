(function() {
    var $firstNameField = $('#GMProfile_first_name'),
        $lastNameField = $('#GMProfile_last_name'),
        $usernameField = $('#GMRegistrationForm_username'),
        usernameModifiedManually = false;

    /**
     * Suggests a username.
     */
    function suggestUsername() {
        var suggestion = $firstNameField.val() + $lastNameField.val();
        suggestion = suggestion.toLowerCase();
        suggestion = suggestion.replace(/ /g, ''); // strip whitespace
        suggestion = suggestion.replace(/[^a-zA-Z 0-9]+/g, ''); // strip non-alphanumeric characters
        $usernameField.val(suggestion);
    }

    // Generate suggestions on keyup
    $($firstNameField).add($lastNameField).on('keyup', function() {
        if (!usernameModifiedManually) {
            suggestUsername();
        }
    });

    // Stop suggestions after manually modifying the username
    $($usernameField).on('keyup', function() {
        usernameModifiedManually = true;
    });

    // Offer suggestions again if the username becomes blank
    $($usernameField).on('change', function() {
        if ($usernameField.val().length === 0) {
            usernameModifiedManually = false;
            suggestUsername();
        }
    });
})();
