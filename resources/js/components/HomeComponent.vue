<template>
    <div style="padding: 10px; min-width: 600px">
        <div v-if="real_device.length" v-for="item in real_device">
            <span>当前设备:{{ item.ip }}</span>
            <span>状态:通讯正常</span>
            <span>当日收益:</span>
            <span>当日成功条数:</span>
            <span>当日失败条数:</span>
            <div v-for="s in item.status">
                <div class="ka_cao" :class="t.has_card ? 'bg-success':''" v-for="t in s">{{ t.port }}</div>
            </div>
        </div>
        <div class="text-center" v-else v-text="loading == false ? '未找到设备':''" style="min-height: 200px; line-height: 200px"></div>
        <div class="text-center">
            <span v-if="loading == true">
                <i>搜索中({{ this.time }})</i>
                <i v-text="emsg"></i>
                <a href="##" v-on:click="search">重新搜索</a>
            </span>
            <div v-else>
                <a class="btn btn-lg btn-default" style="width: 160px;background-color: white; font-weight: bolder; border: 2px solid #BBBBBB" v-on:click="search">搜索新设备</a>
                <a class="btn btn-lg btn-default" :class="open == true ? 'text-danger':''" style="width: 160px;background-color: white; font-weight: bolder; border: 2px solid #BBBBBB" v-on:click="start" v-text="open == 'STOPED' ? '启动':'停止' "></a>
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
                device:[],
                start_name:'',
                end_name:'',
                amount:'',
                ip:[],
                loading:false,
                time:0,
                interval:'',
                emsg:'',
                open:'STOPED',
                frequency:1,
                send_interval:1,
                real_device:[], //真实的数据
            }
        },

        created() {
            this.search();
        },

        mounted() {

        },

        methods: {
            search(){
                clearInterval(this.interval);
                this.loading = true;
                this.time = 0;
                this.emsg = '';
                this.device = [];
                this.real_device = [];

                this.interval = setInterval(()=>{
                    this.time +=1
                    if(this.time >=20){
                    this.emsg = '...搜索时间过长请重新搜索...'
                    }
                },1000)


                this.loading = false;
                this.ip = JSON.parse('{"IPS": ["192.168.1.67","192.168.1.67"]}').IPS
                this.readCard();
                this.getRealStatus(this.device);



                // axios.post("/user/device", {
                //     ip:this.ip,
                // }).then(response => {
                //     this.frequency = response.data.data.frequency;
                //     this.device = response.data.data.device;
                // }).catch(error => {
                //     console.log(error.response.data)
                // });

                // AsyncIPS.getUsefullIPs('80', (json)=>{
                //     this.loading = false;
                //     this.ip = JSON.parse(json).IPS
                //     axios.post("/user/device", {
                //         ip:this.ip,
                //     }).then(response => {
                //         this.frequency = response.data.data.frequency;
                //         this.device = response.data.data.device;
                //     }).catch(error => {
                //         console.log(error.response.data)
                //     });
                // }, (message)=>{
                //     this.loading = false;
                //     console.log(message)
                // });
            },

            start(){
                if(this.open == 'STOPED'){
                    console.log('发送中......');
                    this.open = 'SENDING'



                    this.send_interval = setInterval(()=>{
                        console.log(this.ip);
                    }, Number(this.frequency))

                }else{
                    clearInterval(this.send_interval);
                    console.log('已停止发送');
                    this.open = 'STOPED';
                }
            },

            readCard(){

                this.ip.forEach((value)=>{
                    let r = value.split("|");
                    this.device.push(JSON.parse('{\n' +
                        '    "type": "dev-status",\n' +
                        '    "seq": 187,\n' +
                        '    "expires": -1,\n' +
                        '    "mac": "00-30-f1-00-aa-c1",\n' +
                        '    "ip": "192.168.1.67",\n' +
                        '    "ver": "616-520-840-641-100-020",\n' +
                        '    "max-ports": 8,\n' +
                        '    "max-slot": 32,\n' +
                        '    "status": [\n' +
                        '        {\n' +
                        '            "port": "1.01",\n' +
                        '            "seq": 1062,\n' +
                        '            "st": 6,\n' +
                        '            "imei": "866157032963614",\n' +
                        '            "iccid": "42402117840804569421",\n' +
                        '            "imsi": "001012345678405",\n' +
                        '            "sn": "",\n' +
                        '            "opr": "0 ",\n' +
                        '            "bal": "0.00",\n' +
                        '            "sig": 0,\n' +
                        '            "tot_dur": "0/-1",\n' +
                        '            "mon_dur": "0/-1",\n' +
                        '            "day_dur": "0/-1"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "1.02",\n' +
                        '            "seq": 1063,\n' +
                        '            "st": 6,\n' +
                        '            "imei": "866157032963614",\n' +
                        '            "iccid": "98001122334455667788",\n' +
                        '            "imsi": "001012345678901",\n' +
                        '            "sn": "",\n' +
                        '            "opr": "0 ",\n' +
                        '            "bal": "0.00",\n' +
                        '            "sig": 0,\n' +
                        '            "tot_dur": "0/-1",\n' +
                        '            "mon_dur": "0/-1",\n' +
                        '            "day_dur": "0/-1"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "1.03",\n' +
                        '            "seq": 1064,\n' +
                        '            "st": 6,\n' +
                        '            "imei": "866157032963614",\n' +
                        '            "iccid": "98001122334455667788",\n' +
                        '            "imsi": "001012345678901",\n' +
                        '            "sn": "",\n' +
                        '            "opr": "0 ",\n' +
                        '            "bal": "0.00",\n' +
                        '            "sig": 0,\n' +
                        '            "tot_dur": "0/-1",\n' +
                        '            "mon_dur": "0/-1",\n' +
                        '            "day_dur": "0/-1"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "1.04",\n' +
                        '            "seq": 1065,\n' +
                        '            "st": 6,\n' +
                        '            "imei": "866157032963614",\n' +
                        '            "iccid": "98001122334455667788",\n' +
                        '            "imsi": "001012345678901",\n' +
                        '            "sn": "",\n' +
                        '            "opr": "0 ",\n' +
                        '            "bal": "0.00",\n' +
                        '            "sig": 0,\n' +
                        '            "tot_dur": "0/-1",\n' +
                        '            "mon_dur": "0/-1",\n' +
                        '            "day_dur": "0/-1"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "1.05",\n' +
                        '            "seq": 1066,\n' +
                        '            "st": 6,\n' +
                        '            "imei": "866157032963614",\n' +
                        '            "iccid": "98001122334455667788",\n' +
                        '            "imsi": "001012345678901",\n' +
                        '            "sn": "",\n' +
                        '            "opr": "0 ",\n' +
                        '            "bal": "0.00",\n' +
                        '            "sig": 0,\n' +
                        '            "tot_dur": "0/-1",\n' +
                        '            "mon_dur": "0/-1",\n' +
                        '            "day_dur": "0/-1"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "1.06",\n' +
                        '            "seq": 1067,\n' +
                        '            "st": 6,\n' +
                        '            "imei": "866157032963614",\n' +
                        '            "iccid": "98001122334455667788",\n' +
                        '            "imsi": "001012345678901",\n' +
                        '            "sn": "",\n' +
                        '            "opr": "0 ",\n' +
                        '            "bal": "0.00",\n' +
                        '            "sig": 0,\n' +
                        '            "tot_dur": "0/-1",\n' +
                        '            "mon_dur": "0/-1",\n' +
                        '            "day_dur": "0/-1"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "1.07",\n' +
                        '            "seq": 1068,\n' +
                        '            "st": 6,\n' +
                        '            "imei": "866157032963614",\n' +
                        '            "iccid": "98001122334455667788",\n' +
                        '            "imsi": "001012345678901",\n' +
                        '            "sn": "",\n' +
                        '            "opr": "0 ",\n' +
                        '            "bal": "0.00",\n' +
                        '            "sig": 0,\n' +
                        '            "tot_dur": "0/-1",\n' +
                        '            "mon_dur": "0/-1",\n' +
                        '            "day_dur": "0/-1"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "1.08",\n' +
                        '            "seq": 1069,\n' +
                        '            "st": 6,\n' +
                        '            "imei": "866157032963614",\n' +
                        '            "iccid": "98001122334455667788",\n' +
                        '            "imsi": "001012345678901",\n' +
                        '            "sn": "",\n' +
                        '            "opr": "0 ",\n' +
                        '            "bal": "0.00",\n' +
                        '            "sig": 0,\n' +
                        '            "tot_dur": "0/-1",\n' +
                        '            "mon_dur": "0/-1",\n' +
                        '            "day_dur": "0/-1"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "2.04",\n' +
                        '            "seq": 187,\n' +
                        '            "st": 0,\n' +
                        '            "imei": "866157032949548"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "3.01",\n' +
                        '            "seq": 187,\n' +
                        '            "st": 0,\n' +
                        '            "imei": "866157032841679"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "4.01",\n' +
                        '            "seq": 187,\n' +
                        '            "st": 0,\n' +
                        '            "imei": "866157032843212"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "5.01",\n' +
                        '            "seq": 187,\n' +
                        '            "st": 0,\n' +
                        '            "imei": "866157032952583"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "6A",\n' +
                        '            "seq": 187,\n' +
                        '            "st": 0,\n' +
                        '            "imei": "866157032944838"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "7A",\n' +
                        '            "seq": 187,\n' +
                        '            "st": 0,\n' +
                        '            "imei": "866157032958713"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "8A",\n' +
                        '            "seq": 187,\n' +
                        '            "st": 0,\n' +
                        '            "imei": "866157032928997"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "9A",\n' +
                        '            "seq": 187,\n' +
                        '            "st": 0,\n' +
                        '            "imei": "866157032977069"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "10A",\n' +
                        '            "seq": 187,\n' +
                        '            "st": 0,\n' +
                        '            "imei": "866157032991359"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "11A",\n' +
                        '            "seq": 311,\n' +
                        '            "st": 0,\n' +
                        '            "imei": "866157032670136"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "12A",\n' +
                        '            "seq": 187,\n' +
                        '            "st": 0,\n' +
                        '            "imei": "866157032668262"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "13A",\n' +
                        '            "seq": 187,\n' +
                        '            "st": 0,\n' +
                        '            "imei": "866157032977168"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "14A",\n' +
                        '            "seq": 187,\n' +
                        '            "st": 0,\n' +
                        '            "imei": "866157032683295"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "15G",\n' +
                        '            "seq": 187,\n' +
                        '            "st": 0,\n' +
                        '            "imei": "866157032677222"\n' +
                        '        },\n' +
                        '        {\n' +
                        '            "port": "16A",\n' +
                        '            "seq": 187,\n' +
                        '            "st": 0,\n' +
                        '            "imei": "866157032977416"\n' +
                        '        }\n' +
                        '    ]\n' +
                        '}'));

                    // AsyncHttp.httpRequest(
                    //     "http://"+r[0]+"/goip_get_status.html?username=root&password=root&all_sims=1",
                    //     "get",
                    //     "",
                    //     (json)=>{
                    //         this.device.push(JSON.parse('{\n' +
                    //             '    "type": "dev-status",\n' +
                    //             '    "seq": 187,\n' +
                    //             '    "expires": -1,\n' +
                    //             '    "mac": "00-30-f1-00-aa-c1",\n' +
                    //             '    "ip": "192.168.1.67",\n' +
                    //             '    "ver": "616-520-840-641-100-020",\n' +
                    //             '    "max-ports": 8,\n' +
                    //             '    "max-slot": 32,\n' +
                    //             '    "status": [\n' +
                    //             '        {\n' +
                    //             '            "port": "1.01",\n' +
                    //             '            "seq": 1062,\n' +
                    //             '            "st": 6,\n' +
                    //             '            "imei": "866157032963614",\n' +
                    //             '            "iccid": "42402117840804569421",\n' +
                    //             '            "imsi": "001012345678405",\n' +
                    //             '            "sn": "",\n' +
                    //             '            "opr": "0 ",\n' +
                    //             '            "bal": "0.00",\n' +
                    //             '            "sig": 0,\n' +
                    //             '            "tot_dur": "0/-1",\n' +
                    //             '            "mon_dur": "0/-1",\n' +
                    //             '            "day_dur": "0/-1"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "1.02",\n' +
                    //             '            "seq": 1063,\n' +
                    //             '            "st": 6,\n' +
                    //             '            "imei": "866157032963614",\n' +
                    //             '            "iccid": "98001122334455667788",\n' +
                    //             '            "imsi": "001012345678901",\n' +
                    //             '            "sn": "",\n' +
                    //             '            "opr": "0 ",\n' +
                    //             '            "bal": "0.00",\n' +
                    //             '            "sig": 0,\n' +
                    //             '            "tot_dur": "0/-1",\n' +
                    //             '            "mon_dur": "0/-1",\n' +
                    //             '            "day_dur": "0/-1"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "1.03",\n' +
                    //             '            "seq": 1064,\n' +
                    //             '            "st": 6,\n' +
                    //             '            "imei": "866157032963614",\n' +
                    //             '            "iccid": "98001122334455667788",\n' +
                    //             '            "imsi": "001012345678901",\n' +
                    //             '            "sn": "",\n' +
                    //             '            "opr": "0 ",\n' +
                    //             '            "bal": "0.00",\n' +
                    //             '            "sig": 0,\n' +
                    //             '            "tot_dur": "0/-1",\n' +
                    //             '            "mon_dur": "0/-1",\n' +
                    //             '            "day_dur": "0/-1"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "1.04",\n' +
                    //             '            "seq": 1065,\n' +
                    //             '            "st": 6,\n' +
                    //             '            "imei": "866157032963614",\n' +
                    //             '            "iccid": "98001122334455667788",\n' +
                    //             '            "imsi": "001012345678901",\n' +
                    //             '            "sn": "",\n' +
                    //             '            "opr": "0 ",\n' +
                    //             '            "bal": "0.00",\n' +
                    //             '            "sig": 0,\n' +
                    //             '            "tot_dur": "0/-1",\n' +
                    //             '            "mon_dur": "0/-1",\n' +
                    //             '            "day_dur": "0/-1"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "1.05",\n' +
                    //             '            "seq": 1066,\n' +
                    //             '            "st": 6,\n' +
                    //             '            "imei": "866157032963614",\n' +
                    //             '            "iccid": "98001122334455667788",\n' +
                    //             '            "imsi": "001012345678901",\n' +
                    //             '            "sn": "",\n' +
                    //             '            "opr": "0 ",\n' +
                    //             '            "bal": "0.00",\n' +
                    //             '            "sig": 0,\n' +
                    //             '            "tot_dur": "0/-1",\n' +
                    //             '            "mon_dur": "0/-1",\n' +
                    //             '            "day_dur": "0/-1"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "1.06",\n' +
                    //             '            "seq": 1067,\n' +
                    //             '            "st": 6,\n' +
                    //             '            "imei": "866157032963614",\n' +
                    //             '            "iccid": "98001122334455667788",\n' +
                    //             '            "imsi": "001012345678901",\n' +
                    //             '            "sn": "",\n' +
                    //             '            "opr": "0 ",\n' +
                    //             '            "bal": "0.00",\n' +
                    //             '            "sig": 0,\n' +
                    //             '            "tot_dur": "0/-1",\n' +
                    //             '            "mon_dur": "0/-1",\n' +
                    //             '            "day_dur": "0/-1"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "1.07",\n' +
                    //             '            "seq": 1068,\n' +
                    //             '            "st": 6,\n' +
                    //             '            "imei": "866157032963614",\n' +
                    //             '            "iccid": "98001122334455667788",\n' +
                    //             '            "imsi": "001012345678901",\n' +
                    //             '            "sn": "",\n' +
                    //             '            "opr": "0 ",\n' +
                    //             '            "bal": "0.00",\n' +
                    //             '            "sig": 0,\n' +
                    //             '            "tot_dur": "0/-1",\n' +
                    //             '            "mon_dur": "0/-1",\n' +
                    //             '            "day_dur": "0/-1"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "1.08",\n' +
                    //             '            "seq": 1069,\n' +
                    //             '            "st": 6,\n' +
                    //             '            "imei": "866157032963614",\n' +
                    //             '            "iccid": "98001122334455667788",\n' +
                    //             '            "imsi": "001012345678901",\n' +
                    //             '            "sn": "",\n' +
                    //             '            "opr": "0 ",\n' +
                    //             '            "bal": "0.00",\n' +
                    //             '            "sig": 0,\n' +
                    //             '            "tot_dur": "0/-1",\n' +
                    //             '            "mon_dur": "0/-1",\n' +
                    //             '            "day_dur": "0/-1"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "2.04",\n' +
                    //             '            "seq": 187,\n' +
                    //             '            "st": 0,\n' +
                    //             '            "imei": "866157032949548"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "3.01",\n' +
                    //             '            "seq": 187,\n' +
                    //             '            "st": 0,\n' +
                    //             '            "imei": "866157032841679"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "4.01",\n' +
                    //             '            "seq": 187,\n' +
                    //             '            "st": 0,\n' +
                    //             '            "imei": "866157032843212"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "5.01",\n' +
                    //             '            "seq": 187,\n' +
                    //             '            "st": 0,\n' +
                    //             '            "imei": "866157032952583"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "6A",\n' +
                    //             '            "seq": 187,\n' +
                    //             '            "st": 0,\n' +
                    //             '            "imei": "866157032944838"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "7A",\n' +
                    //             '            "seq": 187,\n' +
                    //             '            "st": 0,\n' +
                    //             '            "imei": "866157032958713"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "8A",\n' +
                    //             '            "seq": 187,\n' +
                    //             '            "st": 0,\n' +
                    //             '            "imei": "866157032928997"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "9A",\n' +
                    //             '            "seq": 187,\n' +
                    //             '            "st": 0,\n' +
                    //             '            "imei": "866157032977069"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "10A",\n' +
                    //             '            "seq": 187,\n' +
                    //             '            "st": 0,\n' +
                    //             '            "imei": "866157032991359"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "11A",\n' +
                    //             '            "seq": 311,\n' +
                    //             '            "st": 0,\n' +
                    //             '            "imei": "866157032670136"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "12A",\n' +
                    //             '            "seq": 187,\n' +
                    //             '            "st": 0,\n' +
                    //             '            "imei": "866157032668262"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "13A",\n' +
                    //             '            "seq": 187,\n' +
                    //             '            "st": 0,\n' +
                    //             '            "imei": "866157032977168"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "14A",\n' +
                    //             '            "seq": 187,\n' +
                    //             '            "st": 0,\n' +
                    //             '            "imei": "866157032683295"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "15G",\n' +
                    //             '            "seq": 187,\n' +
                    //             '            "st": 0,\n' +
                    //             '            "imei": "866157032677222"\n' +
                    //             '        },\n' +
                    //             '        {\n' +
                    //             '            "port": "16A",\n' +
                    //             '            "seq": 187,\n' +
                    //             '            "st": 0,\n' +
                    //             '            "imei": "866157032977416"\n' +
                    //             '        }\n' +
                    //             '    ]\n' +
                    //             '}'));
                    //     },
                    //     (messsage)=>{
                    //         console.log(messsage)
                    //     });
                })

                console.log(this.device);
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
                                            'port':value['port'],
                                            'imei':value['imei'],
                                            'iccid':value['iccid'],
                                            'imsi':value['imsi'],
                                            'has_card':true,
                                        }
                                        throw new Error('该卡已绑定')
                                    }else{
                                        status[i][j] = {
                                            'port':port,
                                            'imei':'',
                                            'iccid':'',
                                            'imsi':'',
                                            'has_card':false,
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

    .bg-fail{
        background-color: #e3342f;
        color:white;
    }

    .bg-success{
        background-color: #38c172;
        color:white;
    }
</style>
