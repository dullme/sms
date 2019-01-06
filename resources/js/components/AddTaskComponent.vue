<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-info">
                <div class="box-header" style="min-height: 45px">
                    <a href="/admin/task" title="列表" class="btn btn-sm btn-default"><i class="fa fa-list"></i> <span class="hidden-xs">&nbsp;列表</span></a>
                </div>
                <form class="form-horizontal" @submit.prevent="onSubmit" @keydown="errors.clear($event.target.name)">
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-body">
                                    <div class="fields-group">

                                        <div class="form-group" :class="{'has-error': this.errors.has('content')}">
                                            <label class="col-sm-2  control-label">
                                                任务内容
                                                <i class="fa fa-times-circle-o"
                                                   v-if="this.errors.has('content')"></i>
                                                <span v-if="this.errors.has('content')"
                                                      v-text="this.errors.get('content')"></span>
                                            </label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                                    <input type="text" v-model="content"
                                                           @keyup="content = this.event.target.value;"
                                                           name="content"
                                                           class="form-control content" placeholder="任务内容">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group" :class="{'has-error': this.errors.has('price')}">
                                            <label class="col-sm-2  control-label">
                                                任务单价
                                                <i class="fa fa-times-circle-o"
                                                   v-if="this.errors.has('price')"></i>
                                                <span v-if="this.errors.has('price')"
                                                      v-text="this.errors.get('price')"></span>
                                            </label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-cny"></i></span>
                                                    <input style="width: 120px; text-align: right;" type="text" v-model="price"
                                                           @keyup="price = this.event.target.value;"
                                                           name="price"
                                                           class="form-control currency" placeholder="任务单价">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group" :class="{'has-error': this.errors.has('file')}">
                                            <label class="col-sm-2  control-label">
                                                文件
                                                <i class="fa fa-times-circle-o"
                                                   v-if="this.errors.has('file')"></i>
                                                <span v-if="this.errors.has('file')"
                                                      v-text="this.errors.get('file')"></span>
                                            </label>
                                            <div class="col-sm-8">
                                                <div class="input-group input-group-sm">
                                                    <input type="file" id="file" name="file" class="form-control" >
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
                        <span v-if="loading" class="fa fa-spinner fa-spin pull-right"></span>
                        <button v-else class="btn btn-info pull-right" type="submit">添加任务</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</template>

<script>
    import Errors from './core/Errors'

    require('../../../public/vendor/datejs/date-zh-CN')

    export default {
        data() {
            return {
                errors: new Errors(),
                content:'',
                price:'',
                loading:false,
                adds:[],
            }
        },

        mounted() {
            $('.currency').inputmask({
                "alias": "currency",
                "groupSeparator": "",
                "radixPoint": ".",
                "prefix": "",
                "removeMaskOnSubmit": true
            });
        },

        methods: {
            onSubmit(){
                let form_date = new FormData();
                form_date.append('file',$('#file')[0].files[0]);
                form_date.append('content',this.content);
                form_date.append('price',this.price);
                this.loading = true;
                axios({
                    method: 'post',
                    url: '/admin/task-add',
                    data: form_date
                }).then(response => {
                    swal(
                        "SUCCESS",
                        '添加成功！',
                        'success'
                    ).then(function () {
                        location.reload()
                    });
                }).catch(error => {
                    this.loading = false;
                    if(error.response.data.status == false){
                        toastr.success(error.response.data.message);
                    }else{
                        this.errors.record(error.response.data.errors);

                    }
                });
            }

        }

    }

</script>

<style type="text/css">
    .select2 {
        width: 100% !important;
    }

    .rent-list > div {
        margin-bottom: 15px;
    }

    .has-error .input-group-addon, .has-error label {
        border-color: #a94442 !important;
        color: #a94442 !important;
    }
</style>
