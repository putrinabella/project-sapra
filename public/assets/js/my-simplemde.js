document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    var editspekElement = document.getElementById('editspek');

    if (editspekElement) {
        var simplemde = new SimpleMDE({
            element: editspekElement,
            toolbar: false,
            status: false,
            preview: true,
        });

        simplemde.togglePreview();
        simplemde.codemirror.options.readOnly = true;
    }
});
