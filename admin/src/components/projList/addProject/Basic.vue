<template>
  <div class="basic">
    <div class="form-container">
      <el-form label-position="left" label-width="100px">
        <el-form-item label="项目中文名">
          <el-input v-model="projData.nameCn" placeholder="请输入项目中文名" required></el-input>
        </el-form-item>
        <el-form-item label="项目英文名">
          <el-input v-model="projData.nameEn" placeholder="请输入项目英文名" required></el-input>
        </el-form-item>
        <el-form-item label="项目主页">
          <el-input v-model="projData.webUrl">
            <template slot="prepend" placeholder="请输入项目官网地址">http://</template>
          </el-input>
        </el-form-item>
        <el-form-item label="项目logo">
          <el-upload class="upload-box" name="logo" action="/api/uploadFile" :on-success="onLogoSuccess" :show-file-list="false">
            <i class="el-icon-plus"></i>
            <img :src="projData.logoUrl" alt="">
          </el-upload>
        </el-form-item>
        <el-form-item label="项目头图">
          <el-upload class="upload-box" name="picture" action="/api/uploadFile" :on-success="onBannerSuccess" :show-file-list="false">
            <i class="el-icon-plus"></i>
            <img :src="projData.bannerUrl" alt="">
          </el-upload>
        </el-form-item>
        <el-form-item label="一句话描述">
          <el-input v-model="projData.desc" placeholder="请输入项目的一句话概括"></el-input>
        </el-form-item>
        <el-form-item class="tag-item" label="项目标签">
          <el-tag class="tag-text" :key="index" v-for="(tag, index) in projData.tagList" closable :disable-transitions="false" @close="removeTag(index)"> {{tag}} </el-tag>
          <el-input v-if="tagInputVisible" class="tag-input" ref="tagInput" v-model="inputTag" size="small" @keyup.enter.native="addTag" @blur="addTag" >
          </el-input>
            <el-button v-else class="tag-btn" size="small" @click="showTagInput">+ 添加标签</el-button>
        </el-form-item>
        <el-form-item label="项目简介">
          <el-input type="textarea" v-model="projData.abstract" placeholder="请输入项目简介"></el-input>
        </el-form-item>
        <el-form-item label="项目白皮书">
          <el-upload class="upload-box" name="whitePaper" action="/api/uploadFile" :on-success="onWhitePaperSuccess" :show-file-list="false">
            <i class="el-icon-plus"></i>
            <img :src="projData.whitePaperUrl" alt="">
          </el-upload>
        </el-form-item>
        <el-form-item label="融资开始时间">
          <el-date-picker v-model="projData.startTime" type="datetime" default-value="2017-01-01T00:00:00" placeholder="请选择融资开始日期">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="融资结束时间">
          <el-date-picker v-model="projData.endTime" type="datetime" default-value="2017-01-01T00:00:00" placeholder="请选择融资结束日期">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="代币名称">
          <el-input v-model="projData.tokenName" placeholder="请输入代币名称"></el-input>
        </el-form-item>
        <el-form-item label="代币符号">
          <el-input v-model="projData.tokenSymbol" placeholder="请输入代币符号"></el-input>
        </el-form-item>
      </el-form>
    </div>
    <div class="footer-btn-area">
      <el-button type="primary" @click="nextStep">下一步</el-button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    projData: Object
  },
  data () {
    return {
      inputTag: '',
      tagList: [],
      tagInputVisible: false
    }
  },
  methods: {
    onLogoSuccess (res) {
      if (res.errcode === 0) {
        this.projData.logoUrl = res.data.url
      }
    },
    onBannerSuccess (res) {
      if (res.errcode === 0) {
        this.projData.bannerUrl = res.data.url
      }
    },
    onWhitePaperSuccess (res) {
      if (res.errcode === 0) {
        this.projData.whitePaperUrl = res.data.url
      }
    },
    showTagInput () {
      this.tagInputVisible = true
      this.$nextTick(_ => {
        this.$refs.tagInput.$refs.input.focus()
      })
    },
    addTag () {
      if (this.inputTag) {
        this.projData.tagList.push(this.inputTag)
      }
      this.tagInputVisible = false
      this.inputTag = ''
    },
    removeTag (index) {
      this.tagList.splice(index, 1)
    },
    nextStep () {
      this.$emit('updateData', {
        index: 1,
        projData: this.projData
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.form-container {
  padding: 0 20px;
  .tag-item {
    .tag-text {
      margin-right: 10px;
    }
    .tag-input {
      width: 90px;
    }
  }
}
</style>
