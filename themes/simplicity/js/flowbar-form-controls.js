(function() {
    $(document).ready(function() {
        var currentIndex = 0,
            $currentMissingField = null,
            $nextBtn = $('#next-required'),
            $workflowData = $('#workflow-missing'),
            missingFields = $workflowData.data('missing'),
            missingFieldsOnPage = [],
            modelClass = $workflowData.data('model-class');

        init();

        /**
         * Initializes the script.
         */
        function init() {
            initMissingFields();
            $currentMissingField = $(getFieldElementId(missingFieldsOnPage[0]));
        }

        /**
         * Initializes the missing fields.
         */
        function initMissingFields() {
            missingFields.forEach(function(fieldName) {
                var fieldId = getFieldElementId(fieldName),
                    $field = $(fieldId);

                if ($field.length > 0) {
                    missingFieldsOnPage.push(fieldName)
                }
            });
        }

        /**
         * Go to the next missing field.
         */
        function goToNext() {
            focusOnField();
            updateCurrentIndex();
            updateCurrentField();
        }

        /**
         * Returns the field element ID.
         * @param attribute
         * @returns {string}
         */
        function getFieldElementId(attribute) {
            return '#' + modelClass + '_' + attribute;
        }

        /**
         * Returns the current missing field.
         * @returns {jQuery|HTMLElement}
         */
        function getCurrentMissingField() {
            return $(getFieldElementId(missingFieldsOnPage[currentIndex]));
        }

        /**
         * Focuses on the current field.
         */
        function focusOnField() {
            $currentMissingField[0].focus();
        }

        /**
         * Updates the current field index.
         */
        function updateCurrentIndex() {
            var max = missingFieldsOnPage.length - 1;

            if (currentIndex < max) {
                currentIndex += 1;
            } else {
                currentIndex = 0;
            }
        }

        /**
         * Updates the current missing field.
         */
        function updateCurrentField() {
            $currentMissingField = getCurrentMissingField();
        }

        /**
         * Register button event handler.
         */
        $nextBtn.on('click', function(e) {
            e.preventDefault();
            goToNext();
        });
    });
})();
