<template>
  <div class="step1">
    <el-form v-if="!isUpload" :model="formData" :rules="formRule" ref="form" label-width="100px" class="form">
      <el-form-item>
        <el-radio-group v-model="formData.isTest">
          <el-radio :label="0">正式发放</el-radio>
          <el-radio :label="1">测试发放<small style="color: #ccc;">（测试时仅向前两个地址发放代币）</small></el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item class="inline-item select-item" label="发放类别">
        <el-select v-model="formData.tokenType" placeholder="请选择" v-loading="selectLoad">
          <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item class="inline-item input-item" prop="contractAddr" v-if="formData.tokenType === 0">
        <!-- <el-button style="color: #ccc;font-size:12px;margin-left:326px;" type="text" @click="findAddress">怎么查找合约地址？</el-button> -->
        <el-tooltip placement="top" effect="light" width="200">
          <div slot="content">1:访问以太网址<br/><a href="https://etherscan.io/tokens" target="_blank">https://etherscan.io/tokens</a><br/><br/>2:输入token名称查找<br><img src="/static/img/search.png" style="width:166px;height:22px;" alt=""></div>
            <span style="color: #ccc;font-size:12px;margin-left:326px;">如何找到合约地址？</span>
        </el-tooltip>
        <el-input v-if="tokenType === 0" class="step1-input" placeholder="请输入合约地址" @blur="getToken" v-model="formData.contractAddr">
          <span style="margin-right:10px;" slot="suffix" v-loading="tokenLoad">{{formData.tokenSymbol}}</span>
        </el-input>
      </el-form-item>
      <el-form-item>
        <el-checkbox style="color:#ccc" v-model="checked" ></el-checkbox>
        <!-- <el-button style = "text-align:center;color: #A1A1A1;" type="text" @click="open">用户协议</el-button> -->
        <el-button style="color: #ccc;font-size:12px;" type="text" @click="open5">用户协议</el-button>
      </el-form-item>
      <el-form-item label="获取模版">
        <el-button type="warning" plain @click="fetchSample">点击下载</el-button>
      </el-form-item>
      <el-form-item label="上传地址">
        <el-tooltip class="item" effect="dark" content="请输入合约地址" :disabled="!uploadDisable" placement="bottom">
          <el-upload class="upload-btn" name="addr" action="/api/parseAddrFile" :before-upload="handleBefore" :on-success="handleSuccess" :on-error="handleError" :data="formData" :disabled="uploadDisable" :show-file-list="false">
            <el-button slot="trigger" type="warning" :disabled="disableFile" class="btn-primary">点击上传</el-button>
          </el-upload>
        </el-tooltip>   
      </el-form-item>
    </el-form>
    <div v-else>
      <h4>上传成功</h4>
      <el-table :data="list" height="400" style="width: 100%">
        <el-table-column type="index"></el-table-column>
        <el-table-column  width="400" label="地址"><template slot-scope="scope">{{ scope.row.address }}</template></el-table-column>
        <el-table-column label="数量"><template slot-scope="scope">{{ scope.row.amount }}</template></el-table-column>
        <el-table-column label="状态"><template slot-scope="scope">{{ [ '上传成功', '地址错误', '数量错误','地址或数量错误'][scope.row.status] }}</template></el-table-column>
        <!-- <el-table-column prop="address" width="400" label="地址"></el-table-column>
        <el-table-column prop="amount" label="数量"></el-table-column>
        <el-table-column label="状态">{{}}</el-table-column> -->
      </el-table>
      <footer class="footer">
        <el-row>
          <el-col :span="5">
            <h5>总数量</h5>
            <div>{{totalAmount}}<small>枚</small></div>
          </el-col>
          <el-col :span="5">
            <h5>总地址</h5>
            <div>{{dataCount}}<small>条</small></div>
          </el-col>
          <el-col :span="5">
            <h5>错误数据</h5>
            <div>{{wrongCount}}<small>条</small></div>
          </el-col>
          <el-col :span="9" class="text-center">
            <el-button  v-if="!wrongCount" type="warning" class="btn-primary" @click="confirm">确定</el-button>
            <el-upload  v-if="wrongCount" class="upload-btn" name="addr" action="/api/parseAddrFile" :data="formData" :before-upload="handleBefore" :on-success="handleSuccess" :on-error="handleError" :show-file-list="false">
              <el-button type="text" class="text-primary">重新上传</el-button>
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
        callback(new Error('请输入正确的ERC20合约地址！'))
      }
    }
    return {
      formData: {
        isTest: 0,
        tokenType: 0,
        contractAddr: '',
        tokenId: 0,
        tokenSymbol: ''
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
        this.$message.warn('请填写合约地址')
        return false
      }
    },
    open5() {
      this.$alert('本人同意使用代发宝业务进行数字货币转账，并承诺自行承担因此产生的风险，本人完全理解并同意接受如下条款：<br><br>1、发送方承诺并保证自行上传的发送资料中所有信息的真实性、准确性和合法性，并对此承担责任。<br><br> 2、如发送方上传的发送资料中，因资料错误、不完整，导致发送失败或者错误发送，由发送方承担责任，代发宝运营方无需承担任何责任。 <br><br>3、如因不可抗力导致服务中断（包括但不限于地震、战争、网络中断、网络攻击等），代发宝运营方无需承担任何责任代发宝在此声明，代发宝作为数字资产代发工具，并不实质性介入发送方与接收方的交易过程。代发宝的注册用户应对自己在代发宝充值的资产及一切转账行为承担责任，并承诺不以任何非法目的、不通过任何非法途径使用和推广代发宝。对于注册用户所有违背与代发宝服务相关的法律、法规及其他规范性文件的行为而造成的损失及后果，代发宝均不承担任何责任。', '免责声明', {
        dangerouslyUseHTMLString: true
      });
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
      this.$message.error('上传失败，请重试')
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
        this.uploadDisable = false
        this.tokenLoad = false
      })
    },
    confirm () {
      this.$emit('finished', {
        tokenId: this.formData.tokenId,
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
