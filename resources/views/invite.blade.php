<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">当月邀请榜 TOP 50</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>

    <!-- /.box-header -->
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>用户账号</th>
                    <th>用户姓名</th>
                    <th>当月邀请总人数</th>
                </tr>
                </thead>
                @foreach($invites as $invite)
                <tr>
                    <td width="120px">{{ $invite['username'] }}</td>
                    <td>{{ $invite['real_name'] }}</td>
                    <td>{{ $invite['count'] }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.box-body -->
</div>
