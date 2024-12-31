<style>
    /*.tox-tinymce {*/
    /*    width: 1270px !important;*/
    /*}*/
    /*@media (max-width: 1000px) {*/
    /*    .tox-tinymce {*/
    /*        width: 900px !important;*/
    /*        margin: auto;*/
    /*    }*/
    /*}*/
    /*@media (max-width: 768px) {*/
    /*    .tox-tinymce {*/
    /*        width: 700px !important;*/
    /*        margin: auto;*/
    /*    }*/
    /*}*/
    /*@media (max-width: 500px) {*/
    /*    .tox-tinymce {*/
    /*        width: 450px !important;*/
    /*        margin: auto;*/
    /*    }*/
    /*}*/
    #FormDiv{
        width: 100% !important;
    }
</style>

<div class="col-md-12">
    <label for="editor">Shortfall Reason:</label>
    <textarea class="form-control tinymce_common_class_selector" name="shortfall_reason" id="editor">{{ old('shortfall_reason') }}</textarea>
</div>

<script src="{{ asset('assets/plugins/tinymce/tinymce.min.js')}}"></script>
<script>
    tinymce.init({
        selector: '.tinymce_common_class_selector',
        plugins: 'lists',
        readonly: false,
        toolbar: 'numlist bullist  undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });
</script>
