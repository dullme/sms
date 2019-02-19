<div class="row">
    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-header" style="min-height: 45px">
                <a href="/admin/user" title="列表" class="btn btn-sm btn-default"><i class="fa fa-list"></i> <span class="hidden-xs">&nbsp;列表</span></a>
            </div>
            <form class="form-horizontal" action="/admin/delete-task-history" method="post">
                @csrf
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-body">
                                <div class="fields-group">
                                    <div class="form-group {{ $errors->has('days') ? 'has-error' : '' }}">
                                        <label class="col-sm-2 control-label">删除几天前的历史记录</label>
                                        <div class="">
                                            <div class="input-group input-group-sm">
                                                <input type="number" name="days" class="form-control">
                                                @if ($errors->has('days'))
                                                    <span style="color: #dd4b39">{{ $errors->first('days') }}</span>
                                                @endif
                                                @if(Session::has('deleteTaskHistories'))
                                                    <span class="text-danger">{{ Session::get('deleteTaskHistories') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <input class="btn btn-info pull-right" type="submit" value="确定删除">
                </div>
            </form>

        </div>
    </div>
</div>
