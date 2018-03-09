<template>
  <el-row class="project-info" v-loading="loading">
    <el-col :span="16" :offset="2">
      <p><br></p>
      <el-form :model="form" ref="form" :rules="rules" label-width="200px" style="margin-top: 30px;">
        <el-form-item label="项目中文名" prop="nameCn" required>
          <el-input v-model="form.nameCn" placeholder="请输入项目中文名"></el-input>
        </el-form-item>
        <el-form-item label="项目英文名" prop="nameEn" required>
          <el-input v-model="form.nameEn" placeholder="请输入项目英文名"></el-input>
        </el-form-item>
        <el-form-item label="项目主页" prop="homeUrl" required>
          <el-input v-model="form.homeUrl" placeholder="请输入项目主页地址"></el-input>
        </el-form-item>
        <el-form-item label="项目logo" prop="logoUrl" required>
          <el-upload
            class="upload-box width-uploader"
            name="picture"
            action="/api/uploadFile"
            :on-progress="(...args) => {handleProgress.apply(this, ['logoLoading', ...args])}"
            :on-success="(...args) => {handleSuccess.apply(this, ['logoUrl', 'logoLoading', ...args])}"
            :on-error="(...args) => {handleError.apply(this, ['logoLoading', ...args])}"
            :show-file-list="false"
            v-loading="logoLoading">
              <img v-if="form.logoUrl" :src="form.logoUrl" class="img width-img">
              <i v-else class="el-icon-plus width-uploader-icon"></i>
          </el-upload>
          <p>(建议128*128的png格式图片)</p>
        </el-form-item>
        <el-form-item label="项目横幅" prop="bannerUrl">
          <el-upload
            class="upload-box rect-uploader"
            name="picture"
            action="/api/uploadFile"
            :on-progress="(...args) => {handleProgress.apply(this, ['bannerLoading', ...args])}"
            :on-success="(...args) => {handleSuccess.apply(this, ['bannerUrl', 'bannerLoading', ...args])}"
            :on-error="(...args) => {handleError.apply(this, ['bannerLoading', ...args])}"
            :show-file-list="false"
            v-loading="bannerLoading">
              <img v-if="form.bannerUrl" :src="form.bannerUrl" class="img">
              <i v-else class="el-icon-plus width-uploader-icon"></i>
          </el-upload>
          <p>(建议上传宽度为600px的图片)</p>
        </el-form-item>
        <el-form-item label="一句话描述" prop="shortDesc" required>
          <el-input v-model="form.shortDesc" placeholder="请输入描述"></el-input>
        </el-form-item>
        <el-form-item label="项目标签" prop="inputTag">
          <el-tag type="warning" :key="index" v-for="(tag, index) in form.tagList" closable :disable-transitions="false" @close="handleRemoveTag(index)"> {{tag}} </el-tag>
          <el-input class="tag-input" v-model="inputTag" placeholder="标签信息">
            <el-button slot="append" @click="handleAddTag"><i class="el-icon-plus"></i></el-button>
          </el-input>
        </el-form-item>
        <el-form-item label="项目成立时间" prop="foundDate" required>
          <el-date-picker type="date" v-model="form.foundDate" placeholder="请选择"></el-date-picker>
        </el-form-item>
        <el-form-item label="项目简介" prop="abstract" required>
          <el-input type="textarea" :autosize="{ minRows: 3, maxRows: 6}" v-model="form.abstract" placeholder="请输入项目简介"></el-input>
        </el-form-item>
        <el-form-item label="项目白皮书" prop="whitePaperUrl" required>
          <el-upload
            class="upload-box width-uploader"
            name="picture"
            action="/api/uploadFile"
            :on-progress="(...args) => {handleProgress.apply(this, ['whiteLoading', ...args])}"
            :on-success="(...args) => {handleSuccess.apply(this, ['whitePaperUrl', 'whiteLoading', ...args])}"
            :on-error="(...args) => {handleError.apply(this, ['whiteLoading', ...args])}"
            :show-file-list="false"
            v-loading="whiteLoading">
              <img v-if="form.whitePaperUrl" :src="form.whitePaperUrl ? '/storage/static/pdf.jpg' : ''" class="img">
              <i v-else class="el-icon-plus width-uploader-icon"></i>
          </el-upload>
        </el-form-item>
        <el-form-item label="地区" prop="region" required>
          <el-select v-model="form.region" placeholder="请选择">
            <el-option v-for="(op, index) in options.region" :key="index" :label="op.label" :value="op.value"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="行业" prop="buzType" required>
          <el-select v-model="form.buzType" placeholder="请选择">
            <el-option v-for="(op, index) in options.buzType" :key="index" :label="op.label" :value="op.value"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="阶段" prop="stage" required>
          <el-select v-model="form.stage" placeholder="请选择">
            <el-option v-for="(op, index) in options.stage" :key="index" :label="op.label" :value="op.value"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="融资开始日期" prop="fundStartTime">
          <el-date-picker v-model="form.fundStartTime" type="date" placeholder="请选择"></el-date-picker>
        </el-form-item>
        <el-form-item label="融资结束日期" prop="fundEndTime">
          <el-date-picker v-model="form.fundEndTime" type="date" placeholder="请选择"></el-date-picker>
        </el-form-item>
        <el-form-item label="融资阶段" prop="fundStage">
          <el-select v-model="form.fundStage">
            <el-option label="保密" :value="0"></el-option>
            <el-option label="未融资" :value="1"></el-option>
            <el-option label="融资中" :value="2"></el-option>
            <el-option label="已融资" :value="3"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="合约地址" prop="contractAddr">
          <el-input v-model="form.contractAddr" placeholder="请输入ERC20合约地址"></el-input>
        </el-form-item>
        <el-form-item label="联系邮箱" prop="companyEmail">
          <el-input type="email" v-model="form.companyEmail" placeholder="请输入联系邮箱"></el-input>
        </el-form-item>
        <el-form-item label="联系地址" prop="companyAddr">
          <el-input v-model="form.companyAddr" placeholder="请输入联系地址"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button type="warning" @click="handleSubmit">提交修改</el-button>
        </el-form-item>
      </el-form>
    </el-col>
  </el-row>
</template>

<script>
import {mapState, mapActions} from 'vuex'
export default {
  data () {
    let valid = (rule, val, callback) => {
      if (val === '' && val === undefined) {
        callback(new Error('必填'))
      }
      callback()
    }
    return {
      loading: false,
      logoLoading: false,
      bannerLoading: false,
      whiteLoading: false,
      form: {
        tagList: []
      },
      rules: {
        nameCn: [ {validator: valid} ],
        nameEn: [ {validator: valid} ],
        homeUrl: [ {validator: valid} ],
        logoUrl: [ {validator: valid} ],
        shortDesc: [ {validator: valid} ],
        foundDate: [ {validator: valid} ],
        whitePaperUrl: [ {validator: valid} ],
        abstract: [ {validator: valid} ],
        region: [ {validator: valid} ],
        buzType: [ {validator: valid} ],
        stage: [ {validator: valid} ]
      },
      inputTag: '',
      options: {
        region: [],
        buzType: [],
        stage: []
      }
    }
  },
  computed: {
    ...mapState({
      userInfo: state => state.userInfo
    })
  },
  created () {
    this.fetch()
  },
  methods: {
    ...mapActions(['getProjBasicInfo', 'getProjTagList', 'updProjBasicInfo']),
    fetch () {
      this.loading = true
      this.getProjBasicInfo({projId: this.userInfo.userId})
        .then((data = {}) => {
          this.form = data
          this.loading = false
        })
        .catch(() => {
          this.loading = true
        })
      this.fetchTags()
    },
    fetchTags () {
      this.getProjTagList({projId: this.userInfo.userId})
        .then((data = {}) => {
          this.options.region = data.region.optionList
          this.options.buzType = data.buzType.optionList
          this.options.stage = data.stage.optionList
        })
    },
    handleAddTag () {
      this.inputTag && this.form.tagList.push(this.inputTag)
    },
    handleRemoveTag (index) {
      this.form.tagList.splice(index, 1)
    },
    handleSuccess (url, loading, res, ...args) {
      if (res.errcode === 0) this.form[url] = res.data.url
      this[loading] = false
    },
    handleProgress (loading, ...args) {
      this[loading] = true
    },
    handleError (loading, ...args) {
      this[loading] = false
      this.$message.warning('图片上传失败，请重试')
    },
    handleSubmit () {
      this.$refs.form.validate(valid => {
        if (valid) {
          this.form.projId = this.userInfo.userId
          this.loading = true
          this.updProjBasicInfo(this.form)
            .then((data = {}) => {
              console.log(data)
              this.$message.success('修改成功!')
              this.loading = false
              this.fetch()
            })
            .catch(() => {
              this.loading = false
            })
        } else {
          this.$message.error('表单项填写不正确，请检查！')
          return false
        }
      })
    }
  }
}
</script>

<style lang="scss">
.project-info {
  .width-uploader{
    width: 100px;
  }
  .width-uploader .el-upload {
    position: relative;
    overflow: hidden;
    background-color: #f5f5f5;
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    width: 100%;
    cursor: pointer;
  }
  .width-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .width-uploader .width-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 100px;
    height: 100px;
    line-height: 100px;
    text-align: center;
  }
  .width-uploader .img {
    width: 100px;
    height: 100px;
    display: block;
  }
  .width-uploader .width-img{
    width: 100%;
  }
  .rect-uploader .el-upload {
    position: relative;
    overflow: hidden;
    background-color: #f5f5f5;
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
  }
  .rect-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .rect-uploader .rect-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 300px;
    height: 100px;
    line-height: 100px;
    text-align: center;
  }
  .rect-uploader .img {
    width: 300px;
    height: 100px;
    display: block;
  }

  .el-tag {
    position: relative;
    margin-right: 10px;

    .el-tag__close {
      position: absolute;
      color: #fff;
      top: -6px;
      right: -6px;
      background-color: #ec787a;
    }
  }

  .tag-input {
    width: 160px;

    .el-input-group__append {
      padding: 0 10px;
      font-size: 18px;
      color: #fff;
      background-color: #fbac43;
      border-color: #fbac43;
    }
  }
}
</style>
