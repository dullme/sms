<template>
    <div style="padding: 10px; min-width: 600px">
        <div style="width: 100%;padding: 20px 0px">

            <!--<div style="width: 80px;height: 40px;background-color: #D7D7D7;text-align: center;line-height: 40px; float: left">颜色表示：</div>-->
            <div class="failed"
                 style="border: 1px solid #dee2e6;width: 80px;height: 40px;text-align: center;line-height: 39px;float: left">
                未识别
            </div>
            <div class="success"
                 style="border: 1px solid #dee2e6;width: 80px;height: 40px;text-align: center;line-height: 39px;float: left">
                卡正常
            </div>
            <div class="wrong"
                 style="border: 1px solid #dee2e6;width: 80px;height: 40px;text-align: center;line-height: 39px;float: left">
                卡错误
            </div>
            <div class="empty"
                 style="border: 1px solid #dee2e6;width: 80px;height: 40px;text-align: center;line-height: 39px;float: left">
                无卡
            </div>
            <div style="clear: both"></div>
        </div>
        <div v-if="real_device.length" v-for="device in real_device">
            <span>当前设备:{{ device.ip }}</span>
            <span>状态:通讯正常</span>
            <span>当日收益:{{ device.income }}</span>
            <span>当日成功条数:{{ device.success }}</span>
            <span>当日失败条数:{{ device.fail }}</span>
            <div v-for="status in device.status">
                <div v-for="row in status">
                    <div class="ka_cao" :class="t.status" v-for="t in row">{{ t.port }}</div>
                </div>

            </div>
        </div>
        <div class="text-center" v-else v-text="loading == false ? '未找到设备':''"
             style="min-height: 200px; line-height: 200px"></div>
        <div class="text-center">
        <span v-if="loading == true">
            <i>搜索中({{ this.time }})</i>
            <i v-text="message"></i>
            <a href="##" v-on:click="search">重新搜索</a>
        </span>
            <div v-else>
                <a class="btn btn-lg btn-default"
                   style="width: 160px;background-color: white; font-weight: bolder; border: 2px solid #BBBBBB"
                   v-on:click="search">搜索新设备</a>
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

    export default {
        data() {
            return {
                device: [],  //读取的数据
                real_device: [], //真实的数据,
                ip: [],  // 所有设备的IP
                loading: false,  //是否在读取设备
                time: 0, //搜索设备等待秒数
                search_interval: '', //搜索事件
                send_interval: '', //请求事件
                message: '', //提示信息
                open: 'STOPPED', //是否开启发送短信
                frequency: 1000, //请求频率/毫秒
            }
        },

        created() {
            this.search();
        },

        mounted() {

        },

        methods: {
            search() {
                if (this.open == 'SENDING') {
                    console.log('启动中无法搜索新设备')
                } else {
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

                    this.search_interval = setInterval(() => {
                        this.time += 1
                        if (this.time >= 20) {
                            this.message = '...搜索时间过长请重新搜索...'
                        }
                    }, 1000);

                    //扫描设备IP
                    AsyncIPS.getUsefullIPs('80', (json) => {
                        this.loading = false;
                        JSON.parse(json).IPS.forEach((value) => {
                            this.ip.push({
                                ip: value,
                                mac: '00-30-f1-00-b7-d5',
                            })
                        });
                        this.readCard();
                    }, (message) => {
                        this.loading = false;
                        console.log(message)
                    });
                }
            },

            start() {
                if (this.open == 'STOPPED') {
                    console.log('发送中......');
                    this.open = 'SENDING'
                    if (this.real_device.length) {
                        this.send_interval = setInterval(() => {
                            axios.post("/user/send/message", {
                                real_device:this.real_device
                            }).then(response => {
                                this.real_device = response.data.data;
                                console.log(response.data)
                            }).catch(error => {
                                console.log(error)
                            });
                        }, this.frequency)
                    }

                } else {
                    clearInterval(this.send_interval);
                    this.open = 'STOPPED';
                    console.log('已停止发送');
                }
            },


            //读取设备
            readCard() {
                this.ip.forEach(value => {
                    AsyncHttp.httpRequest(
                        "http://" + value['ip'] + "/goip_get_status.html?username=root&password=root&all_sims=1",
                        "get",
                        "",
                        (json) => {
                            let device = JSON.parse(json)
                            this.device.push(device);
                            this.makeCard(device);
                        },
                        (messsage) => {
                            console.log(messsage)
                        });
                })

            },

            //构建设备卡槽
            makeCard(device) {
                let status = new Array();
                for (let i = 0; i < device['max-ports']; i++) {
                    status[i] = new Array();
                    status[i][0] = new Array();
                    status[i][1] = new Array();
                    for (let j = 0, k = 0, l = 0; j < device['max-slot']; j++) {
                        let port = (i + 1) + '.' + this.prefixInteger((j + 1), 2)
                        let res = {};
                        try {
                            device['status'].forEach((value) => {
                                let status = 'empty';
                                if (value['port'] == port) {

                                    if (value['st'] == 0) {
                                        status = 'empty';
                                    } else if (value['st'] > 0 && (value['iccid'] == '' || value['imsi'] == '')) {
                                        status = 'failed';
                                        let data = '{"version":"1.1","type":"command","op":"switch","ports":"' + port + '"}';
                                        AsyncHttp.httpRequest(
                                            "http://" + device['ip'] + "/goip_send_cmd.html?username=root&password=root",
                                            "POST",
                                            data,
                                            (json) => {
                                                console.log('json');
                                            },
                                            (messsage) => {
                                                console.log(messsage)
                                            });
                                    } else {
                                        status = 'success';
                                    }

                                    res = {
                                        count: 0,
                                        st: value['st'],
                                        port: value['port'],
                                        imei: value['imei'],
                                        iccid: value['iccid'],
                                        imsi: value['imsi'],
                                        has_card: value['st'] == 0 ? false : true,
                                        status: status, //success:有卡;executing执行中
                                    };
                                    throw new Error('该卡已绑定')
                                } else {
                                    res = {
                                        count: 0,
                                        st: 0,
                                        port: port,
                                        imei: '',
                                        iccid: '',
                                        imsi: '',
                                        has_card: false,
                                        status: status, //empty:无卡
                                    };
                                }
                            })
                        } catch (e) {

                        }
                        if (j % 2 != 0) {
                            status[i][0][k] = res;
                            k++;
                        } else {
                            status[i][1][l] = res;
                            l++;
                        }
                    }
                }

                status = status.reverse();
                axios.post("/user/device", {
                    ip: device['ip'],
                    mac: device['mac'],
                }).then(response => {
                    this.frequency = response.data.data.frequency;
                    this.real_device.push({
                        fail: response.data.data.fail,
                        income: response.data.data.income,
                        success: response.data.data.success,
                        ip: response.data.data.ip,
                        mac: response.data.data.mac,
                        status: status,
                    });
                }).catch(error => {
                    console.log(error.response.data)
                });
            },

            prefixInteger(num, n) {
                return (Array(n).join(0) + num).slice(-n);
            },

        }

    }
</script>
<style>
    .ka_cao {
        float: left;
        border: 1px solid #dee2e6;
        width: 6.25%;
        height: 6.25%;
        text-align: center;
        padding: 0.5rem;
    }

    .empty {
        background-color: #c4c4c4;
        color: black;
    }

    .success , .unknown{
        background-color: #38c172;
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
