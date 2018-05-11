<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Media</a></li>
    </ol>
</nav>
<div class="container">
    <ul class="media-content clearfix col-md-12">
        @if($category)
            <li>
                <div class="media-attachment-info">
                    <div class="clearfix center" data-object="">
                        <a href="" data-id="{{$category->parent_category_id}}" class="link_to_category">
                            <i class="fa fa-level-up img thumbnail-back"></i>
                        </a>
                    </div>
                </div>
            </li>
        @endif
        @foreach($categories as $category)
            <li>
                <div class="media-attachment-info">
                    <a href="" data-id="" class="grid-row-delete pull-right">
                        <i class="fa fa-trash"></i>
                    </a>
                    <a href="#" class="media-attachment-chexbox">
                        <input type="checkbox" class="grid-row-checkbox" data-id=""/>
                    </a>
                    <div class="clearfix center" data-object="">
                        <a title="{{$category['title']}}" href="" data-id="{{$category['id']}}" class="link_to_category"
                           data-description="{{$category['description']}}"
                        ><i class="fa fa-folder img img-category-thumbnail"></i></a>
                    </div>
                    <div class="text-center">
                        <a class="meida-name" href="" title="">{{$category['title']}}</a>
                    </div>
                </div>
            </li>
        @endforeach
        @foreach($files as $file)
            <li>
                <div class="media-attachment-info">
                    <a href="" data-id="13" class="grid-row-delete pull-right">
                        <i class="fa fa-trash"></i>
                    </a>
                    <a href="#" class="media-attachment-chexbox">
                        <input type="checkbox" class="grid-row-checkbox" data-id=""/>
                    </a>
                    <div class="clearfix center">
                        <a title="{{$file['filename']}}" href=""><i class="fa fa-image img-category-thumbnail"></i></a>
                    </div>
                    <div class="text-center">
                        <a class="meida-name" href="" title="">{{$file['filename']}}</a>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
<div class="panel_info">
</div>

<script type="text/javascript">
    $(document).off("click", '.link_to_category');
    $(document).on('click', '.link_to_category', function (e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        show_category(id);
    });

    /**
     * show category function
     * @param id
     */
    function show_category(id) {
        $.ajax({
            type: "POST",
            url: "{{route('LFM.ShowCategory')}}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                category_id: id,
            },
            dataType: "json",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (result) {
                if (result.success == true) {
                    $(".panel-body").empty();
                    $(".panel-body").html(result.html);
                }
            },
            error: function (e) {
            }
        });
    }

    /**
     *
     * @returns array
     */
    function getAttributes() {
        var attrs = {};
        $.each($node[0].attributes, function (index, attribute) {
            attrs[attribute.name] = attribute.value;
        });
        return attrs;
    }
</script>

