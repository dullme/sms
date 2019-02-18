<template>
    <div style="float: left;padding: 10px; min-width: 600px; width: 100%">
        <div style="margin-top:40px; margin-left: 100px;font-size: 16px; font-weight: bolder;">
            <span>您当前账户余额为：{{ amount }}元，可提现金额为：{{ can_withdraw }}元</span>
        </div>
        <form @submit.prevent="onSubmit" style="margin-top: 20px;margin-left: 150px;font-size: 18px; font-weight: bolder;">
            <div style="margin-top: 30px">
                <label>转账金额 ： &nbsp;</label>
                <input type="text" style="width: 250px" name="transfer_amount" v-model="transfer_amount">
            </div>

            <div style="margin-top: 18px">
                <label>资金密码 ： &nbsp;</label>
                <input type="password" style="width: 250px;" name="withdraw_password" v-model="withdraw_password">
            </div>

            <div style="margin-top: 18px">
                <label>转入账户 ： &nbsp;</label>
                <input type="text" style="width: 250px;" v-model="username">
                <span style="font-size: 14px;margin-left: 5px; background-color: white; padding: 5px 10px; color: black; border: 2px solid rgb(166, 166, 166); border-radius: 4px; text-decoration: none; cursor: pointer;" v-on:click="searchUser">验证</span>
                <span style="margin-left: 10px" class="text-success" v-text="real_name"></span>
            </div>

            <div id="message" class="text-danger" style="height: 24px; font-size: 16px;margin-left: 110px" v-text="message"></div>
            <div class="text-success" style="height: 24px; font-size: 16px;margin-left: 110px" v-text="success"></div>

            <div style="margin-top: 30px; margin-left: 110px">
                <a class="btn btn-lg btn-default" style="width: 160px;background-color: white; font-weight: bolder; border: 2px solid #BBBBBB" v-on:click="saveAdds">转&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;账</a>
            </div>
        </form>
        <div style="margin-top: 80px; font-size: 16px; font-weight: bold; margin-left: 100px">
            <p>注意：</p>
            <div style="margin-left: 40px">
                <p v-for="info in withdraw_info.split('<br/>')">{{ info }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    require('../../../public/vendor/datejs/date-zh-CN')

    export default {
        data() {
            return {
                transfer_amount:'',
                withdraw_password:'',
                username:'',
                message:'',
                real_name:'',
                success:''
            }
        },
        props: [
            'amount', //余额
            'can_withdraw', //可提现金额
            'withdraw_info', //可提现金额
        ],

        mounted() {
            // this.uploadFile();
        },

        methods: {
            searchUser(){
                this.real_name = ''
                this.message = '';
                if(this.username != ''){
                    axios.post("/user/info", {
                        username: this.username,
                    }).then(response => {
                        this.real_name = response.data.data.real_name ? response.data.data.real_name : response.data.data.username;

                    }).catch(error => {
                        this.message = error.response.data.message;
                    });
                }

            },

            //转账
            saveAdds(){
                this.success = '';
                this.message = '';
                axios.post("/info/transfer", {
                    transfer_amount: this.transfer_amount,
                    withdraw_password: this.withdraw_password,
                    username: this.username,
                }).then(response => {
                    this.success = '转账成功！';
                    this.amount = response.data.data.amount;
                    this.can_withdraw = response.data.data.can_withdraw;
                }).catch(error => {
                    this.message = error.response.data.message;
                });
            }

        }

    }

</script>
