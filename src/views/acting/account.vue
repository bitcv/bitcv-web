<template>
  <div class="account">
    <div>
      <div class="recharge-bottom" v-loading="balanceLoad">
        <h5 class="title">
          资产余额<i class="el-icon-refresh" @click="handleRefresh"></i>
        </h5>
        <el-table :data="assetList" style="width:100%">
          <el-table-column prop="symbol" label="币种"></el-table-column>
          <el-table-column prop="amount" label="账户余额"></el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <el-button type="warning" size="small" style="color:#fff;"  @click="recharge(scope.row.walletAddr)">充值</el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-dialog title="充值地址" width="500px" :visible.sync="showDialog"  center>
          <div>
            <div style="text-align: center;">
              <vue-qr :text="walletAddr" :margin="10" size=126 class="qrcode"></vue-qr>
              <div style="margin-top:30px;">
                <span class="addressText" style="font-size:18px;color:rgba(0,0,0,1);" data-clipboard-text="walletAddr"> {{ walletAddr }}</span>
              </div>
            </div>
            <div style="margin-top:29px;">
              <p style="color:rgb(253, 152, 1);text-align: center;font-size: 12px;">温馨提示</p>
              <p style="text-align: center;font-size: 10px;margin-top:8px;color: rgba(179,179,179,1);">请勿向上述地址充值任何非 {{recSymbol}} 资产，否则资产将不可找回。您充值至上述地址后，需要整个网络节点的确认，1 次网络确认后到账。最小充值金额：0.001 {{recSymbol}}，小于最小金额的充值将不会上账。</p>
            </div>
            <div style="text-align: center">
              <el-button type="warning" v-clipboard:copy = "walletAddr" v-clipboard:success="onCopy" v-clipboard:error = "onError" style="width:350px;">复制地址</el-button>
            </div>
          </div>
        </el-dialog>
      </div>

      <div>
        <h5 class="title">
          充值记录<i class="el-icon-refresh" @click="fetchFinance"></i>
        </h5>
        <el-table :data="recordList" style="width: 100%" v-loading="recordLoad">
          <el-table-column type="index" label="序号" width="100"></el-table-column>
          <el-table-column prop="tokenSymbol" label="币种" width="100"></el-table-column>
          <!--<el-table-column prop="fromAddr" label="地址"></el-table-column>-->
          <el-table-column prop="amount" label="数量" width="180"></el-table-column>
          <el-table-column prop="status" label="状态" width="180">
            <template slot-scope="scope">
              <span :class="scope.row.status === 1 ? 'text-success' : ''">
                {{['验证中', '已完成'][scope.row.status]}}
              </span>
            </template>
          </el-table-column>
          <el-table-column prop="txTime" label="时间"></el-table-column>
        </el-table>
      </div>
    </div>
  </div>
</template>

<script>
import {mapState, mapActions} from 'vuex'
import VueQr from 'vue-qr'
import bus from '@/utils/bus'
import Share from "@/components/share/Share";
import html2canvas from "html2canvas";
export default {
  components: {
    VueQr,
    Share
  },
  data () {
    return {
      tokenProtocol: 1,
      assetList: [],
      showDialog: false,
      walletAddr: '',
      loading: false,
      balanceLoad: false,
      showShare: false,
      shareUrl: "",
      recordList: [],
      recordLoad: false,
      recSymbol: '',
      recAmount: ''
    }
  },
  computed: {
    ...mapState({
      path: state => state.route.path
    }),
    tokenData () {
      let tokenData = {}
      console.log('tokendata')
      this.assetList.forEach(item => {
        if (parseInt(item.tokenId) === parseInt(this.orderData.tokenId)) {
          tokenData = item
        }
      }, this)
      return tokenData
    }
  },
  mounted () {
    this.fetchBalance()
    this.fetchFinance()
  },
  methods: {
    ...mapActions(['getDispenseBalance', 'getDispenseFinance']),
    fetchBalance () {
      this.balanceLoad = true
      this.getDispenseBalance({
        tokenProtocol: this.tokenProtocol
      }).then((data = {}) => {
        this.assetList = data.dataList
        this.balanceLoad = false
      })
    },
    fetchFinance () {
      this.recordLoad = true
      this.getDispenseFinance({
        type: 1
      }).then(res => {
        this.recordList = res.dataList
        this.recordLoad = false
      }).catch(() => {
        this.recordLoad = false
      })
    },
    recharge (index) {
      this.walletAddr = index
      this.showDialog = true
      //console.log(index)
      //var data = this.assetList[index]
      //console.log(data)
      //this.recSymbol = index
      
    },
    onCopy (e) {
      this.$message.success('复制成功!')
    },
    onError (e) {
      this.$message.success('复制失败!')
    },
    handleRefresh () {
      this.fetchBalance()
    }
  }
}
</script>

<style lang="scss">
@import '~@/styles/variables';
.account{
  padding: 80px 0;
  .recharge-top{
    .left{
      & > div{
        padding: 30px;
        overflow: hidden;
        .el-input{
          width: 80%;
          float: left;
        }
        span, input{
          background: #fcf7f1;
          border: 1px solid $primary-color;
          height: 40px;
          line-height: 40px;
          padding: 0 20px;
        }
        i{
          color: $primary-color;
          font-size: 30px;
          float: left;
          margin-left: 10px;
          margin-top: 10px;
          cursor: pointer;
        }
      }
      p{
        padding: 0 0 30px 30px;
        margin-top: 10px;
        color: $gray;
        font-size: 12px;
        span{
          color: salmon;
        }
      }
    }
    .right{
      .qrcode, .qrcode img{
        width: 120px;
        height: 120px;
      }
      p{
        font-size: 12px;
        color: #ccc;
        line-height: 40px;
      }
    }
  }
  .recharge-bottom{
    //border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    .el-row{
      padding: 30px;
    }
    // span{
      //   font-size: 28px;
      //   color: $primary-color;
    // }
  }
  .recharge-footer{
    text-align: right;
    padding: 20px;
  }
  h5.title{
    margin-top: 50px;
    i{
      margin-left: 10px;
      padding: 3px;
      background: #fcf7f1;
      border: 1px solid $primary-color;
      color: $primary-color;
    }
  }
  .filter{
    overflow: hidden;
    li{
      float: left;
      cursor: pointer;
      padding: 5px 15px;
      font-size: 12px;
    }
    .active{
      color: #fff;
      background-color: $primary-color;
    }
  }
}
@media (max-width: 767px) {
  .account{
    padding: 10px 0;
    .recharge-top{
      .left{
        width: 100%;
        p{
          padding: 0;
        }
        div{
          padding: 0px;
        }
      }
      .right{
        width: 100%;
        text-align: center;
        .qrcode{
          margin: 0 auto;
        }
      }
    }
    .recharge-bottom{
      .el-row{
        padding: 10px 0;
      }
    }
  }
}
.share-box {
  height: 46px;
  display: flex;
  position: absolute;
  right: 40px;
  bottom: 0;
  align-items: center;
  font-size: 14px;
  line-height: 20px;
  color: #000;
  cursor: pointer;
  img {
    width: 23px;
    height: 23px;
    margin: 0 5px;
  }
}
.share-container {
  width: 400px;
  position: fixed;
  z-index: 10;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  cursor: pointer;
  margin: auto;
  display: flex;
  justify-content: center;
  align-items: center;
  & > img {
    width: 100%;
  }
}
</style>
