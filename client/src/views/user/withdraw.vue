<template>
  <div>
    <poptips v-model="showTips" message="提示：本次服务费由平台承担" style="top:51px;"></poptips>

    <div class="container">
      <p><br></p>
      <p><br></p>
      <p><br></p>
      <p><br></p>
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <form class="form-horizontal">
            <div class="form-group no-corner">
              <label class="control-label col-md-4">收款地址:</label>
              <div class="col-md-8">
                <input type="text" class="form-control">
              </div>
            </div>
            <div class="form-group no-corner">
              <label class="control-label col-md-4">输入手机号:</label>
              <div class="col-md-8">
                <div class="input-group">
                  <input type="text" class="form-control">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-gray-light">获取验证码</button>
                  </span>
                </div>
              </div>
            </div>
            <div class="form-group no-corner">
              <label class="control-label col-md-4">输入手机验证码:</label>
              <div class="col-md-8">
                <input type="text" class="form-control">
              </div>
            </div>
            <p><br></p>
            <p><br></p>
            <div class="form-group">
              <div class="col-md-8 col-md-offset-4">
                <button type="button" class="btn btn-primary" style="padding:6px 20px;">提交</button>
                <button type="button" class="btn btn-gray" style="margin-left:20px;padding:6px 20px;color:#fff;">取消</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapState} from 'vuex'
import Poptips from '@/components/poptips'

export default {
  components: {
    Poptips
  },
  computed: {
    ...mapState({
      id: state => state.route.params.id
    })
  },
  data () {
    return {
      showTips: true
    }
  }
}
</script>
