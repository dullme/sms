<template>
    <div style="padding: 10px; min-width: 600px">
        <table class="table table-bordered" style="background-color: white;">
            <thead>
            <tr>
                <th>当前设备</th>
                <th>状态</th>
                <th>当日收益</th>
                <th>当日成功条数</th>
                <th>当日失败条数</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in device">
                <th>{{ item.ip }}</th>
                <td class="text-success">{{ item.status }}</td>
                <td>{{ item.income }}</td>
                <td>{{ item.successful }}</td>
                <td>{{ item.failures }}</td>
            </tr>
            <tr>
                <th>192.168.1.32</th>
                <td>通讯正常</td>
                <td>43</td>
                <td>40</td>
                <td>3</td>
            </tr>
            </tbody>
        </table>
        <span v-text="ip"></span>
        <div class="text-center">
            <span v-if="loading == true">
                <i>搜索中({{ this.time }})</i>
                <a href="##" v-on:click="search">重新搜索</a>
            </span>

            <a v-else class="btn btn-lg btn-default" style="width: 160px;background-color: white; font-weight: bolder; border: 2px solid #BBBBBB" v-on:click="search">搜索新设备</a>
        </div>
    </div>
</template>

<script>
    require('../../../public/vendor/datejs/date-zh-CN')

    export default {
        data() {
            return {
                device:[],
                start_name:'',
                end_name:'',
                amount:'',
                ip:'123',
                loading:false,
                time:0,
                interval:''
            }
        },

        created() {
        },

        mounted() {

        },

        methods: {
            search(){
                clearInterval(this.interval);
                this.loading = true;
                this.time = 0;
                this.interval = setInterval(()=>{
                    this.time +=1
                },1000)
                AsyncIPS.getUsefullIPs('80', (json)=>{
                    this.loading = false;
                    this.ip = json
                }, (message)=>{
                    this.loading = false;
                    console.log(message)
                });
            },

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
