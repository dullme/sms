<template>
    <div style="padding: 10px; min-width: 600px">
        <div>
            <span class="ka_cao_example failed">未识别</span>
            <span class="ka_cao_example success">卡正常</span>
            <span class="ka_cao_example wrong">卡错误</span>
            <span class="ka_cao_example empty">无卡</span>
            <span v-if="can_send_time" style="line-height: 40px; margin-left: 20px">
                <span v-if="can_send == false">下一次请求时间：{{ can_send_time }}</span>
                <span v-if="can_send == true">当前可以请求</span>
                请求频率：{{ frequency / 1000 }}秒/次
            </span>
            <span class="clearfix"></span>
        </div>

        <div v-if="real_device.length" v-for="value in real_device">
            <span>当前设备:{{ value.ip }}</span>
            <span>状态:通讯正常</span>
            <span>当日收益:{{ value.income }}</span>
            <span>当日成功条数:{{ value.success }}</span>
            <span>当日失败条数:{{ value.fail }}</span>
            <div v-for="status in value.status">
                <div v-for="row in status">
                    <div v-for="t in row">
                        <a v-if="t.has_card && t.status == 'failed'" href="##" class="ka_cao" :class="t.status" v-on:click="switchCard(value.ip, t.port, t.has_card, t.status)">{{ t.port }}</a>
                        <span v-else class="ka_cao" :class="t.status">{{ t.port }}</span>
                    </div>

                </div>

            </div>
        </div>

        <div class="text-center" v-else v-text="read_card_status == true ? '未找到设备':''"
             style="min-height: 200px; line-height: 200px"></div>

        <div class="text-center">
            <span v-if="read_card_status == false">
                <i>搜索中({{ this.time }})</i>
                <i v-text="message"></i>
                <a href="##" v-on:click="scanningIp">重新搜索</a>
            </span>
            <div v-else>
                <a class="btn btn-lg btn-default" :class="open == 'SENDING' ? 'disabled' : ''"
                   style="width: 160px;background-color: white; font-weight: bolder; border: 2px solid #BBBBBB"
                   v-on:click="scanningIp">重新扫描设备</a>
                <a class="btn btn-lg btn-default"
                   style="width: 160px;background-color: white; font-weight: bolder; border: 2px solid #BBBBBB"
                   v-on:click="start" v-text="open == 'STOPPED' ? '启动':'停止' "></a>
                <div style="width: 40px;display: inline-block"><img v-if="open == 'SENDING'" src="/images/loading.svg"
                                                                    width="100%" height="100%"></div>

            </div>
        </div>

    </div>
</template>

<script>
    require('../../../public/vendor/datejs/date-zh-CN')
    var _this;

    export default {
        data() {
            return {
                device: [],
                real_device: [],
                ips: [],
                read_ip_finished: false, //ip是否读取完毕
                read_card_status: false, //卡槽是否读取完毕
                open: 'STOPPED', //是否开启自动发送短信
                time: 0, //搜索设备等待秒数
                scanning_ip_interval: '', //扫描IP事件
                send_interval: '', //发送短信事件
                message: '', //提示信息
                frequency: 1000, //请求频率/毫秒
                can_send: false, //是否可以发送短信
                can_send_time: '', //什么时候可以发短信
            }
        },

        watch: {
            read_ip_finished: (current) => {
                if (current) {
                    console.log('IP读取完毕');
                    _this.readCard(0);
                }
            },

            read_card_status: (current) => {
                if (current) {
                    console.log('卡槽读取完毕');
                    _this.makeCard();;
                }
            },
        },

        created() {
            _this = this;
            this.scanningIp();
        },
        mounted() {

        },

        methods: {
            //扫描IP
            scanningIp() {
                if (this.open == 'SENDING') {
                    console.log('启动中无法搜索新设备')
                } else {
                    clearInterval(this.scanning_ip_interval);
                    this.device = [],
                    this.real_device = [],
                    this.ips = [],
                    this.read_ip_finished = false, //ip是否读取完毕
                    this.read_card_status = false, //卡槽是否读取完毕
                    this.open = 'STOPPED', //是否开启自动发送短信
                    this.time = 0, //搜索设备等待秒数
                    this.scanning_ip_interval = '', //扫描IP事件
                    this.message = '', //提示信息
                    this.frequency = 1000; //请求频率/毫秒
                    this.can_send = false; //是否可以发送短信
                    this.can_send_time = ''; //什么时候可以发短信
                    console.log('开始扫描设备');
                    this.scanning_ip_interval = setInterval(() => {
                        this.time += 1
                        if (this.time >= 20) {
                            this.message = '...设备扫描时间过长请重新扫描...'
                        }
                    }, 1000);
                    this.getIps()
                }
            },

            //读IP
            getIps() {
                this.ips = JSON.parse('{"IPS": ["192.168.1.111","192.168.1.111"]}').IPS;
                this.read_ip_finished = true    //完成IP的读取

                // AsyncIPS.getUsefullIPs('80', (json) => {
                //     clearInterval(this.scanning_ip_interval);
                //     this.ips = JSON.parse(json).IPS;
                //     this.read_ip_finished = true    //完成IP的读取
                // }, (message) => {
                //     console.log(message)
                // });
            },

            //读卡
            readCard(index) {
                AsyncHttp.httpRequest(
                    "http://" + this.ips[index] + "/goip_get_status.html?username=root&password=root&all_sims=1",
                    "get",
                    "",
                    (json) => {
                        this.device[index] = {};
                        this.device[index] = JSON.parse(json)
                        if (index < this.ips.length - 1) {
                            this.readCard(index + 1)
                        } else {
                            this.read_card_status = true;
                        }
                    },
                    (messsage) => {
                        console.log(messsage)
                    }
                );
            },

            //处理卡数据
            makeCard() {
                axios.post("/user/make-card", {
                    device: this.device,
                    send:this.open
                }).then(response => {
                    this.can_send = response.data.data.can_send;
                    this.can_send_time = response.data.data.can_send_time;
                    this.frequency = response.data.data.frequency;
                    this.real_device = response.data.data.real_device;
                }).catch(error => {
                    console.log(error.response.data.message)
                });
            },

            //开始自动发短信
            start() {
                if (this.open == 'STOPPED') {
                    console.log('自动发短信中......');
                    this.open = 'SENDING'
                    if (this.real_device.length) {
                        this.send_interval = setInterval(() => {
                            this.read_card_status = false;
                            this.readCard(0);
                        }, this.frequency)
                    }
                } else {
                    clearInterval(this.send_interval);
                    this.open = 'STOPPED';
                    console.log('已停止自动发短信');
                }
            },

            //切卡
            switchCard(ip, port){
                let data = '{"version":"1.1","type":"command","op":"switch","ports":"' + port + '"}';
                AsyncHttp.httpRequest(
                    "http://" + ip + "/goip_send_cmd.html?username=root&password=root",
                    "POST",
                    data,
                    (json) => {
                        alert('切卡成功');
                    },
                    (messsage) => {
                        console.log(messsage)
                    }
                );
            },

        }

    }
</script>
<style>
    .ka_cao_example {
        border: 1px solid #dee2e6;
        width: 80px;
        height: 40px;
        text-align: center;
        line-height: 39px;
        float: left;
    }

    .ka_cao {
        float: left;
        border: 1px solid #dee2e6;
        width: 6.25%;
        height: 6.25%;
        text-align: center;
        padding: 0.5rem;
    }

    .failed:hover{
        color: white;
    }

    .empty {
        background-color: #c4c4c4;
        color: black;
    }

    .success {
        background-color: #38c172;
        color: white;
    }

    .unknown {
        background-color: #29c107;
        color: white;
    }

    .failed {
        background-color: #e3342f;
        color: white;
    }

    .wrong {
        background-color: #6cb2eb;
        color: white;
    }
</style>
