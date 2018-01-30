<template>
  <div class="basic">
    <div class="form-container">
      <el-form label-position="left" label-width="100px">
        <el-form-item label="项目中文名">
          <el-input></el-input>
        </el-form-item>
        <el-form-item label="项目英文名">
          <el-input></el-input>
        </el-form-item>
        <el-form-item label="项目主页">
          <el-input>
            <template slot="prepend">http://</template>
          </el-input>
        </el-form-item>
        <el-form-item label="项目logo">
          <el-upload class="upload-box" action="" :auto-upload="false" :on-change="onChange" :show-file-list="false">
            <i class="el-icon-plus"></i>
            <img :src="imageUrl" alt="">
          </el-upload>
          <input type="file" @change="onChange2">
        </el-form-item>
        <el-form-item label="项目头图">
          <el-upload class="upload-box" action="" :auto-upload="false" :on-change="onChange" :show-file-list="false">
            <i class="el-icon-plus"></i>
            <img :src="imageUrl" alt="">
          </el-upload>
        </el-form-item>
        <el-form-item label="一句话描述">
          <el-input></el-input>
        </el-form-item>
        <el-form-item class="tag-item" label="项目标签">
          <el-tag class="tag-text" :key="tag" v-for="(tag, index) in tagList" closable :disable-transitions="false" @close="removeTag(index)"> {{tag}} </el-tag>
          <el-input v-if="tagInputVisible" class="tag-input" ref="tagInput" v-model="inputTag" size="small" @keyup.enter.native="addTag" @blur="addTag" >
          </el-input>
          <el-button v-else class="tag-btn" size="small" @click="showTagInput">+ 添加标签</el-button>
        </el-form-item>
        <el-form-item label="项目简介">
          <el-input type="textarea"></el-input>
        </el-form-item>
        <el-form-item label="融资时间">
          <el-date-picker type="datetimerange" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期">
          </el-date-picker>
        </el-form-item>
      </el-form>
    </div>
    <div class="footer-btn-area">
      <router-link to="event">
        <el-button type="primary">下一步</el-button>
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      imageUrl: '',
      inputTag: '',
      tagList: [],
      tagInputVisible: false
    }
  },
  methods: {
    onChange (data) {
      console.log('onChange')
      console.log(file)
      var file = data.raw
      var param = new FormData()
      param.append('file',file,file.name);
    },
    showTagInput() {
      this.tagInputVisible = true
      this.$nextTick(_ => {
        this.$refs.tagInput.$refs.input.focus()
      })
    },
    addTag () {
      if (this.inputTag) {
        this.tagList.push(this.inputTag)
      }
      this.tagInputVisible = false
      this.inputTag = ''
    },
    removeTag (index) {
      this.tagList.splice(index, 1)
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
.upload-box {
  position: relative;
  width: 100px;
  height: 100px;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
  border: 1px solid #4A4A4A;
  border-radius: 5px;
  box-sizing: border-box;
  img {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
  }
}
</style>
