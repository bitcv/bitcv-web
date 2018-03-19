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
        <el-input v-if="tokenType === 0" class="step1-input" placeholder="请输入合约地址" @blur="getToken" v-model="formData.contractAddr">
          <i slot="suffix" v-loading="tokenLoad">{{formData.tokenSymbol}}</i>
        </el-input>
      </el-form-item>
      <el-form-item label="上传地址">
        <el-tooltip class="item" effect="dark" content="请输入合约地址" :disabled="!uploadDisable" placement="bottom">
          <el-upload class="upload-btn" name="addr" action="/api/parseAddrFile" :before-upload="handleBefore" :on-success="handleSuccess" :on-error="handleError" :data="formData" :disabled="uploadDisable" :show-file-list="false">
            <el-button slot="trigger" type="warning" class="btn-primary">点击上传</el-button>
          </el-upload>
        </el-tooltip>
        <el-button type="warning" plain @click="fetchSample">获取模板</el-button>
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
            <el-button type="warning" class="btn-primary" @click="confirm">确定</el-button>
            <el-upload class="upload-btn" name="addr" action="/api/parseAddrFile" :data="formData" :before-upload="handleBefore" :on-success="handleSuccess" :on-error="handleError" :show-file-list="false">
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
        }).catch(err => {
          this.selectLoad = false
        })
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
      downloadFile('/static/file/sample.xls')
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
      }).catch(err => {
        this.tokenLoad = false
      })
    },
    confirm () {
      this.addUserDispenseAsset({
        tokenId: this.formData.tokenId
      }).then(() => {
        this.$emit('finished', {
          tokenId: this.formData.tokenId,
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
  .el-form-item.inline-item {
    display: inline-block;
    &.select-item {
      width: 38%;
    }
    &.input-item {
      width: 55%;
      .el-form-item__content {
        margin-left: 0 !important;
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
