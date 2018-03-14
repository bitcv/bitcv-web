<template>
  <div class="step1">
    <el-form v-if="!isUpload" :model="form" :rules="rules" ref="form" label-width="100px" class="form">
      <el-form-item label="" prop="type">
        <el-radio-group v-model="form.type">
          <el-radio :label="0">正式发放</el-radio>
          <el-radio :label="1">测试发放<small style="color: #ccc;">（测试时仅向前两个地址发放代币）</small></el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="智能合约地址" prop="address">
        <el-input v-model="form.address"></el-input>
      </el-form-item>
      <el-form-item label="上传发放地址">
        <!-- <el-button type="warning" class="btn-primary" @click="submit">点击上传</el-button> -->
        <el-upload
          class="upload-btn"
          action="https://0.0.0.8:8080"
          :before-upload="handleBefore"
          :on-success="handleSuccess"
          :on-error="handleError"
          :show-file-list="false">
          <el-button slot="trigger" type="warning" class="btn-primary">上传文件</el-button>
        </el-upload>
        <el-button type="warning" plain @click="fetch">获取模板</el-button>
      </el-form-item>
    </el-form>
    <div v-else>
      <h4>上传成功</h4>
      <el-table
        :data="list"
        style="width: 100%">
        <el-table-column
          prop="id"
          label="序号">
        </el-table-column>
        <el-table-column
          prop="address"
          label="地址">
        </el-table-column>
        <el-table-column
          prop="number"
          label="数量">
        </el-table-column>
        <el-table-column
          prop="status"
          label="状态">
        </el-table-column>
      </el-table>
      <footer class="footer">
        <el-row>
          <el-col :span="7">
            <h5>总数量</h5>
            <div>
              {{total.number}}
              <small>枚90990</small>
            </div>
          </el-col>
          <el-col :span="7">
            <h5>总地址</h5>
            <div>
              {{total.address}}
              <small>条</small>
            </div>
          </el-col>
          <el-col :span="10" class="text-center">
            <el-button type="warning" class="btn-primary" @click="confirm">确定</el-button>
            <el-button type="text" class="text-primary" @click="isUpload = false">重新上传</el-button>
          </el-col>
        </el-row>
      </footer>
    </div>
  </div>
</template>

<script>
import bus from '@/utils/bus'
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
        type: 0
      },
      rules: {
        address: [ {validator: validAddr} ],
        type: [{validator: valid}]
      },
      isUpload: false,
      list: [
        {
          id: 1,
          address: '0xsjksjdn',
          number: 3,
          status: 0
        }
      ],
      total: {
        number: 10000,
        address: 300
      }
    }
  },
  methods: {
    handleBefore (file) {
      const isExcel = /xls/.test(file.name)
      // if (!isExcel) {
      //   this.$message.error('请选择Excel文件')
      // }
      return isExcel
    },
    handleSuccess (res) {
      if (res.statusCode === 200) { // 成功时
        // 成功操作
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
      console.log('获取')
    },
    confirm () {
      this.$emit('finished', true)
      bus.$emit('handleEmit', this.form)
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
