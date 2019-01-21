<template>
    <div style="padding: 10px; min-width: 600px">
        <div v-if="real_device.length" v-for="(item, d_index) in real_device">
            <span>当前设备:{{ item.ip }}</span>
            <span>状态:通讯正常</span>
            <span>当日收益:</span>
            <span>当日成功条数:</span>
            <span>当日失败条数:</span>
            <div v-for="(s,s_index) in item.status">
                <div class="ka_cao" :class="t.status" :id="d_index + '-' + s_index + '-' + t_index" v-for="(t, t_index) in s">{{ t.port }}</div>
            </div>
        </div>
        <div class="text-center" v-else v-text="loading == false ? '未找到设备':''" style="min-height: 200px; line-height: 200px"></div>
        <div class="text-center">
            <span v-if="loading == true">
                <i>搜索中({{ this.time }})</i>
                <i v-text="message"></i>
                <a href="##" v-on:click="search">重新搜索</a>
            </span>
            <div v-else>
                <a class="btn btn-lg btn-default" style="width: 160px;background-color: white; font-weight: bolder; border: 2px solid #BBBBBB" v-on:click="search">搜索新设备</a>
                <a class="btn btn-lg btn-default" style="width: 160px;background-color: white; font-weight: bolder; border: 2px solid #BBBBBB" v-on:click="start" v-text="open == 'STOPPED' ? '启动':'停止' "></a>
                <div style="width: 40px;display: inline-block"><img v-if="open == 'SENDING'" src="/images/loading.svg" width="100%" height="100%"></div>

            </div>
        </div>
    </div>
</template>

<script>
    require('../../../public/vendor/datejs/date-zh-CN')

    export default {
        data() {
            return {
                device:[],  //读取的数据
                real_device:[], //真实的数据,
                ip:[],  // 所有设备的IP
                loading:false,  //是否在读取设备
                time:0, //搜索设备等待秒数
                search_interval:'', //搜索事件
                send_interval:'', //请求事件
                message:'', //提示信息
                open:'STOPPED', //是否开启发送短信
                frequency:1000, //请求频率/毫秒
                data:[],
            }
        },

        created() {
            this.search();
        },

        mounted() {

        },

        methods: {
            search(){
                if(this.open == 'SENDING'){
                    console.log('启动中无法搜索新设备')
                }else{
                    clearInterval(this.search_interval);
                    this.device = [];
                    this.real_device = [];
                    this.ip = [];
                    this.loading = true;
                    this.time = 0;
                    this.search_interval = '';
                    this.message = '';
                    this.open = 'STOPPED';
                    this.frequency = 1000;
                    this.data = [];

                    this.search_interval = setInterval(()=>{
                        this.time +=1
                        if(this.time >=20){
                            this.message = '...搜索时间过长请重新搜索...'
                        }
                    },1000)

                    AsyncIPS.getUsefullIPs('80', (json)=>{
                        this.loading = false;
                        this.ip = JSON.parse(json).IPS
                        axios.post("/user/device", {
                            ip:this.ip,
                        }).then(response => {
                            this.frequency = response.data.data.frequency;
                            this.device = response.data.data.device;
                            this.readCard();
                            this.getRealStatus(this.device);
                        }).catch(error => {
                            console.log(error.response.data)
                        });
                    }, (message)=>{
                        this.loading = false;
                        console.log(message)
                    });
                }
            },

            start(){
                if(this.open == 'STOPPED'){
                    console.log('发送中......');
                    this.data = [];
                    this.open = 'SENDING'
                        this.real_device.forEach((device, d_index) =>{
                            device.status.forEach((ports, p_ports)=>{
                                ports.forEach((value, v_index)=>{
                                    if(value.has_card && value.status == 'waiting'){
                                        this.data.push({
                                            path:d_index + '|' + p_ports + '|' + v_index,    //路径
                                            iccid:value.iccid,
                                            imei:value.imei,
                                            _token:document.head.querySelector('meta[name="csrf-token"]').content
                                        });
                                    }
                                })
                            })

                        })

                    if(this.data.length){
                        this.send_interval = setInterval(()=>{
                            axios.post("/user/send/message", this.data).then(response => {

                                console.log(response.data)
                            }).catch(error => {
                                console.log(error)
                            });
                        },this.frequency)
                    }

                }else{
                    clearInterval(this.send_interval);
                    this.open = 'STOPPED';
                    console.log('已停止发送');
                }
            },


            readCard(){

                this.ip.forEach((value, index)=>{
                    AsyncHttp.httpRequest(
                        "http://"+r[0]+"/goip_get_status.html?username=root&password=root&all_sims=1",
                        "get",
                        "",
                        (json)=>{
                            this.device.push(JSON.parse(json));
                        },
                        (messsage)=>{
                            console.log(messsage)
                        });
                })

            },

            prefixInteger(num, n) {
                return (Array(n).join(0) + num).slice(-n);
            },

            //根据状态获取全部卡槽
            getRealStatus(device){
                let status = new Array();

                device.forEach((value, index) => {
                    for(let i=0; i< value['max-ports'];i++){
                        status[i]=new Array(i);
                        for(let j=0; j< value['max-slot'];j++){
                            let port = (i+1) +'.'+ this.prefixInteger((j+1), 2)

                            try {
                                device[index]['status'].forEach(function (value) {
                                    if(value['port'] == port){
                                        status[i][j] = {
                                            'count':0,
                                            'port':value['port'],
                                            'imei':value['imei'],
                                            'iccid':value['iccid'],
                                            'imsi':value['imsi'],
                                            'has_card':true,
                                            'status':'waiting', //waiting:等待中;executing执行中
                                        }
                                        throw new Error('该卡已绑定')
                                    }else{
                                        status[i][j] = {
                                            'count':0,
                                            'port':port,
                                            'imei':'',
                                            'iccid':'',
                                            'imsi':'',
                                            'has_card':false,
                                            'status':'closed', //closed:已关闭;
                                        }
                                    }
                                })
                            }catch (e) {

                            }
                        }
                    }

                    this.real_device.push({
                        'ip' : value['ip'],
                        'mac' : value['mac'],
                        'status' : status,
                    });
                })
            }

        }

    }

</script>
<style>
    .ka_cao{
        float: left;
        border: 1px solid #dee2e6;
        width: 6.25%;
        height: 6.25%;
        text-align: center;
        padding: 0.5rem;
    }

    .closed{
        color:black;
    }

    .waiting{
        background-color: #38c172;
        color:white;
    }

    .executing{
        background-color: #c110b3;
        color:white;
    }

    .current{
        background-color: #1b72c1;
        color:white;
    }
</style>
