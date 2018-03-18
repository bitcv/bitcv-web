<template>
  <div class="step1">
    <el-form v-if="!isUpload" :model="form" :rules="rules" ref="form" label-width="100px" class="form">
      <el-form-item label="" prop="type">
        <el-radio-group v-model="tokenInfo.isTest">
          <el-radio :label="0">正式发放</el-radio>
          <el-radio :label="1">测试发放<small style="color: #ccc;">（测试时仅向前两个地址发放代币）</small></el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item class="step1-input-group" label="发放类别" prop="currency">
        <el-select v-model="form.currency" placeholder="请选择">
          <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value"></el-option>
        </el-select>
        <el-input class="step1-input" placeholder="请输入合约地址" @blur="getToken" v-if="form.currency !== 1" v-model="tokenInfo.contractAddr">
          <i slot="suffix">{{tokenInfo.tokenSymbol}}</i>
        </el-input>
      </el-form-item>
      <el-form-item label="上传地址">
          <el-tooltip class="item" effect="dark" content="请输入合约地址" :disabled="tokenInfo.tokenId ? true : false" placement="bottom">
        <el-upload
          class="upload-btn"
          name="addr"
          action="/api/parseAddrFile"
          :before-upload="handleBefore"
          :on-success="handleSuccess"
          :on-error="handleError"
          :data="tokenInfo"
          :disabled="tokenInfo.tokenId ? false : true"
          :show-file-list="false">
            <el-button slot="trigger" type="warning" class="btn-primary">点击上传</el-button>
        </el-upload>
          </el-tooltip>
        <el-button type="warning" plain @click="fetch">获取模板</el-button>
      </el-form-item>
    </el-form>
    <div v-else>
      <h4>上传成功</h4>
      <el-table :data="list" height="400" style="width: 100%">
        <el-table-column type="index"></el-table-column>
        <el-table-column prop="address" width="400" label="地址"></el-table-column>
        <el-table-column prop="amount" label="数量"></el-table-column>
        <el-table-column prop="status" label="状态"></el-table-column>
      </el-table>
      <footer class="footer">
        <el-row>
          <el-col :span="5">
            <h5>总数量</h5>
            <div>
              {{totalAmount}}
              <small>枚</small>
            </div>
          </el-col>
          <el-col :span="5">
            <h5>总地址</h5>
            <div>
              {{dataCount}}
              <small>条</small>
            </div>
          </el-col>
          <el-col :span="5">
            <h5>错误数据</h5>
            <div>
              {{wrongCount}}
              <small>条</small>
            </div>
          </el-col>
          <el-col :span="9" class="text-center">
            <el-button type="warning" class="btn-primary" @click="confirm">确定</el-button>
            <el-upload class="upload-btn" name="addr" action="/api/parseAddrFile" :data="tokenInfo" :before-upload="handleBefore" :on-success="handleSuccess" :on-error="handleError" :show-file-list="false">
              <el-button type="text" class="text-primary">重新上传</el-button>
              <!--<el-button slot="trigger" type="warning" class="btn-primary">点击上传</el-button>-->
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
    let valid = (rule, val, callback) => {
      if (val === undefined || val === '') {
        callback(new Error('必填'))
      }
      callback()
    }
    let validAddr = (rule, val, callback) => {
      if (val === undefined || val === '') {
        callback(new Error('必填'))
      }
      if (!val.startsWith('0x') || val.length !== 42) {
        callback(new Error('请输入正确的合约地址'))
      }
      callback()
    }
    return {
      form: {
        address: '',
        type: 0,
        currency: 0
      },
      rules: {
        address: [ {validator: validAddr} ],
        type: [{validator: valid}]
      },
      isUpload: false,
      list: [],
      tokenInfo: {
        isTest: 0,
        tokenId: 0,
        tokenSymbol: '',
        contractAddr: ''
      },
      dataCount: 0,
      uniqueCount: 0,
      wrongCount: 0,
      totalAmount: 0,
      options: [
        {
          value: 0,
          label: 'ERC20'
        },
        {
          value: 1,
          label: 'ETH'
        }
      ]
    }
  },
  methods: {
    ...mapActions(['addUserDispenseAsset', 'getTokenInfo']),
    handleBefore (file) {
      if (!this.tokenInfo.tokenId) {
        alert('请填写合约地址')
        return false
      }
    },
    handleSuccess (res) {
      console.log('success')
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
    submit () {
      this.$refs.form.validate((valid) => {
        if (valid) {
          this.isUpload = true
        } else {
          console.log('error submit!!')
          return false
        }
      })
    },
    fetch () {
      console.log('download')
      downloadFile('/static/file/sample.xls')
    },
    getToken () {
      if (!this.tokenInfo.contractAddr) {
        return
      }
      this.getTokenInfo({
        contractAddr: this.tokenInfo.contractAddr
      }).then((data = {}) => {
        this.tokenInfo.tokenId = data.tokenId
        this.tokenInfo.tokenSymbol = data.tokenSymbol
      })
    },
    confirm () {
      this.addUserDispenseAsset({
        tokenId: this.tokenInfo.tokenId
      }).then(() => {
        this.$emit('finished', {
          tokenId: this.tokenInfo.tokenId,
          totalAmount: this.totalAmount,
          totalCount: this.dataCount - this.wrongCount
        })
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
  .step1-input-group{
    overflow: hidden;
    .el-select{
      width: 30%;
      float: left;
    }
    .step1-input{
      width: 60%;
      float: left;
      margin-left: 5%;
      .el-input__suffix{
        padding: 0 10px;
      }
      i{
        font-style: normal;
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
