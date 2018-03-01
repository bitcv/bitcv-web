webpackJsonp([39],{"4jj0":function(t,e){},GIbw:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o={props:{value:{type:Boolean,default:!1},hasToken:Boolean},data:function(){return{show:!1}},watch:{value:{handler:function(t){this.show=t},immediate:!0}},methods:{toggle:function(){this.$emit("input",!this.show)},signout:function(){this.$emit("signout"),this.toggle()}}},a={render:function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"popside",class:{"popside-show":t.show}},[n("div",{staticClass:"popside-mask",on:{click:t.toggle}}),t._v(" "),n("div",{staticClass:"popside-container"},[t.hasToken?n("img",{staticClass:"popside-avatar",attrs:{src:"/static/img/avatar.png"}}):n("router-link",{staticClass:"popside-avatar hidden-sm",attrs:{to:"/signin"},nativeOn:{click:function(e){t.toggle(e)}}},[t._v("登录")]),t._v(" "),n("ul",{staticClass:"nav navbar-nav popside-nav"},[n("router-link",{attrs:{tag:"li","active-class":"active",exact:"",to:"/"},nativeOn:{click:function(e){t.toggle(e)}}},[n("a",{attrs:{href:"javascript:;"}},[t._v("主页")])]),t._v(" "),n("router-link",{attrs:{tag:"li","active-class":"active",to:"/discover"},nativeOn:{click:function(e){t.toggle(e)}}},[n("a",{attrs:{href:"javascript:;"}},[t._v("发现")])]),t._v(" "),n("router-link",{attrs:{tag:"li","active-class":"active",to:"/candyRoom"},nativeOn:{click:function(e){t.toggle(e)}}},[n("a",{attrs:{href:"javascript:;"}},[t._v("余币宝")])]),t._v(" "),t.hasToken?n("li",{staticClass:"hidden-md hidden-lg"},[n("a",{attrs:{href:"javascript:;"},nativeOn:{click:function(e){t.signout(e)}}},[t._v("注销登录")])]):t._e()],1)],1)])},staticRenderFns:[]};var r=n("VU/8")(o,a,!1,function(t){n("VU4u")},null,null);e.default=r.exports},MvGc:function(t,e){e.install=function(t,e){t.prototype.convertFundStage=function(t){switch(t){case 0:return"保密";case 1:return"未融资";case 2:return"融资中";case 3:return"已融资"}},t.prototype.convertBuzType=function(t){switch(t){case 1:return"金融";case 2:return"数字货币";case 3:return"娱乐";case 4:return"供应链管理";case 5:return"法律服务";case 6:return"医疗";case 7:return"能源服务";case 8:return"公益";case 9:return"物联网";case 10:return"农业";case 11:return"社交";default:return"其它"}},t.prototype.getShortStr=function(t,e){return t.substr(0,e)+"..."+t.substr(-1*e)},t.prototype.convertOrderStatus=function(t){switch(t){case 0:return"待支付";case 1:return"已完成";case 2:return"已取消";case 3:return"已过期";default:return"未知状态"}},t.prototype.convertDate=function(t){var e=(new Date).getTime()-new Date(t).getTime(),n=e/36e5,o=e/6e4;if(n>24)var a=parseInt(e/864e5)+"天前";else if(n>=1)a=parseInt(n)+"个小时前";else if(o>=1)a=parseInt(o)+"分钟前";else a="刚刚";return a},t.prototype.getInterest=function(t,e,n){return t*e*n/12},t.prototype.mediaClass=function(){return document.body.clientWidth<=600?"media-mobile":""},Array.prototype.indexOf=function(t){for(var e=0;e<this.length;e++)if(this[e]===t)return e;return-1},Array.prototype.remove=function(t){var e=this.indexOf(t);e>-1&&this.splice(e,1)}}},NHnr:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o={};n.d(o,"reLogin",function(){return w}),n.d(o,"getProList",function(){return b}),n.d(o,"getProDetail",function(){return _}),n.d(o,"getNewsList",function(){return C}),n.d(o,"getFilterParams",function(){return j}),n.d(o,"getTop10",function(){return k}),n.d(o,"updateFocus",function(){return P});var a={};n.d(a,"updateUserInfo",function(){return D}),n.d(a,"cleanUserInfo",function(){return L});var r=n("//Fk"),i=n.n(r),s=n("7+uW"),c=n("fZjL"),u=n.n(c),l=n("Dd8w"),d=n.n(l),p=n("NYxO"),v={name:"App",components:{vHeader:n("hxP8").default,vFooter:n("bYoP").default},computed:d()({},Object(p.d)({path:function(t){return t.route.path},userInfo:function(t){return t.userInfo}}),{visible:function(){return"/share"!==this.path},hasToken:function(){return!!this.userInfo&&u()(this.userInfo).length>0}}),watch:{hasToken:function(t){t||this.$router.push("/signin")}},methods:d()({},Object(p.c)(["cleanUserInfo"]),{signout:function(){this.cleanUserInfo()}})},h={render:function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{attrs:{id:"app"}},[t.visible?n("v-header",{attrs:{"has-token":t.hasToken},on:{signout:t.signout}}):t._e(),t._v(" "),n("div",{staticClass:"main-container"},[n("router-view")],1),t._v(" "),t.visible?n("v-footer"):t._e()],1)},staticRenderFns:[]};var f=n("VU/8")(v,h,!1,function(t){n("4jj0"),n("qqIn")},"data-v-44889f04",null).exports,m=n("mtWM"),g=n.n(m),w=function(t){(0,t.commit)("cleanUserInfo")},y=function(t){var e=t.errcode,n=t.data,o=t.errmsg,a=void 0===o?"":o;switch(e){case 0:return n;case 302:return w(),i.a.reject(a);default:return alert(a),i.a.reject(a)}},b=function(t,e){return s.default.axios.post("/api/getProjList",e).then(function(t){return y(t.data)})},_=function(t,e){return s.default.axios.post("/api/getProjDetail",e).then(function(t){return y(t.data)})},C=function(t,e){return s.default.axios.post("/api/getNewsList",e).then(function(t){return y(t.data)})},j=function(t,e){return s.default.axios.post("/api/getProjTagList",e).then(function(t){return y(t.data)})},k=function(t,e){return s.default.axios.post("/api/getProjTopList",e).then(function(t){return y(t.data)})},P=function(t,e){return s.default.axios.post("/api/toggleFocus",e).then(function(t){return y(t.data)})},O=n("mvHQ"),I=n.n(O),D=function(t,e){var n=localStorage.getItem("userInfo");n&&(n=JSON.parse(n)),e&&(n=e,localStorage.setItem("userInfo",I()(n))),t.userInfo=n},L=function(t){localStorage.removeItem("userInfo"),t.userInfo=null};s.default.use(p.a);var S=new p.a.Store({actions:o,mutations:a,state:{userInfo:null}}),R=n("/ocq");function T(t){return function(){return n("mUJ2")("./"+t+".vue")}}function x(t){return function(){return n("Opzk")("./"+t+".vue")}}var M=[{path:"/",component:x("home/home")},{path:"/discover",component:x("discover/discover")},{path:"/discover/detail/:id",component:x("discover/detail")},{path:"/wallet/:code",component:x("user/wallet")},{path:"/wallet/withdraw/:id",component:x("user/withdraw")},{path:"/wallet/records",component:x("user/records")},{path:"/projList",component:T("projList/ProjList")},{path:"/candyRoom",redirect:"/candyRoom/candyList",component:T("candyRoom/CandyRoom"),children:[{path:"candyList",component:T("candyRoom/CandyList")},{path:"candyBuy",component:T("candyRoom/CandyBuy"),props:!0},{path:"candyOrder",component:T("candyRoom/CandyOrder")},{path:"candyOrderDetail/:id",component:T("candyRoom/CandyOrderDetail")},{path:"candyOrderConfirm/:id",component:T("candyRoom/CandyOrderConfirm")},{path:"myCandyOrder",component:T("candyRoom/MyCandyOrder")}]},{path:"/projDetail/:id",component:T("projDetail/ProjDetail"),redirect:"/projDetail/info/:id",children:[{path:"/projDetail/info/:id",component:T("projDetail/ProjDetailPanel")},{path:"/projDetail/dynamic/:id",component:T("projDetail/ProjDynamicPanel")}]},{path:"/signin",component:T("sign/Signin")},{path:"/signup",component:T("sign/Signup")},{path:"/share",component:T("share/Share")},{path:"/protocol",component:T("sign/Protocol")},{path:"/findpwd",component:T("sign/FindPwd")},{path:"/resetpwd/:mobile",component:T("sign/ResetPwd")},{path:"/newslist",component:T("news/NewsList")},{path:"/newsdetail/:id",component:T("news/NewsDetail")},{path:"/apply",component:T("apply/Apply")}];s.default.use(R.a);var F=new R.a({routes:M,mode:"history",linkActiveClass:"active",scrollBehavior:function(t,e,n){return t.hash?{selector:t.hash}:n||{x:0,y:0}}}),U=n("9JMe"),B=n("zL8q"),N=n.n(B),E=(n("v2ns"),n("tvR6"),n("MvGc")),V=n.n(E);s.default.use(V.a),n("hKoQ").polyfill(),s.default.use(N.a),s.default.axios=g.a,s.default.prototype.$http=g.a,s.default.config.productionTip=!1,Object(U.sync)(S,F),g.a.interceptors.response.use(function(t){return 302===t.data.errcode?(S.commit("cleanUserInfo"),F.push("/"),i.a.reject(t)):t},function(t){return 302===t.response.status&&(S.commit("cleanUserInfo"),F.push("/")),i.a.reject(t)}),S.commit("updateUserInfo"),new s.default({el:"#app",store:S,router:F,components:{App:f},template:"<App/>",data:{eventHub:new s.default}})},Opzk:function(t,e,n){var o={"./discover/detail.vue":["PwbF",0,5],"./discover/discover.vue":["POBk",0,8],"./home/home.vue":["zKIK",0,2],"./user/records.vue":["sFhK",0,17],"./user/wallet.vue":["I37D",0,37],"./user/withdraw.vue":["z4wH",7]};function a(t){var e=o[t];return e?Promise.all(e.slice(1).map(n.e)).then(function(){return n(e[0])}):Promise.reject(new Error("Cannot find module '"+t+"'."))}a.keys=function(){return Object.keys(o)},a.id="Opzk",t.exports=a},VU4u:function(t,e){},WIKu:function(t,e){},bYoP:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o={render:function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"footer"},[e("div",{staticClass:"content"},[this._m(0),this._v(" "),e("div",{staticClass:"icon-box"},this._l(this.socialList,function(t,n){return e("a",{key:n,attrs:{href:t.url,target:"_blank"}},[e("i",{staticClass:" fab",class:t.fontClass})])})),this._v(" "),this._m(1)])])},staticRenderFns:[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"name"},[e("img",{attrs:{src:"/static/img/logo.png",alt:"BitCV"}})])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"bottom"},[e("span",[this._v("Copyright © 2018 BitCV. All Rights Reserved")])])}]};var a=n("VU/8")({data:function(){return{socialList:[{url:"https://weibo.com/bitcv",fontClass:"fa-weibo"},{url:"https://www.facebook.com/groups/1301707606641533/",fontClass:"fa-facebook"},{url:"https://github.com/bitcv",fontClass:"fa-github"},{url:"https://t.me/bcvtoken",fontClass:"fa-telegram-plane"},{url:"https://twitter.com/BCVofficial",fontClass:"fa-twitter"}]}}},o,!1,function(t){n("pgmb")},"data-v-4cb152b2",null);e.default=a.exports},hxP8:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n("Dd8w"),a=n.n(o),r=n("NYxO"),i={bind:function(t,e){var n=e.value;t.handler=function(e){t&&!t.contains(e.target)&&n(e)},document.addEventListener("click",t.handler,!0)},unbind:function(t){document.removeEventListener("click",t.handler,!0),t.handler=null}},s={components:{Popside:n("GIbw").default},props:{hasToken:Boolean},data:function(){return{showSide:!1,showDropdown:!1}},computed:a()({},Object(r.d)({userInfo:function(t){return t.userInfo}}),{avatar:function(){return this.userInfo&&this.userInfo.avatarUrl?this.userInfo.avatarUrl:"/static/img/avatar.png"}}),directives:{"click-outside":i},watch:{showSide:"onToggleMenu",showDropdown:"onToggleMenu"},methods:{navbarToggle:function(){this.showDropdown=!1,this.showSide=!this.showSide},onMouseenter:function(){this.showSide=!1,this.__timer&&(clearTimeout(this.__timer),this.__timer=null),this.showDropdown=!0},onMouseleave:function(){var t=this;this.__timer&&(clearTimeout(this.__timer),this.__timer=null),this.__timer=setTimeout(function(){t.showDropdown=!1},150)},onClickOutside:function(){this.showSide&&(this.showSide=!1)},onToggleMenu:function(){this.showSide||this.showDropdown?document.body.style.overflow="hidden":document.body.style.overflow=""},dimissMenu:function(){this.showSide&&(this.showSide=!1),this.showDropdown&&(this.showDropdown=!1)}},mounted:function(){this.showSide=!1,this.showDropdown=!1}},c={render:function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("nav",{staticClass:"navbar navbar-inverse navbar-static-top"},[n("div",{staticClass:"container"},[n("div",{staticClass:"navbar-header"},[n("button",{staticClass:"navbar-toggle",class:{collapsed:!t.showSide},attrs:{type:"button"},on:{click:function(e){e.stopPropagation(),t.navbarToggle(e)}}},[n("span",{staticClass:"sr-only"},[t._v("Toggle navigation")]),t._v(" "),n("span",{staticClass:"icon-bar"}),t._v(" "),n("span",{staticClass:"icon-bar"}),t._v(" "),n("span",{staticClass:"icon-bar"}),t._v(" "),n("span",{staticClass:"icon-bar"})]),t._v(" "),n("router-link",{staticClass:"navbar-brand",attrs:{to:"/"}},[n("img",{attrs:{src:"/static/img/brand.png",alt:"BitCV",height:"30"}})])],1),t._v(" "),t.hasToken?t._e():n("div",{staticClass:"navbar-right hidden-sm hidden-xs"},[n("router-link",{staticClass:"btn navbar-btn btn-default btn-outline",attrs:{to:"/signup"}},[t._v("注册")]),t._v(" "),n("span",[t._v("  ")]),t._v(" "),n("router-link",{staticClass:"btn navbar-btn btn-default btn-outline",attrs:{to:"/signin"}},[t._v("登录")])],1),t._v(" "),n("div",{staticClass:"dropdown navbar-right",class:{open:t.showDropdown},on:{mouseenter:t.onMouseenter,mouseleave:t.onMouseleave}},[n("a",{staticClass:"dropdown-toggle",attrs:{href:"javascrip:;"}},[n("img",{staticClass:"img-circle",attrs:{src:t.avatar}})]),t._v(" "),t.hasToken?n("ul",{staticClass:"dropdown-menu"},[n("li",[n("router-link",{attrs:{to:"/wallet/user"},nativeOn:{click:function(e){t.dimissMenu(e)}}},[t._v("我的资产")])],1),t._v(" "),n("li",[n("a",{attrs:{href:"javascript:;"},on:{click:function(e){t.$emit("signout")}}},[t._v("注销登录")])])]):n("ul",{staticClass:"dropdown-menu hidden-md hidden-lg"},[n("li",[n("router-link",{attrs:{to:"/signin"},nativeOn:{click:function(e){t.dimissMenu(e)}}},[t._v("登录")])],1),t._v(" "),n("li",[n("router-link",{attrs:{to:"/signup"},nativeOn:{click:function(e){t.dimissMenu(e)}}},[t._v("注册")])],1)])]),t._v(" "),n("div",{directives:[{name:"click-outside",rawName:"v-click-outside",value:t.onClickOutside,expression:"onClickOutside"}],staticClass:"collapse navbar-collapse",class:{in:t.showSide}},[n("ul",{staticClass:"nav navbar-nav"},[n("router-link",{attrs:{tag:"li","active-class":"active",exact:"",to:"/"},nativeOn:{click:function(e){t.dimissMenu(e)}}},[n("a",{attrs:{href:"javascript:;"}},[t._v("主页")])]),t._v(" "),n("router-link",{attrs:{tag:"li","active-class":"active",to:"/discover"},nativeOn:{click:function(e){t.dimissMenu(e)}}},[n("a",{attrs:{href:"javascript:;"}},[t._v("发现")])]),t._v(" "),n("router-link",{attrs:{tag:"li","active-class":"active",to:"/candyRoom"},nativeOn:{click:function(e){t.dimissMenu(e)}}},[n("a",{attrs:{href:"javascript:;"}},[t._v("余币宝")])])],1)])])])},staticRenderFns:[]};var u=n("VU/8")(s,c,!1,function(t){n("WIKu")},null,null);e.default=u.exports},mUJ2:function(t,e,n){var o={"./apply/Apply.vue":["0OVd",11],"./candyRoom/CandyBuy.vue":["tHq/",15],"./candyRoom/CandyList.vue":["ynLq",24],"./candyRoom/CandyOrder.vue":["h6Zs",34],"./candyRoom/CandyOrderConfirm.vue":["3ExQ",0,22],"./candyRoom/CandyOrderDetail.vue":["eB6N",0,28],"./candyRoom/CandyRoom.vue":["Z8Fx",20],"./candyRoom/MyCandyOrder.vue":["LItE",10],"./footer/Footer.vue":["bYoP"],"./header/header.vue":["hxP8"],"./home/Home.vue":["h6qm",0,1],"./home/NewsPanel.vue":["NGpT",31],"./home/ProjIntro.vue":["0VlW",0],"./home/ProjListPanel.vue":["U5/d",12],"./home/ProjShowPanel.vue":["SYRS",0,30],"./home/Search.vue":["ZsFn",0],"./home/SearchPanel.vue":["c8fR",0,35],"./home/TopList.vue":["n5G4",36],"./navbar/navbar.vue":["dFzb",27],"./news/NewsDetail.vue":["y0Ls",0,13],"./news/NewsList.vue":["mcZf",0,6],"./news/NewsTablePanel.vue":["fOQt",33],"./pagination/pagination.vue":["+ryu",0],"./popside/popside.vue":["GIbw"],"./poptips/poptips.vue":["K1eW",18],"./projDetail/ProjDetail.vue":["prwi",0,4],"./projDetail/ProjDetailPanel.vue":["Yxvu",0,32],"./projDetail/ProjDynamicPanel.vue":["zdKg",0,14],"./projDetail/ProjInfoPanel.vue":["Y8Ir",0,25],"./projDetail/ProjTimeLine.vue":["qrst",0],"./projList/PagePanel.vue":["oByv",0],"./projList/ProjList.vue":["c+ar",0,3],"./projList/ProjSearchPanel.vue":["+4D8",0,26],"./projList/ProjTablePanel.vue":["eslO",0,19],"./projList/TopListPanel.vue":["YCFY",0],"./search-bar/search-bar.vue":["b17X",0],"./share/Share.vue":["mSzT",0],"./sign/FindPwd.vue":["vZjr",21],"./sign/Protocol.vue":["0wBR",9],"./sign/ResetPwd.vue":["ynxK",23],"./sign/Signin.vue":["nsCj",29],"./sign/Signup.vue":["E+Fy",16]};function a(t){var e=o[t];return e?Promise.all(e.slice(1).map(n.e)).then(function(){return n(e[0])}):Promise.reject(new Error("Cannot find module '"+t+"'."))}a.keys=function(){return Object.keys(o)},a.id="mUJ2",t.exports=a},pgmb:function(t,e){},qqIn:function(t,e){},tvR6:function(t,e){},v2ns:function(t,e){}},["NHnr"]);
//# sourceMappingURL=app.04a39032bf11edd1028e.js.map