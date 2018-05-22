<!-- Modal -->
<div id="myModalForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Select link</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Modules:</label>
                    <select class="form-control select-module">
                        <option value="" selected=""> -- Select Module --</option>
                        <option value="page">Pages</option>
                        <option value="post">Posts</option>
                        <option value="post">Products</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="message-text" class="control-label">Item:</label>
                    <select class="form-control item-module">
                        <option value="" selected=""> -- Select Item --</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="save-item" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


{{-- EDIT --}}
<div id="myModalFormEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-edit"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel-edit">Select link</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Modules:</label>
                    <select class="form-control select-module-edit">
                        <option value="" selected=""> -- Select Module --</option>
                        <option value="page">Pages</option>
                        <option value="post">Posts</option>
                        <option value="post">Products</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="message-text" class="control-label">Item:</label>
                    <select class="form-control item-module-edit">
                        <option value="" selected=""> -- Select Item --</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" data-target="" id="save-item-edit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.select-module').change(function () {
                var module = $(this).val();

                axios.post('{{route('menus.select.module')}}', {
                    module: module
                })
                    .then(function (response) {
                        // console.log(response.data);
                        if (response.status == 200) {
                            if (module == '') {
                                $('.item-module').html('<option value="" selected=""> -- Select Item -- </option>');
                            } else {
                                $('.item-module').html(response.data);
                            }
                        }
                    })
                    .catch(function (error) {
                        console.log('Fail');
                    });
            });
            $('#save-item').click(function () {
                var item = $('.item-module').val();
                $('.modal').modal('hide');
                $('.item-link').attr("value", item);
            });
        });


        // Edit

        $(document).ready(function () {
            $('.select-module-edit').change(function () {
                var module = $(this).val();

                axios.post('{{route('menus.select.module')}}', {
                    module: module
                })
                    .then(function (response) {
                        // console.log(response.data);
                        if (response.status == 200) {
                            if (module == '') {
                                $('.item-module-edit').html('<option value="" selected=""> -- Select Item -- </option>');
                            } else {
                                $('.item-module-edit').html(response.data);
                            }
                        }
                    })
                    .catch(function (error) {
                        console.log('Fail');
                    });
            });
            $('#save-item-edit').click(function () {
                var item = $('.item-module-edit').val();
                var item_link_change = $(this).attr('data-target');
                $('.modal').modal('hide');
                $('#' + item_link_change).attr("value", item);
            });
        });

    </script>
@endpush