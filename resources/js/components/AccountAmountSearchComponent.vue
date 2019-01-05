<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-info">
                <div class="box-header" style="min-height: 45px">
                    <a href="/admin/card" title="列表" class="btn btn-sm btn-default"><i class="fa fa-list"></i> <span class="hidden-xs">&nbsp;列表</span></a>
                </div>
                <form class="form-horizontal" action="/admin/account-amount-search" method="post" enctype="multipart/form-data">
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-body">
                                    <div class="fields-group">
                                        <div class="form-group"><label class="col-sm-2 control-label">文件</label>
                                            <div class="col-sm-8">
                                                <div class="input-group input-group-sm">
                                                    <input type="file" name="file" class="form-control">
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
                        <input type="hidden" name="_token" :value="token">
                        <input class="btn btn-info pull-right" type="submit" value="自动填充金额">
                    </div>
                </form>

            </div>
        </div>
    </div>

</template>

<script>
    require('../../../public/vendor/datejs/date-zh-CN')

    export default {
        data() {
            return {
                token:LA.token,
            }
        },


    }

</script>
