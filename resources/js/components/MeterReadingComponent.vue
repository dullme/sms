<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-info">
                <div class="box-header">

                    <div class="row">
                        <div class="col-sm-3">
                            <select class="form-control" id="select2-search-project"></select>
                        </div>
                        <div class="col-sm-2">
                            <select id="select2-meter-type" class="form-control" v-model="meter_type">
                                <option v-for="item in meter_types" :value="item.id">{{ item.name }}</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input id="month" type="text" autocomplete="off"
                                   class="form-control" placeholder="月份"
                                   v-model="month">
                        </div>

                    </div>

                    <div class="box-tools">
                        <form onsubmit="return false" enctype="multipart/form-data">
                            <a :href="'/admin/water-and-power-bill/download-file/' + project_id"  class="btn btn-default btn-sm" v-if="project_id != '' && !rooms.length" target="_blank">下载{{ meter_type == 'ELECTRICITY_FEE' ? '电费' : '水费' }}模版</a>
                            <span class="btn btn-default btn-sm" v-on:click="uploadFileClick">
                                导入{{ meter_type == 'ELECTRICITY_FEE' ? '电费' : '水费' }}
                            </span>
                            <input id="upload-file" type="file" style="display: none">
                        </form>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <table class="table">
                        <tbody v-if="rooms.length">
                            <tr>
                                <th>房间号</th>
                                <th>项目</th>
                                <th>月份</th>
                                <th>上次读数（度）</th>
                                <th>本次读数（度）</th>
                                <th>本月用量</th>
                                <th>操作</th>
                            </tr>
                            <tr v-for="room in rooms">
                                <td>{{ room.name }}</td>
                                <td>{{ meter_type == 'ELECTRICITY_FEE' ? '电费' : '水费' }}</td>
                                <td>{{ month }}</td>
                                <td>{{ room.last_time_reading }}</td>
                                <td>{{ room.this_time_reading }}</td>
                                <td><span class="label label-success">{{ room.monthly_usage  }}</span></td>
                                <td><a href="##">抄表历史</a></td>
                            </tr>
                        </tbody>
                        <tbody v-else-if="adds.length">
                            <tr>
                                <th>房间号</th>
                                <th>项目</th>
                                <th>月份</th>
                                <th>上次读数（度）</th>
                                <th>本次读数（度）</th>
                                <th>本月用量</th>
                            </tr>
                            <tr v-for="(add, index) in adds">
                                <td>{{ add.name }}</td>
                                <td>{{ meter_type == 'ELECTRICITY_FEE' ? '电费' : '水费' }}</td>
                                <td>{{ month }}</td>
                                <td>{{ add.last_time_reading }}</td>
                                <td><input type="text" class="currency" @keyup="changeThisTimeReading(index, this.event.target.value);" v-model="adds[index]['this_time_reading']"></td>
                                <td><span class="label" :class="parseFloat(add.monthly_usage) > 0 ? 'label-success' : 'label-danger'">{{ add.monthly_usage }}</span></td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <div style="min-height: 200px; line-height: 200px; font-size: 40px; opacity: .1" class="text-center">
                                <span v-if="loading" class="fa fa-spinner fa-spin"></span>
                                <span v-else-if="project_id == ''">请选择房源项目</span>
                                <span v-else>{{ month.substr(4, 2) }}月份没有对应的{{ meter_type == 'ELECTRICITY_FEE' ? '电费' : '水费' }}记录</span>
                            </div>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->

                <div class="box-footer" v-if="adds.length">
                    <button class="btn btn-info pull-right" v-on:click="saveAdds">保存</button>
                    <div class="col-sm-2 pull-right">
                        <input id="payable-time" type="text" autocomplete="off"
                               class="form-control" placeholder="应付时间"
                               v-model="payable_time">
                    </div>
                </div>

            </div>
        </div>
    </div>

</template>

<script>
    import Common from '../util'

    export default {
        data() {
            return {
                meter_types: [
                    {id: 'ELECTRICITY_FEE', name: '电费'},
                    {id: 'WATER_FEE', name: '水费'},
                ],
                meter_type:'ELECTRICITY_FEE',
                rooms:[],
                project_id:'',
                month:Date.today().toString('yyyyMM'),
                loading:false,
                adds:[],
                payable_time:'',
            }
        },

        watch: {
            adds: function () {
                this.$nextTick(function () {
                    this.setPayableTime();
                    $('.currency').inputmask({
                        "alias": "currency",
                        "groupSeparator": "",
                        "radixPoint": ".",
                        "prefix": "",
                        "removeMaskOnSubmit": true
                    });
                })
            }
        },

        mounted() {
            this.meterType();
            this.project();     //搜索房源项目
            this.setMonth();
            this.uploadFile();
        },

        methods: {
            //设置月份
            setMonth() {
                $('#month').datetimepicker({
                    'format': 'YYYYMM',
                    'locale': 'zh-CN',
                    'allowInputToggle': true
                }).on('dp.change', (e) => {
                    this.adds = [];
                    this.month = e.currentTarget.value;
                    this.searchRooms()
                });
            },

            setPayableTime(){
                $('#payable-time').datetimepicker({
                    'format': 'YYYY-MM-DD',
                    'locale': 'zh-CN',
                    'allowInputToggle': true
                }).on('dp.change', (e) => {
                    this.payable_time = e.currentTarget.value;
                });
            },

            meterType(){
                $('#select2-meter-type').select2().on("change", () => {
                    let meter_type = $("#select2-meter-type").val()
                    this.meter_type = meter_type ? meter_type : this.meter_types[0].id;
                    this.adds = [];
                    this.searchRooms()
                });
            },

            project() {
                Common.select([], "#select2-search-project", "/admin/search-project", "name", "address", true, '请选择房源项目', 1, 'zh-CN',
                    function (repo) {
                        if (repo.loading) return '搜索中...';
                        let html = "<div class='container-fluid'>" +
                            "<div class='row'>" +
                            "<div class='col-sm-12'>房源项目：" + repo['text'] + "</div>" +
                            "<div class='col-sm-12'>房源地址：" + repo['address'] + "</div>" +
                            "<div class='col-sm-12'>房源类型：" + repo['type'] + "</div>" +
                            "</div>" +
                            "</div>";

                        return html;
                    },
                    function (repo) {
                        if (repo['name']) {
                            return repo['name'] + '：' + repo['address'];
                        }

                        return repo.text;
                    }
                );

                $("#select2-search-project").on("change", () => {
                    this.rooms = [];
                    this.adds = [];
                    let project_id = $("#select2-search-project").val()
                    this.project_id = project_id ? project_id : '';
                    this.searchRooms()
                });
            },

            uploadFileClick(){
                if(this.project_id == ''){
                    toastr.success('请先选择房源项目');
                }else{
                    $('#upload-file').click();
                }
            },

            changeThisTimeReading(index, value){
                this.adds[index]['this_time_reading'] = value;
                this.adds[index]['monthly_usage'] =this.accSub(value, this.adds[index]['last_time_reading']);
            },

            accSub(arg1, arg2) {
                let r1, r2, m, n;
                try { r1 = arg1.toString().split(".")[1].length } catch (e) { r1 = 0 }
                try { r2 = arg2.toString().split(".")[1].length } catch (e) { r2 = 0 }
                m = Math.pow(10, Math.max(r1, r2));
                n = (r1 >= r2) ? r1 : r2;
                return ((arg1 * m - arg2 * m) / m).toFixed(n);
            },

            uploadFile(){
                $('#upload-file').on('change', () => {
                    $('#upload-file').closest('form').submit();
                });
                $('form').submit(() => {
                    let form_date = new FormData();
                    form_date.append('file',$('#upload-file')[0].files[0]);
                    form_date.append('project_id', this.project_id);
                    form_date.append('meter_type', this.meter_type);
                    form_date.append('month', this.month);
                    this.loading = true;
                    axios({
                        method: 'post',
                        url: '/admin/water-and-power-bill/import-file',
                        data: form_date
                    }).then(response => {
                        this.loading = false;
                        this.$nextTick(() => {
                            this.adds = response.data.data;
                            $('#upload-file').val('')
                        });
                        console.log(response.data.data);
                    }).catch(error => {
                        this.loading = false;
                        $('#upload-file').val('');
                        toastr.success(error.response.data.message);
                    });
                });
            },

            searchRooms() {
                if (this.project_id) {
                    this.loading = true;
                    axios.post("/admin/search-room-meter", {
                        project_id:this.project_id,
                        meter_type:this.meter_type,
                        month:this.month,
                    }).then(response => {
                        this.loading = false;
                        this.rooms = response.data;
                        // if (!!!this.rooms.length) {
                        //     swal({
                        //         title: '该房源项目未添加房间号',
                        //         timer: 3600,
                        //         type: 'info'
                        //     });
                        // }
                    }).catch(error => {
                        this.loading = false;
                        console.log(error.response.data);
                    });
                } else {
                    // this.form_data.room_id = ''
                    // this.rooms = []
                    // $("#select2-search-room").prop("disabled", true);
                }
            },

            //导入水电费
            saveAdds(){
                if(this.adds.length){
                    this.loading = true;
                    axios.post("/admin/water-and-power-bill/save-import-file", {
                        adds:this.adds,
                        month:this.month,
                        project_id:this.project_id,
                        payable_time:this.payable_time,
                        meter_type:this.meter_type,
                    }).then(response => {
                        this.loading = false;
                        this.payable_time = ''
                        this.adds = [];
                        toastr.success('导入成功');
                        this.searchRooms();
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
