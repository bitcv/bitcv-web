<template>
  <div class="container">
    <div id="share-box" v-show="showShare" class="share-container" @click="showShare = false">
      <share id="shareCard" :proj-detail="info"></share>
    </div>
    <!-- <div id="infoBox" class="info-box">
      <img :src="info.logoUrl" alt="">
      <div class="text-box">
        <div class="top-row">
          <span class="name-cn">{{ info.nameCn }}</span>
          <span class="name-en">({{ info.nameEn }})</span>
        </div>
        <div class="bottom-row">
          <span class="text-title">项目简称：</span>
          <span class="text">{{ info.tokenName }}</span>
          <span class="text-title">项目符号：</span>
          <span class="text">{{ info.tokenSymbol }}</span>
        </div>
      </div>
    </div> -->
    <div class="panel panel-custom">
      <div class="panel-body" style="padding:15px 30px 0;">
        <div class="row">
          <div class="col-md-6">
            <div class="media">
              <div class="media-left">
                <img :src="info.logoUrl" class="media-object img-rounded" width="100">
              </div>
              <div class="media-body">
                <h4>{{ info.nameCn}}</h4>
                <p>{{ $t('label.project_shortname') }}：<span class="text-primary">{{ info.nameEn }}</span></p>
                <p>{{ $t('label.project_s') }}：<span class="text-primary">{{ info.tokenSymbol }}</span></p>
              </div>
            </div>
            <p class="span-group" v-if="info.tagList && info.tagList.length > 1">
              <span class="text-dark">{{ $t('label.detail_tag') }}</span>
              <span v-for="(item, index) in info.tagList" :key="index">{{ item }}</span>
            </p>
          </div>
          <div class="col-md-6 md-mg-t">
            <div class="col-xs-6 col-md-8">
              <p><a :href="info.homeUrl" class="btn btn-default btn-outline btn-sm" target="_blank" style="width:160px;">{{ $t('label.project_home') }}</a></p>
              <div style="height: 20px;"></div>
              <p><a :href="info.whitePaperUrl" class="btn btn-default btn-outline btn-sm" target="_blank" style="width:160px;">{{ $t('label.white_paper') }}</a></p>
            </div>
            <div class="col-xs-6 col-md-4">
              <ul class="list-unstyled text-dark">
                <!--<li>-->
                  <!--<p>-->
                    <!--<span v-if="language === 'cn'" class="fixed-label">{{ ['关注', '取消关注'][info.focusStatus] }}&nbsp;&nbsp;</span>-->
                    <!--<span v-else class="fixed-label">{{ ['Follow', 'Unfollow'][info.focusStatus] }}&nbsp;&nbsp;</span>-->
                    <!--<a href="javascript:;" :style="info.focusStatus ? 'color:#f10808;': 'color:#999'" class="text-dark" @click="handleFocus">-->
                      <!--<span class="icon-bcv" :class="{'icon-heart': info.focusStatus == 0, 'icon-heart-fill': info.focusStatus == 1}"></span>-->
                    <!--</a>-->
                  <!--</p>-->
                <!--</li>-->
                <!--<li>-->
                  <!--<p>-->
                    <!--<span class="fixed-label">{{ $t('label.share') }}：</span>-->
                    <!--<a href="javascript:;" @click="openShare"><span class="icon-bcv icon-share"></span></a>-->
                  <!--</p>-->
                <!--</li>-->
                <!--<li>-->
                  <!--<p>-->
                    <!--<span class="fixed-label">{{ $t('label.focus') }}：</span>-->
                    <!--<span class="text-black fnum">{{ info.focusNum }}</span>-->
                  <!--</p>-->
                <!--</li>-->
                <li>
                  <p>
                    <span class="fixed-label">{{ $t('label.view') }}：</span>
                    <span class="text-black">{{ info.viewTimes }}</span>
                  </p>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="tab-bar">
      <div class="row">
        <!-- 社区链接 -->
        <div class="col-md-12 text-right hidden-xs hidden-sm">
          <ul class="list-inline text-darker">
            <li class="text-dark">{{ $t('label.social') }}</li>
            <li v-for="(item, index) in info.socialList" :key="index">
              <a :href="item.linkUrl" target="_blank">
                <span class="icon-bcv" :class="'icon-'+item.name"></span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="component-wrapper">
      <div class="row">
        <div class="col-md-8 hidden-xs hidden-sm">
          <ul v-if="language === 'cn'" class="nav nav-tabs">
            <li v-for="(item, index) in infoList" :class="{active: item.active}" :key="index">
              <a :href="item.target" @click="onTabItemClick(infoList, item)">{{ item.text }}</a>
            </li>
          </ul>
          <ul v-else-if="language === 'en'" class="nav nav-tabs">
            <li v-for="(item, index) in e_infoList" :class="{active: item.active}" :key="index">
              <a :href="item.target" @click="onTabItemClick(e_infoList, item)">{{ item.text }}</a>
            </li>
          </ul>
          <ul v-else class="nav nav-tabs">
            <li v-for="(item, index) in j_infoList" :class="{active: item.active}" :key="index">
              <a :href="item.target" @click="onTabItemClick(j_infoList, item)">{{ item.text }}</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="row outer">
        <div class="col-md-8 col">
          <div class="tab-content">
            <div id="info" class="panel-body">
              <h4 class="sub-title">{{ $t('label.project_intr') }}</h4>
              <div class="sub-content">
                <p>{{ info.shortDesc }}</p>
                <article class="text-darker">
                  <p v-if="info.bannerUrl"><img :src="info.bannerUrl" style="max-width:100%"></p>
                  <p>{{ info.abstract }}</p>
                </article>
              </div>
            </div><!-- /#info -->
            <div id="team" class="panel-body">
              <h4 class="sub-title">{{ $t('label.team_mem') }}</h4>
              <div class="sub-content">
                <swiper :options="swiperOption" ref="swiper">
                  <swiper-slide v-for="(item, index) in info.memberList" :key="index">
                    <div class="text-center">
                      <figure>
                        <p>
                          <img v-if="item.photoUrl" :src="item.photoUrl" class="img-circle">
                          <span v-else class="avatar-span">{{ item.name ? item.name.substr(0, 1) : '' }}</span>
                        </p>
                        <figcaption>{{ item.name }}</figcaption>
                      </figure>
                      <p class="desc">{{ item.intro}}</p>
                    </div>
                  </swiper-slide>
                  <div class="swiper-button-prev" slot="button-prev" @click="swiperPrev"><span class="icon-bcv icon-arrow-left"></span></div>
                  <div class="swiper-button-next" slot="button-next" @click="swiperNext"><span class="icon-bcv icon-arrow-right"></span></div>
                </swiper>
              </div>
              <div v-if="info.advisorList && info.advisorList.length">
                <h4 class="sub-title">{{ $t('label.project_advisor') }}</h4>
                <div class="sub-content">
                  <div class="row adviser-list">
                    <div class="col-xs-6 col-sm-4 col-md-3" v-for="(item, index) in info.advisorList" :key="index">
                      <div class="thumbnail text-center">
                        <div class="img img-circle" v-if="item.logoUrl" :style="{backgroundImage: `url(${item.logoUrl})`}"></div>
                        <span v-else class="avatar-span">{{ item.name ? item.name.substr(0, 1) : '' }}</span>
                        <div class="caption">
                          <div class="text-darker">
                            <span>{{ item.name }}</span><br>
                            <p class="text-ellipsis text-dark" style="margin:0;"><small>{{ item.intro }}</small></p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- /#team -->

            <!-- 项目发展 -->
            <div id="develop" class="panel-body" v-if="info.eventList && info.eventList.length">
              <h4 class="sub-title">{{ $t('label.project_dev') }}</h4>
              <div class="sub-content">
                <div class="timelime">
                  <div class="timelime-item" v-for="(item, index) in info.eventList" :key="index">
                    <p class="label">{{ item.eventKey }}</p>
                    <ul class="time-list">
                      <li class="item" v-for="(item2,i) in item.eventNode" :key="i">
                        <p class="time">{{ item2.time}}</p>
                        <p class="content">{{ item2.title}}</p>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div v-else id="develop" class="panel-body">
              <h4>{{ $t('label.project_dev') }}</h4>
              <div style="height: 82px;">
                <p style="text-align: center;margin-top: 42px; color: #A1A1A1">{{ $t('label.no_message') }}</p>
              </div>
            </div>

            <!-- 合作伙伴 -->
            <div id="partner" class="panel-body" v-if="info.partnerList && info.partnerList.length">
              <h4 class="sub-title">{{ $t('label.brother') }}</h4>
              <div class="sub-content">
                <div class="row partner-list">
                  <div class="col col-xs-6 col-sm-4 col-md-3" v-for="(item, index) in info.partnerList" :key="index">
                    <a class="partner-item" :title="item.title">
                      <img class="img-responsive" :src="item.logoUrl" :alt="item.name">
                    </a>
                  </div>
                </div>
              </div>
            </div>
            <div v-else id="partner" class="panel-body">
              <h4>{{ $t('label.brother') }}</h4>
              <div style="height: 82px;">
                <p style="text-align: center;margin-top: 42px; color: #A1A1A1">{{ $t('label.no_message') }}</p>
              </div>
            </div>

            <!-- 媒体报道 -->
            <!--<div id="media" class="panel-body" v-if="showList && showList.length > 0">-->
              <!--<h4 class="sub-title">{{ $t('label.media') }}</h4>-->
              <!--<div class="sub-content">-->
                <!--<ul class="list-unstyled media-list">-->
                  <!--<li class="clearfix" v-for="(report, index) in showList" :key="index">-->
                    <!--<p style="float: right;font-size:12px;color:#A1A1A1;margin-right:28px;">来自 {{ report.name }}</p>-->
                    <!--<p class="content"><span >{{ report.releaseTime | formatDate }}</span><a :href="report.linkUrl" target="_blank" class="more"><span class= "title" v-html="report.title"></span></a></p>-->
                  <!--</li>-->
                  <!--<div v-if="info.reportList.length > 3"  style="text-align:center;color: #A1A1A1;font-size:12px;" @click="show" class="show-more">{{word}}</div>-->
                <!--</ul>-->
              <!--</div>-->
            <!--</div>-->
            <!--<div v-else id="media" class="panel-body">-->
              <!--<h4>{{ $t('label.media') }}</h4>-->
              <!--<div style="height: 82px;">-->
                <!--<p style="text-align: center;margin-top: 42px; color: #A1A1A1">暂无信息</p>-->
              <!--</div>-->
            <!--</div>-->
            
            <!-- 官方公告 -->
            <!--<div id="offical" class="panel-body" v-if="info.publicList && info.publicList.length">-->
              <!--<h4 class="sub-title">{{ $t('label.anno') }}</h4>-->
              <!--<div class="sub-content">-->
                <!--<ul class="list-unstyled notice-list">-->
                  <!--<li class="clearfix" v-for="(notice, index) in info.publicList" :key="index">-->
                    <!--<p class="content"><span class="date">{{ notice.postTime | formatDate }}</span><a :href="notice.referUrl" target="_blank" class="more"><span style="margin-left:36px;" v-html="notice.title"></span></a></p>-->
                  <!--</li>-->
                <!--</ul>-->
              <!--</div>-->
            <!--</div>-->
            <!--<div v-else id="offical" class="panel-body">-->
              <!--<h4>{{ $t('label.anno') }}</h4>-->
              <!--<div style="height: 82px;">-->
                <!--<p style="text-align: center;margin-top: 42px; color: #A1A1A1">暂无信息</p>-->
              <!--</div>-->
            <!--</div>-->

            <!-- 社区发布 -->
            <div id="community" class="panel-body" v-if="info.dynamicList && info.dynamicList.length">
              <h4 class="sub-title">{{ $t('label.comm_anno') }}</h4>
              <div class="sub-content community">
                <div class="media" v-for="(social, index) in info.dynamicList" :key="index">
                  <div class="media-left">
                    <img :src="social.logoUrl" alt=""  class="media-object" width="40">
                  </div>
                  <div class="media-body text-darker">
                    <p class="media-heading">{{ social.officialName }}</p>
                    <p class="small text-dark">{{ social.postTime }} 来自 {{ social.name }}</p>
                    <p class="content"><span v-html="social.title"></span></p>
                    <!-- <p class="content">{{ social.title }}</p> -->
                    <a :href="social.referUrl" target="_blank" class="more">{{ $t('label.view_full') }}</a>
                  </div>
                </div>
              </div>
            </div>
            <div v-else id="community" class="panel-body">
              <h4>{{ $t('label.comm_anno') }}</h4>
              <div style="height: 82px;">
                <p style="text-align: center;margin-top: 42px; color: #A1A1A1">{{ $t('label.no_message') }}</p>
              </div>
            </div>

          </div>
        </div>
        <!-- <div class="col-md-4 col">
          <div class="panel-body text-darker score" style="padding-top: 0">
            <h4 style="text-align:center;font-weight:bold;">{{ "币威指数" }}</h4>
            <p class="text-center" style="margin-top: 37px;width:100%; height:155px;">
              <canvas id="redcircle" width="135" height="130"></canvas>
            </p>
          </div>
          <div>
            <p class="text-center" style="margin-left:16px; margin-right:15px;">
              <img :src="'/static/img/question.png'" style="height:12px;width:12px;vertical-align:middle;" alt="">
              <span style="font-size:12px;color:rgba(155,155,155,1);line-height:16px;text-align:center;margin-top:7px;margin-left:2px;margin-right:15px;">币威指数是根据项目动态更新频率产生的综合评分，我们将逐步完善更多评分选项。</span>
            </p>
          </div>
        </div> -->
        <!-- <div class="btn-panel">
          <h3 class="center-title panel-title">认领该公司</h3>
          <img src="/static/img/头像扫描@2x.png" alt="">
        </div> -->
        <!-- <div v-if="projDetail.companyTel || projDetail.companyEmail || projDetail.companyAddr" class="contact-panel">
          <h3 class="center-title panel-title">公司联系信息</h3>
          <div v-if="projDetail.companyTel" class="info-row">
            <img src="/static/img/tel@2x.png" alt="">
            <span class="text">{{ projDetail.companyTel }}</span>
          </div>
          <div v-if="projDetail.companyEmail" class="info-row">
            <img src="/static/img/email@2x.png" alt="">
            <span class="text">{{ projDetail.companyEmail }}</span>
          </div>
          <div v-if="projDetail.companyAddr" class="info-row">
            <img src="/static/img/addr@2x.png" alt="">
            <span class="text">{{ projDetail.companyAddr }}</span>
          </div>
        </div> -->
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import { swiper, swiperSlide } from "vue-awesome-swiper";
import { formatDate } from "@/utils/utils";
import Share from "@/components/share/Share";
import html2canvas from "html2canvas";
export default {
  props: {
    projDetail: Object
  },
  components: {
    swiper,
    swiperSlide,
    Share
  },
  data() {
    return {
      info: {},
      score: "",
      viewNum: {},
      word: "",
      reports: [],
      showList: [],
      ctx: "",
      swiperOption: {
        autoplay: true
      },
      showAll: false,
      showShare: false,
      shareUrl: "",
      infoList: [
        { text: "项目信息", target: "#info", active: true },
        { text: "团队信息", target: "#team", active: false },
        { text: "项目发展", target: "#develop", active: false },
        { text: "合作伙伴", target: "#partner", active: false },
        { text: "社区发布", target: "#community", active: false }
      ],
      e_infoList: [
        { text: "Project Information", target: "#info", active: true },
        { text: "Team Information", target: "#team", active: false },
        { text: "Project Development", target: "#develop", active: false },
        { text: "Cooperative Partner", target: "#partner", active: false },
        { text: "Community Release", target: "#community", active: false }
      ],
      j_infoList: [
        { text: "プロジェクトインフォ", target: "#info", active: true },
        { text: "チーム情報", target: "#team", active: false },
        { text: "プロジェクトロードマップ", target: "#develop", active: false },
        { text: "協力パートナー", target: "#partner", active: false },
        { text: "コミュニティお知らせ", target: "#community", active: false }
      ]
    };
  },
  filters: {
    formatDate(time) {
      let date = new Date(time);
      return formatDate(date, "yyyy-MM-dd");
    }
  },
  computed: {
    ...mapState({
      proId: state => state.route.params.id
    }),
    swiper() {
      return this.$refs.swiper.swiper;
    },
    address() {
      const { companyAddr, companyEmail } = this.info;
      if (companyAddr && companyEmail) {
        return `${companyEmail} ${companyAddr}`;
      }
      return "";
    },
    language() {
      return this.$i18n.locale;
    }
  },
  created() {
    //this.fetch();
  },
  mounted() {
    this.viewProjectd();
    this.getAver();
    this.getProDetail();
    //this.tips();
  },
  methods: {
    ...mapActions(["getProDetail", "viewProject", "getScore", "updateFocus"]),
    swiperPrev() {
      this.swiper.slidePrev();
    },
    swiperNext() {
      this.swiper.slideNext();
    },
    onTabItemClick(list, item) {
      list.map(item => {
        item.active = false;
      });
      item.active = true;
    },
    circleProgress(value,average){
      var canvas = document.getElementById("redcircle");  
      if (!canvas) return
      var context = canvas.getContext('2d');    
      var _this = $(canvas),
      value= value,// 当前百分比,数值
      average = Number(average),// 平均百分比
      color = "",// 进度条、文字样式
      maxpercent = 10,//最大百分比，可设置
      c_width = _this.width(),// canvas，宽度
      c_height =_this.height();// canvas,高度
      // 判断设置当前显示颜色
      if( value== maxpercent ){
          color="#ff8b13";
      }else if( value> average ){
          color="#ff8b13";
      }else{
          color="#ff8b13";
      }    // 清空画布
      context.clearRect(0, 0, c_width, c_height);    // 画初始圆
      context.beginPath();    // 将起始点移到canvas中心
      context.moveTo(c_width/2, c_height/2);    // 绘制一个中心点为（c_width/2, c_height/2），半径为c_height/2，起始点0，终止点为Math.PI * 2的 整圆
      context.arc(c_width/2, c_height/2, c_height/2, 0, Math.PI * 2, false);
      context.closePath();
      context.fillStyle = '#FFE4B5'; //填充颜色
      context.fill();    // 绘制内圆
      context.beginPath();
      context.strokeStyle = color;
      context.lineCap = 'round';
      context.closePath();
      context.fill();
      context.lineWidth = 10.0;//绘制内圆的线宽度
 
      function draw(cur){
          // 画内部空白 
          context.beginPath(); 
          context.moveTo(24, 24); 
          context.arc(c_width/2, c_height/2, c_height/2-10, 0, Math.PI * 2, true); 
          context.closePath(); 
          context.fillStyle = 'rgba(255,255,255,1)';  // 填充内部颜色
          context.fill();        // 画内圆
          context.beginPath();        // 绘制一个中心点为（c_width/2, c_height/2），半径为c_height/2-5不与外圆重叠，
          // 起始点-(Math.PI/2)，终止点为((Math.PI*2)*cur)-Math.PI/2的 整圆cur为每一次绘制的距离
          context.arc(c_width/2, c_height/2, c_height/2-5, -(Math.PI / 2), ((Math.PI * 2) * cur ) - Math.PI / 2, false);
          context.stroke();        //在中间写字 
          context.font = "16pt PingFangSC";  // 字体大小，样式
          context.fillStyle = color;  // 颜色
          context.textAlign = 'center';  // 位置
          context.textBaseline = 'middle'; 
          context.moveTo(c_width/2, c_height/2);  // 文字填充位置
          context.fillText(value, c_width/2, c_height/2-20);
          context.fillText("综合评分", c_width/2, c_height/2+20);
      }    
        // 调用定时器实现动态效果
        var timer=null,n=0;    
        function loadCanvas(nowT){
            timer = setInterval(function(){
                if(n>nowT){
                    clearInterval(timer);
                }else{
                    draw(n);
                    n += 0.01;
                }
            },15);
        }
        loadCanvas(value/10);
        timer=null;
    },
    /*画曲线*/
    drawCircle(circleObj) {
        var ctx = circleObj.ctx;
        ctx.beginPath();
        ctx.arc(circleObj.x, circleObj.y, circleObj.radius, circleObj.startAngle, circleObj.endAngle, false); //true表示逆时针绘画
        //设定曲线粗细度
        ctx.lineWidth = circleObj.lineWidth;
        //给曲线着色
        ctx.strokeStyle = circleObj.color;
        //连接处样式
        ctx.lineCap = 'round';
        //给环着色
        ctx.stroke();
        ctx.closePath();
    },
    circle(deg) {
      var canvas = document.getElementById('redcircle');
      var ctx = canvas.getContext("2d");
      var ctxt = canvas.getContext("2d");
      var width = canvas.width;
      var height = canvas.height;
      /*圆环中心文字*/
      ctx.font = "32px PingFangSC bold";
      ctx.fillStyle = '#FFD700';
      var ratioStr = deg;
      var str = '综合评分';
      var text = ctx.measureText(ratioStr);
      var txt = ctxt.measureText(str);
     
      ctxt.font = "12px PingFangSC";
      ctxt.fillStyle = '#FFD700';
      ctx.fillText(ratioStr, (150 - text.width) / 2,height/2 );
      ctxt.fillText(str, (180 - txt.width) / 2,height/2 + 92.48/4);
      /*底下的灰圆环*/
      var circleObj = {
          ctx: ctx,
          /*圆心*/
          x: width/2,
          y: height/2,
          /*半径*/
          radius: width/2-20.48/2,
          /*环的宽度*/
          lineWidth: 12.48
      }
      /*上面的渐变圆环*/
      circleObj.startAngle = 300 *Math.PI/180 ; //startAngle弧度制 从135度开始画
      /*结束的度数*/
      circleObj.endAngle =250 *Math.PI/180-Math.PI*2
      circleObj.color = '#FFEFD5';
      this.drawCircle(circleObj);
      /*有色的圆环*/
      /*开始的度数-从上一个结束的位置开始*/
      circleObj.startAngle = circleObj.endAngle;
          /*结束的度数*/
      circleObj.endAngle = circleObj.endAngle-(Math.PI*2*deg/100);
      var my_gradient=ctx.createLinearGradient(0,0,0,170);
      my_gradient.addColorStop(0,"#FFEC8B");
      my_gradient.addColorStop(0.5,"#FFD700");
      my_gradient.addColorStop(1,"#FFD700");
      circleObj.color = my_gradient
      this.drawCircle(circleObj);//6ff1c4
    },
    
    fetch() {
      this.getProDetail({ projId: this.proId }).then(
        (data = {}) => (this.info = data)
      );
    },
    getProDetail (){
      this.word = '更多'
      this.$http
        .post("/api/getProjDetail", {
          projId: this.proId
        })
        .then( res => {
          var resData = res.data;
          if (resData.errcode === 0){
            this.info = resData.data
            if (this.showAll == false){
              if (this.info.reportList.length > 3){
                for (var i=0; i< 3 ;i++){
                  this.showList.push(this.info.reportList[i])
                }
              }else{
                this.showList = this.info.reportList
              }
            }else{
              for (var i=0; i< 4 ;i++){
                this.showList.push(this.info.reportList[i])
              }
            }
          }
        })
    },
    show () {
      this.showAll = true
      
      if (this.showList.length == 3){
        this.showList.push(this.info.reportList[3])
        this.word = '收起'
      }else{
        this.showList = []
        for (var i=0; i< 3 ;i++){
          this.showList.push(this.info.reportList[i])
        }
        this.word = '更多'
      }
    },
    tips (){
      if(this.showAll == false){　　　　　　　　　　　//对文字进行处理
        this.word =  '展开全部'
      }else{
        this.word =  '收起'
      }
    },
    getAver (){
      this.$http
        .post("/api/getAverageScore", {
          id: this.proId
        })
        .then(res => {
          var resData = res.data;
          if (resData.errcode === 0) {
            this.score = resData.data.score;
            //this.circle(this.score)
            this.circleProgress(this.score,50)
          }
        });  
    },       
    viewProjectd() {
      this.viewProject({ projId: this.proId }).then(
        (data = {}) => (this.viewNum = data)
      );
    },
    handleFocus() {
      this.updateFocus({ projId: this.proId }).then(data => {
        // 修改关注状态
        this.$set(
          this.info,
          "focusStatus",
          this.info.focusStatus === 0 ? 1 : 0
        );
        // 修改关注数量
        let { focusNum } = this.info;
        this.$set(
          this.info,
          "focusNum",
          this.info.focusStatus === 0 ? focusNum - 1 : focusNum + 1
        );
      });
    },
    openShare: function() {
      this.showShare = true;
      if (this.shareUrl) return;
      this.$nextTick(() => {
        html2canvas(document.querySelector("#shareCard")).then(canvas => {
          document.querySelector("#shareBox").innerHTML = "";
          var url = canvas.toDataURL();
          this.shareUrl = url;
          var newImg = document.createElement("img");
          newImg.src = url;
          newImg.style.width = "90%";
          newImg.class = "share-img";
          document.querySelector("#shareBox").appendChild(newImg);
        });
      });
    },
    toggleFocus: function() {
      var userId = localStorage.getItem("userId");
      if (!userId) {
        return alert("请登录");
      }
      this.$http
        .post("/api/toggleFocus", {
          projId: this.projDetail.id
        })
        .then(res => {
          var resData = res.data;
          if (resData.errcode === 0) {
            this.projDetail.focusStatus = resData.data.status;
          }
        });
    }
  }
};
</script>

<style lang="scss" scoped>
@import "~@/styles/variables";
.creditRating {
  position: relative;
}
.arcBar {
  display: block;
  margin: 0 auto;
}
.creditScore {
  width: 165px;
  height: 190px;
  margin: 0 auto;
  margin-top: -190px;
  background-color: transparent;
}
.creditScore p {
  color: #fff;
  font-size: 16px;
  line-height: 40px;
  text-align: center;
}
.creditScore p img {
  width: 25px;
  margin-top: 10px;
}
.creditScore p .grade {
  margin-right: 5px;
}
.creditScore p .value {
  font-size: 54px;
}
.circle_process {
  position: relative;
  width: 199px;
  height: 200px;
}
.circle_process .wrapper {
  width: 100px;
  height: 200px;
  position: absolute;
  top: 0;
  overflow: hidden;
}
.circle_process .right {
  right: 0;
}
.circle_process .left {
  left: 0;
}
.circle_process .circle {
  width: 160px;
  height: 160px;
  border: 20px solid transparent;
  border-radius: 50%;
  position: absolute;
  top: 0;
  transform: rotate(-135deg);
}
.circle_process .rightcircle {
  border-top: 20px solid green;
  border-right: 20px solid green;
  right: 0;
  -webkit-animation: circle_right 5s linear infinite;
}
.circle_process .leftcircle {
  border-bottom: 20px solid green;
  border-left: 20px solid green;
  left: 0;
  -webkit-animation: circle_left 5s linear infinite;
}
@-webkit-keyframes circle_right {
  0% {
    -webkit-transform: rotate(-135deg);
  }
  50%,
  100% {
    -webkit-transform: rotate(45deg);
  }
}
@-webkit-keyframes circle_left {
  0%,
  50% {
    -webkit-transform: rotate(-135deg);
  }
  100% {
    -webkit-transform: rotate(45deg);
  }
}
.span-group {
  margin-top: 15px;
  margin-bottom: 15px;
  > span {
    display: inline-block;
    vertical-align: middle;
    & + span {
      margin-left: 10px;
    }
  }
}
.tab-bar {
  .tab-item {
    display: inline-block;
    margin-left: 30px;
    margin-right: 20px;
    padding-bottom: 15px;
    color: $gray-dark;
    border-bottom: 2px solid transparent;
    &:hover {
      color: $primary-color;
    }
    &.active {
      color: $primary-color;
      border-color: $primary-color;
    }
  }
}
.fixed-label {
  display: inline-block;
  vertical-align: middle;
  width: 80px;
  text-align: right;
}
.component-wrapper {
  background-color: #fff;
  > .outer {
    margin: 0;
    > .col {
      padding: 0;
    }
  }
  .nav-tabs {
    > li {
      > a {
        padding: 10px 0;
        margin-left: 30px;
        color: $gray-dark;
        border: none;
        border-radius: 0;
        border-bottom: 2px solid transparent;
        &:hover,
        &:focus {
          color: $primary-color;
          background-color: transparent;
        }
      }
      &.active > a {
        color: $black-light;
        border-bottom-color: $primary-color;
      }
    }
  }
  .sub-title {
    margin-bottom: 15px;
  }
  .swiper-slide {
    figure {
      img {
        width: 120px;
        height: 120px;
      }
      figcaption {
        font-size: 16px;
        margin: 20px 0;
      }
    }
    .desc {
      font-size: 14px;
      text-align: left;
      line-height: 1.5;
      color: $gray-dark;
    }
  }
  .swiper-button-prev,
  .swiper-button-next {
    background-image: none;
    width: 30px;
    height: 30px;
    top: 30%;
    line-height: 25px;
    font-size: 24px;
    text-align: center;
    color: #fff;
    background-color: $gray;
    border-radius: 50%;
    &:hover {
      background-color: $primary-color;
    }
  }
  .swiper-button-prev {
    left: 20px;
  }
  .swiper-button-next {
    right: 20px;
  }
  .adviser-list {
    .thumbnail {
      border-radius: 0;
      > .img {
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
      }
      > .img,
      > .avatar-span {
        width: 100px;
        height: 100px;
        margin: 15px auto 10px;
      }
      .avatar-span {
        position: relative;
        display: block;
        line-height: 100px;
        font-size: 30px;
        color: $gray-darker;
        text-align: center;
        text-transform: uppercase;
        background-color: $gray-light;
        border-radius: 50%;
      }
      .caption > p {
        margin: 0;
      }
    }
  }
  .partner-list {
    margin-left: -5px;
    margin-right: -5px;
    > .col {
      position: relative;
      padding-left: 5px;
      padding-right: 5px;
      padding-bottom: 80px;
      margin-bottom: 10px;
      img {
        max-width: 100%;
        max-height: 100%;
      }
    }
    .partner-item {
      display: flex;
      position: absolute;
      top: 0;
      right: 5px;
      bottom: 0;
      left: 5px;
      align-items: center;
      justify-content: center;
      // border: 1px solid $gray;
    }
  }
}
.timelime {
  position: relative;
  padding-top: 20px;
  overflow: hidden;
  &:before {
    content: "";
    position: absolute;
    width: 1px;
    top: 0;
    left: 160px;
    bottom: 10px;
    background-color: $gray;
  }
  .timelime-item {
    .label {
      display: block;
      width: 140px;
      padding: 0;
      margin: 0;
      font-size: 16px;
      font-weight: normal;
      white-space: nowrap;
      color: $primary-color;
      text-align: right;
    }
    .time-list {
      position: relative;
      margin-top: 10px;
      margin-bottom: 20px;
      list-style-type: none;
      padding-left: 0;
      .item {
        padding-left: 180px;
        color: $gray-dark;
        .time {
          position: absolute;
          width: 140px;
          white-space: nowrap;
          left: 0;
          text-align: right;
          &:after {
            content: "";
            position: absolute;
            width: 10px;
            height: 10px;
            top: 50%;
            right: -25px;
            margin-top: -4px;
            background-color: $gray;
            border: 2px solid #fff;
            border-radius: 50%;
          }
        }
      }
    }
  }
}
.notice-list {
  li {
    line-height: 2;
    > .date {
      float: right;
      width: 120px;
      color: $gray-dark;
    }
    .content {
      margin-right: 120px;
      width: 85%;
      color: $gray-darker;
      white-space: nowrap;
      text-overflow: ellipsis;
      overflow: hidden;
    }
  }
}
.media-list {
  li {
    line-height: 2;
    > .date {
      width: 120px;
      font-size: 12px;
      color: #a1a1a1;
    }
    .content {
      width: calc(100% - 110px);
      color: $gray-darker;
      white-space: nowrap;
      text-overflow: ellipsis;
      overflow: hidden;
    }
    .title {
      margin-left: 36px;
      margin-right: 36px;
    }
    .source {
      font-size: 12px;
      color: #a1a1a1;
    }
  }
  .show-more {
    cursor: pointer;
    color: red;
  }
}
.score {
  border-bottom: 1px dashed $gray-light;
  margin-left: 20px;
  margin-right: 20px;
}
// .score {
//   width:2450px;
//   height:600px;
//   line-height:30px;
//   background:rgba(255,255,255,1);
//   // box-shadow: 1px 1px 1px 1px red;
//   //border-style:solid;
//   border-width:1px;
//   border-radius: 6px ;
//   font-size: 48px;
//   color: #FC9E3C;
//   padding: 5px 20px;
// }
.community .media {
  border-bottom: 1px dashed $gray-light;
  margin-top: 16px;
  .content {
    // letter-spacing:1px;
    line-height: 24px;
    letter-spacing: 1px;
    font-size: 14px;
    width: 80%;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 5;
    -webkit-box-orient: vertical;
    > a.more {
      float: right;
      margin-right: 16px;
      color: $primary-color;
    }
  }
  .media-heading {
    font-weight: bold;
  }
  .more {
    float: right;
    margin-right: 16px;
    margin-bottom: 16px;
  }
}
@media (min-width: 992px) {
  .component-wrapper {
    > .outer {
      display: flex;
      flex-direction: row;
      > .col {
        position: relative;
        float: none;
        &:first-child:after {
          content: "";
          position: absolute;
          width: 2px;
          right: 0;
          top: 0;
          bottom: 0;
          background-color: $gray-light;
        }
      }
    }
    .sub-content {
      padding-left: 20px;
    }
    .swiper-slide {
      figure {
        img {
          width: 150px;
          height: 150px;
        }
        figcaption {
          font-size: 18px;
        }
      }
      .desc {
        font-size: 16px;
      }
    }
    .swiper-button-prev {
      left: 50px;
    }
    .swiper-button-next {
      right: 50px;
    }
  }
}
.avatar-span {
  width: 150px;
  height: 150px;
  position: relative;
  display: block;
  line-height: 150px;
  font-size: 30px;
  color: #666;
  text-align: center;
  text-transform: uppercase;
  background-color: #e5e5e5;
  border-radius: 50%;
  margin: 0 auto;
}
.share-box {
  height: 46px;
  display: flex;
  position: absolute;
  right: 40px;
  bottom: 0;
  align-items: center;
  font-size: 14px;
  line-height: 20px;
  color: #000;
  cursor: pointer;
  img {
    width: 23px;
    height: 23px;
    margin: 0 5px;
  }
}
.share-container {
  width: 400px;
  position: fixed;
  z-index: 10;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  cursor: pointer;
  margin: auto;
  display: flex;
  justify-content: center;
  align-items: center;
  & > img {
    width: 100%;
  }
}
</style>
