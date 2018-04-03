<template>
  <div class="candy-order-confirm">
    <div class="title-area">
      <span>{{ $t('label.order_detail') }}</span>
    </div>
    <div class="content-area">
      <div class="info-area" :class="mediaClass()">
        <div v-if="orderData.projData" class="info-row">
          <span class="title">{{ $t('label.candy_project') }}：</span>
          <div class="content-box">
            <img :src="orderData.projData.logoUrl" alt="">
            <div class="info-box">
              <span class="title">{{ orderData.projData.tokenSymbol }}</span>
              <span class="text">{{ orderData.projData.tokenName }}</span>
            </div>
          </div>
        </div>
        <div class="info-row">
          <div class="info-item">
            <span class="title">{{ $t('label.in_amount') }}：</span>
            <span class="content">{{ orderData.orderAmount }} {{ $t('label.coin_amount') }}</span>
          </div>
          <div class="info-item">
            <span class="title">{{ $t('label.lock') }}：</span>
            <span class="content">{{ orderData.lockTime }} {{ $t('label.month') }}</span>
          </div>
          <div class="info-item">
            <span class="title">{{ $t('label.return') }}：</span>
            <span class="content">{{ getInterest(orderData.orderAmount, orderData.interestRate, orderData.lockTime) }} {{ $t('label.coin_amount') }}</span>
          </div>
        </div>
        <div class="info-row addr">
          <span class="title addr">{{ $t('label.re_address') }}：</span>
          <span class="content">{{ orderData.toAddr }}</span>
        </div>
        <div class="info-row addr">
          <span class="title addr">{{ $t('label.your_address') }}：</span>
          <span class="content">{{ orderData.fromAddr }}</span>
        </div>
        <!--<div class="status-row">-->
          <!--<span class="title">订单状态：</span>-->
          <!--<span class="content">等待确认</span>-->
        <!--</div>-->
        <div class="qrcode" :class="mediaClass()"></div>
      </div>
      <div class="table-box" :class="mediaClass()" v-if="showConfirm">
        <span class="title">{{ $t('label.sys_notice') }}</span>
        <table>
          <tr>
            <th>{{ $t('label.in_amount') }}</th>
            <th class="mobile-hide">{{ $t('label.r_time') }}</th>
            <th>{{ $t('label.hash') }}</th>
            <th>{{ $t('label.hash') }}</th>
          </tr>
          <tr v-for="(record, index) in recordList" :key="index">
            <td>{{ record.txAmount }}</td>
            <td class="mobile-hide">{{ record.txTime }}</td>
            <td><a :href="'https://etherscan.io/tx/' + record.txHash" target="_blank">{{ getShortStr(record.txHash, 12) }}</a></td>
            <td><input @change="changeCheck($event, record.id)" type="checkbox"></td>
          </tr>
        </table>
        <div class="btn default" :class="mediaClass()" @click="refreshTx">
          <span>{{ $t('label.refresh_data') }}</span>
        </div>
        <div class="btn" @click="confirmTx">
          <span>{{ $t('label.co_co') }}</span>
        </div>
      </div>
      <div class="btn-box" v-else>
        <div class="btn-row">
          <input type="checkbox">
          <span>{{ $t('label.my_goal') }}<em>{{ orderData.orderAmount }}</em> {{ $t('label.coin_amount') }}</span>
        </div>
        <div class="btn" :class="mediaClass()" @click="toOrderConfirm">
          <span>{{ $t('label.start_co') }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      checkBox: '',
      recordList: [],
      recordIdList: [],
      orderData: {},
      showConfirm: false
    }
  },
  mounted () {
    this.$http.post('/api/getOrderDetail', {
      depositOrderId: this.$route.params.id
    }).then((res) => {
      if (res.data.errcode === 0) {
        this.orderData = res.data.data
        this.$nextTick(() => {
          require('@/components/share/jquery.min.js')
          require('@/components/share/qrcode.min.js')
          this.getQrcode()
        })
      }
    })
  },
  methods: {
    confirmTx () {
      this.$http.post('/api/confirmDepositTx', {
        depositOrderId: this.orderData.id,
        txRecordIdList: this.recordIdList
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$router.push('/candyRoom/myCandyOrder')
        } else {
          alert(res.data.errmsg)
        }
      })
    },
    refreshTx () {
      this.updTxRecord()
    },
    toOrderConfirm () {
      this.updTxRecord()
      this.showConfirm = true
    },
    updTxRecord () {
      this.$http.post('/api/getOrderTxRecordList', {
        depositOrderId: this.orderData.id
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.recordList = res.data.data.dataList
        }
      })
    },
    changeCheck (e, recordId) {
      var isChecked = e.target.checked
      if (isChecked) {
        this.recordIdList.push(recordId)
      } else {
        this.recordIdList.remove(recordId)
      }
    },
    getQrcode () {
      // eslint-disable-next-line
      $('.qrcode').qrcode({
        text: this.orderData.toAddr,
        width: 150,
        height: 150
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.candy-order-confirm {
  .title-area {
    width: 100%;
    height: 108px;
    background-color: #FFF;
    font-size: 24px;
    color: #4A4A4A;
    text-align: center;
    line-height: 108px;
    margin-bottom: 10px;
  }
  .content-area {
    background-color: #FFF;
    padding: 20px;
    .info-area {
      box-sizing: border-box;
      width: 80%;
      border-bottom: 1px solid #C2C2C2;
      padding: 30px 0 30px;
      position: relative;
      margin: auto;
      &.media-mobile {
        width: calc(100% + 30px);
        margin: 0 -15px;
        box-sizing: border-box;
        .info-row {
          font-size: 14px;
          line-height: 20px;
          &.addr {
            background-color: #F2F2F2;
          }
          >span.title.addr {
            display: block;
            width: 100%;
            text-align: center;
            font-size: 18px;
            line-height: 24px;
            padding-top: 8px;
          }
          >.content {
            display: block;
            width: 100%;
            text-align: center;
          }
          &:first-child {
            margin-bottom: 10px;
          }
          &:nth-child(2) {
            margin-bottom: 10px;
          }
          .title {
            display: inline-block;
            text-align: right;
            width: 80px;
          }
          .info-item {
            display: block;
            margin-right: 5px;
          }
        }
      }
      .info-row {
        min-height: 46px;
        font-size: 16px;
        color: #4A4A4A;
        &:first-child {
          margin-bottom: 20px;
        }
        >.title {
          display: inline-block;
          width: 80px;
          text-align: right;
        }
        .info-item {
          margin-right: 40px;
          display: inline-block;
          .content {
            font-size: 16px;
            color: #FF6276;
          }
        }
        .content-box {
          display: inline-block;
          vertical-align: middle;
          font-size: 0;
          img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
          }
          .info-box {
            display: inline-flex;
            vertical-align: top;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-start;
            height: 40px;
            font-size: 14px;
            color: #9B9B9B;
            .title {
              font-size: 18px;
              color: #4A4A4A;
              text-align: left;
            }
          }
        }
      }
      .status-row {
        position: absolute;
        top: 20px;
        right: 20px;
        .content {
          color: #FF6276;
        }
      }
    }
    .table-box {
      text-align: center;
      padding: 0 80px;
      &.media-mobile {
        padding: 0;
      }
      .title {
        display: inline-block;
        font-size: 16px;
        color: #FF6276;
        margin: 30px auto;
      }
      table {
        width: 100%;
        font-size: 16px;
        color: #4A4A4A;
        margin-bottom: 40px;
        tr {
          border-bottom: 1px solid #979797;
          line-height: 60px;
          td {
            color: #000;
          }
        }
      }
      .btn {
        display: inline-block;
        width: 160px;
        height: 40px;
        border-radius: 23px;
        background-color: #FF6276;
        color: #FFF;
        line-height: 40px;
        cursor: pointer;
        margin-bottom: 10px;
        &.media-mobile {
          width: 140px !important;
        }
        &.default {
          margin-right: 20px;
        }
        &.media-mobile.default {
          margin-right: 0;
        }
      }
    }
    .btn-box {
      text-align: center;
      .btn-row {
        color: #9B9B9B;
        line-height: 70px;
        font-size: 0;
        input {
          vertical-align: middle;
          width: 22px;
          height: 22px;
          position: relative;
          bottom: 6px;
        }
        span {
          font-size: 16px;
          em {
            color: #FF6276;
          }
        }
      }
      .btn {
        display: inline-block;
        width: 160px;
        height: 40px;
        border-radius: 23px;
        background-color: #FF6276;
        color: #FFF;
        line-height: 40px;
        cursor: pointer;
        margin-bottom: 10px;
        &.meida-mobile {
          width: 100px;
          height: 35px;
        }
      }
    }
  }
  .qrcode {
    position: absolute;
    width: 150px;
    height: 150px;
    right: 110px;
    top: 80px;
    &.media-mobile {
      display: none;
    }
  }
}
</style>
