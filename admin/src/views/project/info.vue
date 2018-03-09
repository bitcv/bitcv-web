<template>
  <el-row class="project-info">
    <el-col :span="16" :offset="2">
      <p><br></p>
      <el-form :model="form" ref="form" label-width="200px" style="margin-top: 30px;">
        <el-form-item label="项目中文名" required>
          <el-input v-model="form.nameCn" placeholder="请输入项目中文名"></el-input>
        </el-form-item>
        <el-form-item label="项目英文名" required>
          <el-input v-model="form.nameEn" placeholder="请输入项目英文名"></el-input>
        </el-form-item>
        <el-form-item label="项目主页" required>
          <el-input v-model="form.url" placeholder="请输入项目主页地址"></el-input>
        </el-form-item>
        <el-form-item label="项目logo" required>
          <el-upload action="" class="width-uploader">
            <img v-if="form.logo" :src="form.logo" class="img">
            <i v-else class="el-icon-plus width-uploader-icon"></i>
          </el-upload>
          <p>(建议128*128的png格式图片)</p>
        </el-form-item>
        <el-form-item label="项目横幅">
          <el-upload action="" class="rect-uploader">
            <img v-if="form.img" :src="form.img" class="img">
            <i v-else class="el-icon-plus rect-uploader-icon"></i>
          </el-upload>
          <p>(建议上传宽度为600像素的图片)</p>
        </el-form-item>
        <el-form-item label="一句话描述" required>
          <el-input v-model="form.desc" placeholder="请输入描述"></el-input>
        </el-form-item>
        <el-form-item label="项目标签">
          <el-tag type="warning" closable>数据资产</el-tag>
          <el-tag type="warning" closable>数据货币</el-tag>
          <el-input class="tag-input" placeholder="标签信息">
            <el-button slot="append"><i class="el-icon-plus"></i></el-button>
          </el-input>
        </el-form-item>
        <el-form-item label="项目成立时间" required>
          <el-date-picker type="date" placeholder="请选择"></el-date-picker>
        </el-form-item>
        <el-form-item label="项目简介" required>
          <el-input type="textarea" :rows="3" placeholder="请输入简介"></el-input>
        </el-form-item>
        <el-form-item label="项目白皮书" required>
          <el-upload action="" class="width-uploader">
            <img v-if="form.logo" :src="form.logo" class="img">
            <i v-else class="el-icon-plus width-uploader-icon"></i>
          </el-upload>
        </el-form-item>
        <el-form-item label="地区" required>
          <el-select v-model="form.area" placeholder="请选择"></el-select>
        </el-form-item>
        <el-form-item label="行业" required>
          <el-select v-model="form.area" placeholder="请选择"></el-select>
        </el-form-item>
        <el-form-item label="阶段" required>
          <el-select v-model="form.area" placeholder="请选择"></el-select>
        </el-form-item>
        <el-form-item label="融资开始日期">
          <el-date-picker type="date" placeholder="请选择"></el-date-picker>
        </el-form-item>
        <el-form-item label="融资结束日期">
          <el-date-picker type="date" placeholder="请选择"></el-date-picker>
        </el-form-item>
        <el-form-item label="融资阶段">
          <el-select v-model="form.area" placeholder="请选择"></el-select>
        </el-form-item>
        <el-form-item label="合约地址">
          <el-input placeholder="请输入ERC2.0合约地址"></el-input>
        </el-form-item>
        <el-form-item label="联系邮箱">
          <el-input placeholder="请输入联系邮箱"></el-input>
        </el-form-item>
        <el-form-item label="联系地址">
          <el-input placeholder="请输入联系地址"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button type="warning">提交修改</el-button>
        </el-form-item>
      </el-form>
    </el-col>
  </el-row>
</template>

<script>
export default {
  data () {
    return {
      form: {
        nameCn: '',
        nameEn: '',
        url: '',
        logo: '',
        img: '',
        desc: '',
        area: ''
      }
    }
  }
}
</script>

<style lang="scss">
.project-info {
  .width-uploader .el-upload {
    position: relative;
    overflow: hidden;
    background-color: #f5f5f5;
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
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

    & + .el-tag {
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
