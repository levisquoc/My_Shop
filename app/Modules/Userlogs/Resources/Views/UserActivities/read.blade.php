<div class="modal-header">
    <h4 class="modal-title"><i class="voyager-trash"></i> Read list</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>

</div>
<div class="modal-body">
    <table id="tableTrash" class="table table-bordered table-striped mt-2">
        <thead>
        <tr>
            <th>UserID</th>
            <th>UserIP</th>
            <th>Action</th>
            <th>Object</th>
            <th>Value Before</th>
            <th>Value After</th>
            <th>Time</th>
        </tr>
        </thead>
        <tbody>
        @foreach($logs as $items)
            <tr>

                <td>{{$items->user_id}}</td>
                <td>{{$items->user_ip}}</td>
                <td>{{$items->action}}</td>
                <td>{{$items->object}}</td>
                <td>{{$items->value_before}}</td>
                <td>{{$items->value_after}}</td>
                <td>{{Carbon\Carbon::parse($items->created_at)->diffForHumans()}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>