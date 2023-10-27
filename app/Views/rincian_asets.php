<!-- Include the required CSS and JS for Select2 -->
<link href="path/to/select2.min.css" rel="stylesheet">
<script src="path/to/select2.min.js"></script>

<select id="select2Dropdown" name="select2Dropdown">
    <!-- Options will be populated dynamically using JavaScript -->
</select>

<script>
    $(document).ready(function () {
        $('#select2Dropdown').select2({
            placeholder: 'Select an option',
            ajax: {
                url: '<?= site_url('rincian-aset/loadOptions') ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    });
</script>
