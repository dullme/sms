<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-info">
                <div class="box-header" style="min-height: 45px">
                    <a href="/admin/card" title="列表" class="btn btn-sm btn-default"><i class="fa fa-list"></i> <span class="hidden-xs">&nbsp;列表</span></a>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <form class="form-horizontal">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-body">
                                    <div class="fields-group">
                                        <div class="form-group"><label class="col-sm-2 control-label">账号</label>
                                            <div class="col-sm-8" style="width: 390px;">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" placeholder="开始账号" v-model="start_name" name="start_name" value="" class="form-control">
                                                    <span class="input-group-addon" style="border-left: 0px; border-right: 0px;">-</span>
                                                    <input type="text" placeholder="结束账号" v-model="end_name" name="end_name" value="" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group"><label class="col-sm-2 control-label">金额</label>
                                            <div class="col-sm-8" style="width: 390px;">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" placeholder="金额" v-model="amount" name="amount" value="" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- /.box-body -->

                <div class="box-footer">
                    <button class="btn btn-info pull-right" v-on:click="saveAdds">保存</button>
                </div>

            </div>
        </div>
    </div>

</template>

<script>
    require('../../../public/vendor/datejs/date-zh-CN')

    export default {
        data() {
            return {
                start_name:'',
                end_name:'',
                amount:''
            }
        },

        mounted() {
        },

        methods: {
            //保存修改
            saveAdds(){
                axios.post("/admin/add-account-amount", {
                    start_name:this.start_name,
                    end_name:this.end_name,
                    amount:this.amount,
                }).then(response => {
                    this.start_name='';
                    this.end_name = '';
                    this.amount = '';
                    toastr.success(response.data.data);
                }).catch(error => {
                    toastr.error(error.response.data.message);
                });
            }

        }

    }

</script>
