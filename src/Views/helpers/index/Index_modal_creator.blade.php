{{--add modal Create  section--}}
<div class="modal fade" id="create_modal" tabindex="-1" role="dialog" aria-labelledby="create_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="modal_iframe"></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="create_modal_button">Submit Form</button>
            </div>
        </div>
    </div>
</div>
{{--END Create  Modal--}}

{{--add modal Create Upload section--}}
<div class="modal fade" id="create_upload_modal" tabindex="-1" role="dialog" aria-labelledby="create_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modal_dialog_upload" role="document">
        <div class="modal-content create_modal_content_edit_category">
            <div class="modal-header">
                <h5>Upload File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_upload_div">
                <iframe class="modal_iframe" id="create_upload_modal_iframe"></iframe>
            </div>
        </div>
    </div>
</div>
{{--END Create Upload Modal--}}

{{--add modal Create  category--}}
<div class="modal fade" id="create_category_modal" tabindex="-1" role="dialog" aria-labelledby="create_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal_dialog_category" role="document">
        <div class="modal-content create_modal_content_edit_category">
            <div class="modal-header">
                <h5>Create Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body category_upload_body">
                    <iframe class="modal_iframe create_category_modal_iframe" id="modal_iframe_category"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="create_category_modal_button">save</button>
                <button type="button" class="btn btn-primary" id="create_category_modal_button_close">save & close</button>
            </div>
        </div>
    </div>
</div>
{{--END Create  Modal--}}

{{--add modal Edit Create  category--}}
<div class="modal fade" id="create_edit_category_modal" tabindex="-1" role="dialog" aria-labelledby="create_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal_dialog_category" role="document">
        <div class="modal-content create_modal_content_edit_category">
            <div class="modal-header">
                <h5 class="title_edit_category"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body category_upload_body">
                    <iframe class="modal_iframe create_edit_category_modal_iframe" id="modal_iframe_edit_category"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="create_edit_category_modal_button">save</button>
                <button type="button" class="btn btn-primary" id="create_edit_category_modal_button_close">save & close</button>
            </div>
        </div>
    </div>
</div>
{{--END Create  Modal--}}

{{--add modal Edit File Name--}}
<div class="modal fade" id="create_edit_file_name_modal" tabindex="-1" role="dialog" aria-labelledby="create_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal_dialog_category" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="title_edit_file_name"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal_body_edit_file_name">
                    <iframe class="modal_iframe create_edit_file_name_modal_iframe" id="modal_iframe_edit_file_name"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="create_edit_file_name_modal_button">save</button>
                <button type="button" class="btn btn-primary" id="create_edit_file_name_modal_button_close">save & close</button>
            </div>
        </div>
    </div>
</div>
{{--END Create  Modal--}}

{{--add modal Edit Picture--}}
<div class="modal fade" id="create_edit_picture_modal" tabindex="-1" role="dialog" aria-labelledby="create_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal_dialog_category" role="document">
        <div class="modal-content create_modal_content_edit_picture" >
            <div class="modal-header">
                <nav class="nav nav-pills nav-justified">
                    <a class="nav-link active" id="nva_orginal">Orginal Image</a>
                    <div class="nav-link" id="nva_large">Large Image</div>
                    <a class="nav-link" id="nva_medium">Medium Image</a>
                    <a class="nav-link" id="nva_small">Small Image</a>
                </nav>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal_body_edit_picture">
                    <iframe class="modal_iframe create_edit_picture_modal_iframe " id="modal_iframe_edit_picture"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="create_edit_picture_modal_button" data-type="orginal">Crop Image</button>
            </div>
        </div>
    </div>
</div>
{{--END Create  Modal--}}