if ($("#form-create").length > 0) {
    $("#form-create").validate({
        ignore: '*:not([name])',
        rules: {
            case_number: {
                required: true,
            },
            clarification: {
                required: true,
            },
            trial_date: {
                required: true,
            },
            mediator: {
                required: true,
            },
            notes: {
                required: true,
            },
            "name_penggugat[]": {
                required: true,
            },
            "place_of_birth_penggugat[]": {
                required: true,
            },
            "date_of_birth_penggugat[]": {
                required: true,
            },
            "name_tergugat[]": {
                required: true,
            },
            "place_of_birth_tergugat[]": {
                required: true,
            },
            "date_of_birth_tergugat[]": {
                required: true,
            },

        },
        messages: {
            case_number: {
                required: function() {
                    toastr.error($('#case_number').attr('placeholder') +' Harus Diisi')
                },
            },
            clarification: {
                required: function() {
                    toastr.error($('#clarification').attr('placeholder') +' Harus Diisi')
                },
            },
            trial_date: {
                required: function() {
                    toastr.error($('#trial_date').attr('placeholder') +' Harus Diisi')
                },
            },
            notes: {
                required: function() {
                    toastr.error($('#notes').attr('placeholder') +' Harus Diisi')
                },
            },
            mediator: {
                required: function() {
                    toastr.error($('#mediator').attr('data-placeholder') +' Harus Diisi')
                },
            },
            qty: {
                required: function() {
                    toastr.error($('#point').attr('placeholder') +' Harus Diisi')
                },
            },
            "name_penggugat[]": {
                required: function() {
                    toastr.error('Nama Penggugat Harus Diisi')
                },
            },
            "place_of_birth_penggugat[]": {
                required: function() {
                    toastr.error('Tempat Lahir Penggugat Harus Diisi')
                },
            },
            "date_of_birth_penggugat[]": {
                required: function() {
                    toastr.error('Tanggal Lahir Penggugat Harus Diisi')
                },
            },
            "name_tergugat[]": {
                required: function() {
                    toastr.error('Nama Tergugat Harus Diisi')
                },
            },
            "place_of_birth_tergugat[]": {
                required: function() {
                    toastr.error('Tempat Lahir Tergugat Harus Diisi')
                },
            },
            "date_of_birth_tergugat[]": {
                required: function() {
                    toastr.error('Tanggal Lahir Tergugat Harus Diisi')
                },
            },
        },
        debug: true,
        submitHandler : function(form) {
            form.submit();
        }
    })
}