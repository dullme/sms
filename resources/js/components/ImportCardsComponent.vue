<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-info">
                <div class="box-header" style="min-height: 45px">
                    <a href="/admin/card" title="列表" class="btn btn-sm btn-default"><i class="fa fa-list"></i> <span class="hidden-xs">&nbsp;列表</span></a>
                    <div class="box-tools">
                        <form onsubmit="return false" enctype="multipart/form-data">
                            <span class="btn btn-default btn-sm" v-on:click="uploadFileClick">
                                批量导入账号
                            </span>
                            <input id="upload-file" type="file" style="display: none">
                        </form>
                        <div role="progressbar" class="progress-bar progress-bar-striped active" :style="'width: '+ this.progress +';'">{{ this.progress }}</div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <table class="table">
                        <tbody v-if="adds.length">
                            <tr>
                                <th>卡号</th>
                                <th>密码</th>
                                <th>初始金额</th>
                            </tr>
                            <tr v-for="(add, index) in adds">
                                <td>{{ add.name }}</td>
                                <td>{{ add.password }}</td>
                                <td>{{ add.amount }}</td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <div style="min-height: 200px; line-height: 200px; font-size: 40px; opacity: .1" class="text-center">
                                <span v-if="loading" class="fa fa-spinner fa-spin"></span>
                                <span v-else>请上传文件</span>
                            </div>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->

                <div class="box-footer" v-if="adds.length">
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
                loading:false,
                adds:[],
                progress:''
            }
        },

        mounted() {
            this.uploadFile();
        },

        methods: {
            uploadFileClick(){
                $('#upload-file').click();
            },

            uploadFile(){
                $('#upload-file').on('change', () => {
                    $('#upload-file').closest('form').submit();
                });
                $('form').submit(() => {
                    let form_date = new FormData();
                    form_date.append('file',$('#upload-file')[0].files[0]);
                    this.loading = true;
                    axios({
                        method: 'post',
                        url: '/admin/import-cards',
                        data: form_date,
                        onUploadProgress: progressEvent => {
                            var complete = (progressEvent.loaded / progressEvent.total * 100 | 0) + '%'
                            this.progress = complete
                        }
                    }).then(response => {
                        this.loading = false;
                        this.adds = response.data.data;
                        this.progress = '';
                        $('#upload-file').val('')
                        console.log(response.data.data);
                    }).catch(error => {
                        this.loading = false;
                        this.progress = '';
                        $('#upload-file').val('');
                        toastr.success(error.response.data.message);
                    });
                });
            },

            //导入水电费
            saveAdds(){
                if(this.adds.length){
                    this.loading = true;
                    axios.post("/admin/save-import-cards", {
                        adds:this.adds,
                    }).then(response => {
                        this.loading = false;
                        this.adds = [];
                        toastr.success('导入成功');
                    }).catch(error => {
                        this.loading = false;
                        if(error.response.data.errors){
                            toastr.error(error.response.data.errors.payable_time[0]);
                        }else{
                            toastr.error(error.response.data.message);
                        }
                    });
                }
            }

        }

    }

</script>
