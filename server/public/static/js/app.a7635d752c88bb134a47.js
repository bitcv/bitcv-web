webpackJsonp([40],{CaK3:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a={components:{Popside:n("GIbw").default},props:{hasToken:Boolean},data:function(){return{showSide:!1,showDropdown:!1}},methods:{navbarToggle:function(){this.showSide=!this.showSide}},mounted:function(){this.showSide=!1,this.showDropdown=!1}},o={render:function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("nav",{staticClass:"navbar navbar-inverse navbar-static-top"},[n("div",{staticClass:"container"},[n("div",{staticClass:"navbar-header"},[n("button",{staticClass:"navbar-toggle",class:{collapsed:!t.showSide},attrs:{type:"button"},on:{click:t.navbarToggle}},[n("span",{staticClass:"sr-only"},[t._v("Toggle navigation")]),t._v(" "),n("span",{staticClass:"icon-bar"}),t._v(" "),n("span",{staticClass:"icon-bar"}),t._v(" "),n("span",{staticClass:"icon-bar"}),t._v(" "),n("span",{staticClass:"icon-bar"})]),t._v(" "),n("router-link",{staticClass:"navbar-brand",attrs:{to:"/"}},[n("img",{attrs:{src:"/static/img/logo.png",alt:"BitCV",height:"30"}})])],1),t._v(" "),n("popside",{attrs:{"has-token":t.hasToken},on:{signout:function(e){t.$emit("signout")}},model:{value:t.showSide,callback:function(e){t.showSide=e},expression:"showSide"}}),t._v(" "),t.hasToken?n("div",{staticClass:"dropdown navbar-right hidden-sm hidden-xs",class:{open:t.showDropdown},on:{mouseenter:function(e){t.showDropdown=!0},mouseleave:function(e){t.showDropdown=!1}}},[t._m(0),t._v(" "),n("ul",{staticClass:"dropdown-menu"},[n("li",[n("router-link",{attrs:{to:"/wallet"}},[t._v("我的资产")])],1),t._v(" "),n("li",[n("a",{attrs:{href:"javascript:;"},on:{click:function(e){t.$emit("signout")}}},[t._v("注销登录")])])])]):n("div",{staticClass:"navbar-right hidden-sm hidden-xs"},[n("router-link",{staticClass:"btn navbar-btn btn-default btn-outline",attrs:{to:"/signup"}},[t._v("注册")]),t._v(" "),n("span",[t._v("  ")]),t._v(" "),n("router-link",{staticClass:"btn navbar-btn btn-default btn-outline",attrs:{to:"/signin"}},[t._v("登录")])],1)],1)])},staticRenderFns:[function(){var t=this.$createElement,e=this._self._c||t;return e("a",{staticClass:"dropdown-toggle",attrs:{href:"javascrip:;"}},[e("img",{staticClass:"img-circle",attrs:{src:"/static/img/avatar.png"}})])}]};var r=n("VU/8")(a,o,!1,function(t){n("vj4M")},null,null);e.default=r.exports},GIbw:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a={props:{value:{type:Boolean,default:!1},hasToken:Boolean},data:function(){return{show:!1}},watch:{value:{handler:function(t){this.show=t},immediate:!0}},methods:{toggle:function(){this.$emit("input",!this.show)},signout:function(){this.$emit("signout"),this.toggle()}}},o={render:function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"popside",class:{"popside-show":t.show}},[n("div",{staticClass:"popside-mask",on:{click:t.toggle}}),t._v(" "),n("div",{staticClass:"popside-container"},[t.hasToken?n("img",{staticClass:"popside-avatar",attrs:{src:"/static/img/avatar.png"}}):n("router-link",{staticClass:"popside-avatar hidden-sm",attrs:{to:"/signin"},nativeOn:{click:function(e){t.toggle(e)}}},[t._v("登录")]),t._v(" "),n("ul",{staticClass:"nav navbar-nav popside-nav"},[n("router-link",{attrs:{tag:"li","active-class":"active",exact:"",to:"/"},nativeOn:{click:function(e){t.toggle(e)}}},[n("a",{attrs:{href:"javascript:;"}},[t._v("主页")])]),t._v(" "),n("router-link",{attrs:{tag:"li","active-class":"active",to:"/discover"},nativeOn:{click:function(e){t.toggle(e)}}},[n("a",{attrs:{href:"javascript:;"}},[t._v("发现")])]),t._v(" "),n("router-link",{attrs:{tag:"li","active-class":"active",to:"/candyRoom"},nativeOn:{click:function(e){t.toggle(e)}}},[n("a",{attrs:{href:"javascript:;"}},[t._v("余币宝")])]),t._v(" "),t.hasToken?n("li",{staticClass:"hidden-md hidden-lg"},[n("a",{attrs:{href:"javascript:;"},nativeOn:{click:function(e){t.signout(e)}}},[t._v("注销登录")])]):t._e()],1)],1)])},staticRenderFns:[]};var r=n("VU/8")(a,o,!1,function(t){n("VU4u")},null,null);e.default=r.exports},MvGc:function(t,e){e.install=function(t,e){t.prototype.convertFundStage=function(t){switch(t){case 0:return"保密";case 1:return"未融资";case 2:return"融资中";case 3:return"已融资"}},t.prototype.convertBuzType=function(t){switch(t){case 1:return"金融";case 2:return"数字货币";case 3:return"娱乐";case 4:return"供应链管理";case 5:return"法律服务";case 6:return"医疗";case 7:return"能源服务";case 8:return"公益";case 9:return"物联网";case 10:return"农业";case 11:return"社交";default:return"其它"}},t.prototype.getShortStr=function(t,e){return t.substr(0,e)+"..."+t.substr(-1*e)},t.prototype.convertOrderStatus=function(t){switch(t){case 0:return"待支付";case 1:return"已完成";case 2:return"已取消";case 3:return"已过期";default:return"未知状态"}},t.prototype.convertDate=function(t){var e=(new Date).getTime()-new Date(t).getTime(),n=e/36e5,a=e/6e4;if(n>24)var o=parseInt(e/864e5)+"天前";else if(n>=1)o=parseInt(n)+"个小时前";else if(a>=1)o=parseInt(a)+"分钟前";else o="刚刚";return o},t.prototype.getInterest=function(t,e,n){return t*e*n/12},t.prototype.mediaClass=function(){return document.body.clientWidth<=600?"media-mobile":""},Array.prototype.indexOf=function(t){for(var e=0;e<this.length;e++)if(this[e]===t)return e;return-1},Array.prototype.remove=function(t){var e=this.indexOf(t);e>-1&&this.splice(e,1)}}},NHnr:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a={};n.d(a,"reLogin",function(){return b}),n.d(a,"getProList",function(){return j}),n.d(a,"getProDetail",function(){return _}),n.d(a,"getNewsList",function(){return P}),n.d(a,"getFilterParams",function(){return k}),n.d(a,"getTop10",function(){return O}),n.d(a,"updateFocus",function(){return D});var o={};n.d(o,"updateUserInfo",function(){return R}),n.d(o,"cleanUserInfo",function(){return S});var r=n("//Fk"),s=n.n(r),i=n("7+uW"),c=n("fZjL"),u=n.n(c),l=n("Dd8w"),d=n.n(l),p=n("NYxO"),v=n("CaK3"),f=n("bYoP"),h={name:"App",components:{vHeader:v.default,vFooter:f.default},computed:d()({},Object(p.d)({path:function(t){return t.route.path},userInfo:function(t){return t.userInfo}}),{visible:function(){return"/share"!==this.path},hasToken:function(){return!!this.userInfo&&u()(this.userInfo).length>0}}),watch:{hasToken:function(t){t||this.$router.push("/signin")}},methods:d()({},Object(p.c)(["cleanUserInfo"]),{signout:function(){this.cleanUserInfo()}})},m={render:function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{attrs:{id:"app"}},[t.visible?n("v-header",{attrs:{"has-token":t.hasToken},on:{signout:t.signout}}):t._e(),t._v(" "),n("div",{staticClass:"main-container"},[n("router-view")],1),t._v(" "),t.visible?n("v-footer"):t._e()],1)},staticRenderFns:[]};var g=n("VU/8")(h,m,!1,function(t){n("z8qy"),n("Ymkb")},"data-v-49e9e196",null).exports,w=n("mtWM"),y=n.n(w),b=function(t){(0,t.commit)("cleanUserInfo")},C=function(t){var e=t.errcode,n=t.data,a=t.errmsg,o=void 0===a?"":a;switch(e){case 0:return n;case 302:return b(),s.a.reject(o);default:return alert(o),s.a.reject(o)}},j=function(t,e){return i.default.axios.post("/api/getProjList",e).then(function(t){return C(t.data)})},_=function(t,e){return i.default.axios.post("/api/getProjDetail",e).then(function(t){return C(t.data)})},P=function(t,e){return i.default.axios.post("/api/getNewsList",e).then(function(t){return C(t.data)})},k=function(t,e){return i.default.axios.post("/api/getProjTagList",e).then(function(t){return C(t.data)})},O=function(t,e){return i.default.axios.post("/api/getProjTopList",e).then(function(t){return C(t.data)})},D=function(t,e){return i.default.axios.post("/api/toggleFocus",e).then(function(t){return C(t.data)})},I=n("mvHQ"),L=n.n(I),R=function(t,e){var n=localStorage.getItem("userInfo");n&&(n=JSON.parse(n)),e&&(n=e,localStorage.setItem("userInfo",L()(n))),t.userInfo=n},S=function(t){localStorage.removeItem("userInfo"),t.userInfo=null};i.default.use(p.a);var x=new p.a.Store({actions:a,mutations:o,state:{userInfo:null}}),T=n("/ocq");function F(t){return function(){return n("mUJ2")("./"+t+".vue")}}function U(t){return function(){return n("Opzk")("./"+t+".vue")}}var B=[{path:"/",component:U("home/home")},{path:"/discover",component:U("discover/discover")},{path:"/discover/detail/:id",component:U("discover/detail")},{path:"/wallet",component:U("user/wallet")},{path:"/wallet/withdraw/:id",component:U("user/withdraw")},{path:"/wallet/records",component:U("user/records")},{path:"/projList",component:F("projList/ProjList")},{path:"/candyRoom",redirect:"/candyRoom/candyList",component:F("candyRoom/CandyRoom"),children:[{path:"candyList",component:F("candyRoom/CandyList")},{path:"candyBuy",component:F("candyRoom/CandyBuy"),props:!0},{path:"candyOrder",component:F("candyRoom/CandyOrder")},{path:"candyOrderDetail/:id",component:F("candyRoom/CandyOrderDetail")},{path:"candyOrderConfirm/:id",component:F("candyRoom/CandyOrderConfirm")},{path:"myCandyOrder",component:F("candyRoom/MyCandyOrder")}]},{path:"/projDetail/:id",component:F("projDetail/ProjDetail"),redirect:"/projDetail/info/:id",children:[{path:"/projDetail/info/:id",component:F("projDetail/ProjDetailPanel")},{path:"/projDetail/dynamic/:id",component:F("projDetail/ProjDynamicPanel")}]},{path:"/signin",component:F("sign/Signin")},{path:"/signup",component:F("sign/Signup")},{path:"/share",component:F("share/Share")},{path:"/protocol",component:F("sign/Protocol")},{path:"/findpwd",component:F("sign/FindPwd")},{path:"/resetpwd/:mobile",component:F("sign/ResetPwd")},{path:"/newslist",component:F("news/NewsList")},{path:"/newsdetail/:id",component:F("news/NewsDetail")},{path:"/apply",component:F("apply/Apply")}];i.default.use(T.a);var N=new T.a({routes:B,mode:"history",linkActiveClass:"active",scrollBehavior:function(t,e,n){return t.hash?{selector:t.hash}:n||{x:0,y:0}}}),$=n("9JMe"),z=n("zL8q"),E=n.n(z),M=(n("v2ns"),n("tvR6"),n("MvGc")),V=n.n(M);i.default.use(V.a),n("hKoQ").polyfill(),i.default.use(E.a),i.default.axios=y.a,i.default.prototype.$http=y.a,i.default.config.productionTip=!1,Object($.sync)(x,N),y.a.interceptors.response.use(function(t){return 302===t.data.errcode?(x.commit("cleanUserInfo"),N.push("/"),s.a.reject(t)):t},function(t){return 302===t.response.status&&(x.commit("cleanUserInfo"),N.push("/")),s.a.reject(t)}),x.commit("updateUserInfo"),new i.default({el:"#app",store:x,router:N,components:{App:g},template:"<App/>",data:{eventHub:new i.default}})},Opzk:function(t,e,n){var a={"./discover/detail.vue":["PwbF",0,5],"./discover/discover.vue":["POBk",0,8],"./home/home.vue":["zKIK",0,2],"./user/records.vue":["sFhK",0,17],"./user/wallet.vue":["I37D",0,11],"./user/withdraw.vue":["z4wH",7]};function o(t){var e=a[t];return e?Promise.all(e.slice(1).map(n.e)).then(function(){return n(e[0])}):Promise.reject(new Error("Cannot find module '"+t+"'."))}o.keys=function(){return Object.keys(a)},o.id="Opzk",t.exports=o},VU4u:function(t,e){},Ymkb:function(t,e){},bYoP:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a={render:function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"footer"},[e("div",{staticClass:"content"},[this._m(0),this._v(" "),e("div",{staticClass:"icon-box"},this._l(this.socialList,function(t,n){return e("a",{key:n,attrs:{href:t.url,target:"_blank"}},[e("i",{staticClass:" fab",class:t.fontClass})])})),this._v(" "),this._m(1)])])},staticRenderFns:[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"name"},[e("img",{attrs:{src:"/static/img/logo.png",alt:""}})])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"bottom"},[e("span",[this._v("Copyright © 2018 BitCV. All Rights Reserved")])])}]};var o=n("VU/8")({data:function(){return{socialList:[{url:"https://weibo.com/bitcv",fontClass:"fa-weibo"},{url:"https://www.facebook.com/groups/1301707606641533/",fontClass:"fa-facebook"},{url:"https://github.com/bitcv",fontClass:"fa-github"},{url:"https://t.me/bcvtoken",fontClass:"fa-telegram-plane"},{url:"https://twitter.com/BCVofficial",fontClass:"fa-twitter"}]}}},a,!1,function(t){n("sagv")},"data-v-7b2f74fa",null);e.default=o.exports},mUJ2:function(t,e,n){var a={"./apply/Apply.vue":["0OVd",21],"./candyRoom/CandyBuy.vue":["tHq/",16],"./candyRoom/CandyList.vue":["ynLq",24],"./candyRoom/CandyOrder.vue":["h6Zs",34],"./candyRoom/CandyOrderConfirm.vue":["3ExQ",0,22],"./candyRoom/CandyOrderDetail.vue":["eB6N",0,29],"./candyRoom/CandyRoom.vue":["Z8Fx",20],"./candyRoom/MyCandyOrder.vue":["LItE",10],"./footer/Footer.vue":["bYoP"],"./header/Header-back.vue":["pmcr",37],"./header/Header.vue":["CaK3"],"./home/Home.vue":["h6qm",0,1],"./home/NewsPanel.vue":["NGpT",31],"./home/ProjIntro.vue":["0VlW",0],"./home/ProjListPanel.vue":["U5/d",12],"./home/ProjShowPanel.vue":["SYRS",0,30],"./home/Search.vue":["ZsFn",0],"./home/SearchPanel.vue":["c8fR",0,35],"./home/TopList.vue":["n5G4",36],"./navbar/navbar.vue":["dFzb",27],"./news/NewsDetail.vue":["y0Ls",0,13],"./news/NewsList.vue":["mcZf",0,6],"./news/NewsTablePanel.vue":["fOQt",33],"./pagination/pagination.vue":["+ryu",0],"./popside/popside.vue":["GIbw"],"./poptips/poptips.vue":["K1eW",18],"./projDetail/ProjDetail.vue":["prwi",0,4],"./projDetail/ProjDetailPanel.vue":["Yxvu",0,32],"./projDetail/ProjDynamicPanel.vue":["zdKg",0,14],"./projDetail/ProjInfoPanel.vue":["Y8Ir",0,25],"./projDetail/ProjTimeLine.vue":["qrst",0],"./projList/PagePanel.vue":["oByv",0],"./projList/ProjList.vue":["c+ar",0,3],"./projList/ProjSearchPanel.vue":["+4D8",0,26],"./projList/ProjTablePanel.vue":["eslO",0,19],"./projList/TopListPanel.vue":["YCFY",0],"./search-bar/search-bar.vue":["b17X",0],"./share/Share.vue":["mSzT",0],"./sign/FindPwd.vue":["vZjr",38],"./sign/Protocol.vue":["0wBR",9],"./sign/ResetPwd.vue":["ynxK",23],"./sign/Signin.vue":["nsCj",15],"./sign/Signup.vue":["E+Fy",28]};function o(t){var e=a[t];return e?Promise.all(e.slice(1).map(n.e)).then(function(){return n(e[0])}):Promise.reject(new Error("Cannot find module '"+t+"'."))}o.keys=function(){return Object.keys(a)},o.id="mUJ2",t.exports=o},sagv:function(t,e){},tvR6:function(t,e){},v2ns:function(t,e){},vj4M:function(t,e){},z8qy:function(t,e){}},["NHnr"]);
//# sourceMappingURL=app.a7635d752c88bb134a47.js.map