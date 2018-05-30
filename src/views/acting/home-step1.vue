<template>
  <div class="step1">
    <el-form v-if="!isUpload" :model="formData" :rules="formRule" ref="form" label-width="100px" class="form">
      <el-form-item>
        <el-radio-group v-model="formData.isTest">
          <el-radio :label="0">{{ $t('label.fafang') }}</el-radio>
          <el-radio :label="1">{{ $t('label.ce_fafang') }}<small style="color: #ccc;">（{{ $t('label.ce_content') }}）</small></el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item class="inline-item select-item" :label="$t('label.fa_leibie')">
        <el-select v-model="formData.tokenType" :placeholder="$t('label.please_c')" v-loading="selectLoad">
          <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item class="inline-item input-item" prop="contractAddr" v-if="formData.tokenType === 0">
        <!-- <el-button style="color: #ccc;font-size:12px;margin-left:326px;" type="text" @click="findAddress">怎么查找合约地址？</el-button> -->
        <el-tooltip placement="top" effect="light" width="200">
          <div slot="content">1:{{ $t('label.fang_eth') }}<br/><a href="https://etherscan.io/tokens" target="_blank">https://etherscan.io/tokens</a><br/><br/>2:{{ $t('label.shu_token') }}<br><img src="/static/img/search.png" style="width:166px;height:22px;" alt=""></div>
            <span style="color: #ccc;font-size:12px;margin-left:239px;">{{ $t('label.find_addr') }}</span>
        </el-tooltip>
        <el-input v-if="tokenType === 0" class="step1-input" :placeholder="$t('label.he_addr')" @blur="getToken" v-model="formData.contractAddr">
          <span style="margin-right:10px;" slot="suffix" v-loading="tokenLoad">{{formData.tokenSymbol}}</span>
        </el-input>
      </el-form-item>
      <el-form-item>
        <el-checkbox style="color:#ccc" v-model="checked" ></el-checkbox>
        <!-- <el-button style = "text-align:center;color: #A1A1A1;" type="text" @click="open">用户协议</el-button> -->
        <el-button style="color: #ccc;font-size:12px;" type="text" @click="open5">{{ $t('label.user_shuoming') }}</el-button>
      </el-form-item>
      <el-form-item :label="$t('label.acct_blade')">
        <el-button type="warning" plain @click="fetchSample">{{ $t('label.click_down') }}</el-button>
      </el-form-item>
      <el-form-item :label="$t('label.up_addr')">
        <el-tooltip class="item" effect="dark" :content="$t('label.he_addr')" :disabled="!uploadDisable" placement="bottom">
          <el-upload class="upload-btn" name="addr" action="/api/parseAddrFile" :before-upload="handleBefore" :on-success="handleSuccess" :on-error="handleError" :data="formData" :disabled="uploadDisable" :show-file-list="false">
            <el-button slot="trigger" type="warning" :disabled="disableFile" class="btn-primary">{{ $t('label.click_up') }}</el-button>
          </el-upload>
        </el-tooltip>   
      </el-form-item>
    </el-form>
    <div v-else>
      <h4>{{ $t('label.upload_success') }}</h4>
      <el-table :data="list" height="400" style="width: 100%">
        <el-table-column type="index"></el-table-column>
        <el-table-column  width="400" label="地址"><template slot-scope="scope">{{ scope.row.address }}</template></el-table-column>
        <el-table-column :label="$t('label.coin_num')"><template slot-scope="scope">{{ scope.row.amount }}</template></el-table-column>
        <el-table-column v-if="language === 'cn'" :label="$t('label.coin_status')"><template slot-scope="scope">{{ [ '上传成功', '地址错误', '数量错误','地址或数量错误'][scope.row.status] }}</template></el-table-column>
        <el-table-column v-else-if="language === 'en'" :label="$t('label.coin_status')"><template slot-scope="scope">{{ [ 'Uploaded Successfully', 'Wrong Address', 'Incorrect Number','Incorrect Address Or Number'][scope.row.status] }}</template></el-table-column>
        <el-table-column v-else :label="$t('label.coin_status')"><template slot-scope="scope">{{ [ 'アップロード完了', 'アドレスが正しくありません', '数量が正しくありません','アドレスあるいは数量が正しくありません'][scope.row.status] }}</template></el-table-column>
        <!-- <el-table-column prop="address" width="400" label="地址"></el-table-column>
        <el-table-column prop="amount" label="数量"></el-table-column>
        <el-table-column label="状态">{{}}</el-table-column> -->
      </el-table>
      <footer class="footer">
        <el-row>
          <el-col :span="5">
            <h5>{{ $t('label.zong_amount') }}</h5>
            <div>{{totalAmount}}<small>{{ $t('label.coin_amount') }}</small></div>
          </el-col>
          <el-col :span="5">
            <h5>{{ $t('label.zong_addr') }}</h5>
            <div>{{dataCount}}<small>{{ $t('label.tiao') }}</small></div>
          </el-col>
          <el-col :span="5">
            <h5>{{ $t('label.err_data') }}</h5>
            <div>{{wrongCount}}<small>{{ $t('label.tiao') }}</small></div>
          </el-col>
          <el-col :span="9" class="text-center">
            <el-button  v-if="!wrongCount" type="warning" class="btn-primary" @click="confirm">{{ $t('label.confirm') }}</el-button>
            <el-upload  v-if="wrongCount" class="upload-btn" name="addr" action="/api/parseAddrFile" :data="formData" :before-upload="handleBefore" :on-success="handleSuccess" :on-error="handleError" :show-file-list="false">
              <el-button type="text" class="text-primary">{{ $t('label.second_upload') }}</el-button>
            </el-upload>
          </el-col>
        </el-row>
      </footer>
    </div>
  </div>
</template>

<script>
// import bus from '@/utils/bus'
import {mapActions} from 'vuex'
export default {
  data () {
    let addrRule = (rule, value, callback) => {
      let reg = /^0x[0-9a-fA-F]{40}$/
      if (reg.test(value)) {
        callback()
      } else {
        if (this.$i18n.locale === 'cn') {
          callback(new Error('请输入正确的ERC20合约地址！'))
        } else if (this.$i18n.locale === 'en') {
          callback(new Error('Please enter the correct ERC20 contract address！'))
        } else {
          callback(new Error('正しいERC20 コントラクトアドレスをご入力ください！'))
        }
      }
    }
    return {
      formData: {
        isTest: 0,
        tokenType: 0,
        contractAddr: '',
        tokenId: 0,
        tokenSymbol: '',
        logoUrl: ''
      },
      formRule: {
        contractAddr: [{validator: addrRule, trigger: 'blur'}]
      },
      isUpload: false,
      disableFile: false,
      checked: true,
      list: [],
      dataCount: 0,
      uniqueCount: 0,
      wrongCount: 0,
      totalAmount: 0,
      tokenType: 0,
      uploadDisable: true,
      tokenLoad: false,
      selectLoad: false,
      options: [{
        value: 0,
        label: 'ERC20'
      }, {
        value: 1,
        label: 'ETH'
      }]
    }
  },
  computed: {
    language () {
      return this.$i18n.locale
    }
  },
  watch: {
    'formData.tokenType' () {
      this.formData.tokenId = 0
      this.formData.tokenSymbol = ''
      this.formData.contractAddr = ''
      if (this.formData.tokenType === 1) {
        this.uploadDisable = true
        this.selectLoad = true
        this.getTokenBySymbol({
          tokenSymbol: 'ETH'
        }).then(data => {
          this.formData.tokenId = data.tokenId
          this.formData.tokenSymbol = data.tokenSymbol
          this.uploadDisable = false
          this.selectLoad = false
        })
      }else{
        this.uploadDisable = true
      }
    },
    'checked' (){
      if (!this.checked){
        this.disableFile = true
      }else{
        this.disableFile = false
      }
    }
  },
  methods: {
    ...mapActions(['addUserDispenseAsset', 'getTokenInfo', 'getTokenBySymbol']),
    handleBefore (file) {
      if (!this.formData.tokenId) {
        if (this.$i18n.locale === 'cn') {
          this.$message.warn('请填写合约地址')
        } else if (this.$i18n.locale === 'en') {
          this.$message.warn('Please fill in the contract address')
        } else {
          this.$message.warn('契約の住所を記入してください')
        }
        return false
      }
    },
    open5() {
      if (this.$i18n.locale === 'cn') {
        this.$alert('本人同意使用代发宝业务进行数字货币转账，并承诺自行承担因此产生的风险，本人完全理解并同意接受如下条款：<br><br>1、发送方承诺并保证自行上传的发送资料中所有信息的真实性、准确性和合法性，并对此承担责任。<br><br> 2、如发送方上传的发送资料中，因资料错误、不完整，导致发送失败或者错误发送，由发送方承担责任，代发宝运营方无需承担任何责任。 <br><br>3、如因不可抗力导致服务中断（包括但不限于地震、战争、网络中断、网络攻击等），代发宝运营方无需承担任何责任代发宝在此声明，代发宝作为数字资产代发工具，并不实质性介入发送方与接收方的交易过程。代发宝的注册用户应对自己在代发宝充值的资产及一切转账行为承担责任，并承诺不以任何非法目的、不通过任何非法途径使用和推广代发宝。对于注册用户所有违背与代发宝服务相关的法律、法规及其他规范性文件的行为而造成的损失及后果，代发宝均不承担任何责任。', '免责声明', {
          dangerouslyUseHTMLString: true
        });
      } else if (this.$i18n.locale === 'en') {
        this.$alert('I agree to use AutoTransfer to conduct digital asset transfer and promise to bear the risks from it. I fully understand and agree to accept the following terms：<br><br>1、The sender promises and guarantees all the accuracy and legality of the information in the self-uploaded data and take responsibility for it.<br><br> 2、If the data sender uploads is wrong or incomplete, causes the sending to fail or error, the sender takes the responsibility. The operator does not need to bear any responsibility. <br><br>3、If the service interruption due to force majeure (including but not limited to earthquake, war, network interruption, network attack, etc.), the operator does not need to assume any responsibility. On behalf of a digital currency transfer tool, AutoTransfer does not substantively involve the transaction process between the sender and the receiver. The registered users of AutoTransfer shall be responsible for their own assets and all fund transfer activities on behalf of AutoTransfer, and promise not to use and promote AutoTransfer for any illegal purposes or through any illegal means. For the losses and consequences caused by all registered users\' violation of the laws, regulations, and other regulatory documents related to AutoTransfer services, AutoTransfer shall not bear any responsibility.', 'Disclaimer', {
          dangerouslyUseHTMLString: true
        });
      } else {
        this.$alert('私は代発宝で仮想通貨の送金を行うことに同意し、またそれにより生じたリスクを負うことを承諾いたします。私は以下の事項を十分理解し、同意いたします：<br><br>1、ユーザーがアップロードした資料の真実性、正確性または合法性を保証し、関連責任を取ります。<br><br> 2、資料の間違い、不足により生じた送金失敗や送金エラーについて、ユーザー側が責任を取ります。代発宝運営側は一切責任を負えません。 <br><br>3、不可抗力により生じたサービス中断に対して（地震、戦争、ネット中断、サイバー攻撃に限らない）、代発宝運営側は一切責任を負えません。代発宝はここで声明を出します。代発宝はデジタルアセットの送金代行ツールとして、ユーザーとその送金先（受取人）の取引に介入しません。代発宝のユーザーは代発宝にチャージしたアセットや代発宝で行った取引について責任を取ります。また、ユーザーは違法な目的・用途で代発宝を使用・宣伝しないことを承諾いたします。ユーザーが違法、ルール違反で被った損失などについて、代発宝運営側は一切責任を負えません。', '免責事項', {
          dangerouslyUseHTMLString: true
        });
      }
    },
    findAddress() {
      this.$alert('1:访问以太网址<br/><a href="https://etherscan.io/tokens" target="_blank">https://etherscan.io/tokens</a><br><br>2:输入token名称查找<br><input disabled="disabled" placeholder="Search for any ERC20 Token Name/Address"><button disabled="disabled" style="background-color:#95a5a6">Find</button>', '', {
        dangerouslyUseHTMLString: true
      });
    },
    handleSuccess (res) {
      if (res.errcode === 0) {
        this.isUpload = true
        this.list = res.data.dataList
        this.dataCount = res.data.dataCount
        this.totalAmount = res.data.totalAmount
        this.wrongCount = res.data.wrongCount
      }
    },
    handleError (res) {
      if (this.$i18n.locale === 'cn') {
        this.$message.error('上传失败，请重试')
      } else if (this.$i18n.locale === 'en') {
        this.$message.error('Upload failed, please try again')
      } else {
        this.$message.error('アップロードに失敗しました。もう一度お試しください')
      }
    },
    fetchSample () {
      window.downloadFile('/static/file/sample.xls')
    },
    getToken () {
      if (!/^0x[0-9a-fA-F]{40}$/.test(this.formData.contractAddr)) return
      this.uploadDisable = true
      this.tokenLoad = true
      this.getTokenInfo({
        contractAddr: this.formData.contractAddr
      }).then((data = {}) => {
        this.formData.tokenId = data.tokenId
        this.formData.tokenSymbol = data.tokenSymbol
        this.formData.logoUrl = data.logoUrl
        this.uploadDisable = false
        this.tokenLoad = false
      })
    },
    confirm () {
      this.$emit('finished', {
        tokenId: this.formData.tokenId,
        tokenSymbol: this.formData.tokenSymbol,
        logoUrl: this.formData.logoUrl,
        totalAmount: this.totalAmount,
        totalCount: this.dataCount - this.wrongCount
      })
    }
  }
}
</script>

<style lang="scss">
.step1{
  padding: 80px 0;
  .el-form-item, .el-form-item__content{
    &::after, &::before{
      display: none;
    }
  }
  .el-form-item, .el-form-item__label {
    line-height: 28px;
  }
  .el-upload__input{
    display: none;
  }
  .upload-btn{
    display: inline-block;
  }
  .el-button--text:focus, .el-button--text:hover{
    color: orange;
  }
  .el-form-item.inline-item {
    display: inline-block;
    &.select-item {
      width: 30%;
    }
    &.input-item {
      width: 55%;
      .el-form-item__content {
        margin-left: 16px !important;
      }
    }
  }
  .footer{
    background: #fdf6ec;
    margin-top: 30px;
    padding: 20px 60px;
    .el-col-7{
      h5{
        color: #999;
      }
      div{
        font-size: 18px;
        color: orange;
        small{
          font-size: 12px;
          color: #ccc;
        }
      }
    }
    .el-col-10{
      line-height: 60px;
    }
  }
  @media (max-width: 767px) {
    .el-form-item__content{
      &:nth-child(1) {
        margin-left: 10px!important;
      }
    }
    .el-radio+.el-radio{
      margin-left: 0;
    }
    .footer{
      padding: 0;
      .el-row{
        padding: 10px;
      }
      .el-button+.el-button{
        margin-left: 0;
      }
      .btn-primary{
        padding: 8px 14px;
      }
    }
  }
}
</style>
