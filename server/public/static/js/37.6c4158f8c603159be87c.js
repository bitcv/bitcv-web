webpackJsonp([37],{"86Yl":function(t,e){},vZjr:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a={data:function(){return{mobile:"",vcode:"",timerId:"",disableSms:!1,countDown:60,passwd:"",selected:65}},methods:{getVcode:function(){var t=this;if(!new RegExp(/^\d{7,11}$/).test(this.mobile))return alert("请填写正确的手机号");this.disableSms=!0,this.timerId=setInterval(function(){t.countDown<=1?(clearInterval(t.timerId),t.disableSms=!1,t.timerId="",t.countDown=60):t.countDown--},1e3),this.$http.post("/api/getVcode",{mobile:this.mobile}).then(function(t){var e=t.data;0===e.errcode||alert(e.errmsg)}).catch(function(t){console.log(t)})},findPwd:function(){if(!this.selected)return alert("请选择手机国家号");if(!new RegExp(/^\d{7,11}$/).test(this.mobile))return alert("请填写正确的手机号");if(!this.vcode)return alert("验证码不能为空");if(this.passwd.length<6)return alert("密码长度至少需要6位");var t=this;this.$http.post("/api/resetPwd",{mobile:this.mobile,vcode:this.vcode,passwd:this.passwd,nation:this.selected}).then(function(e){var s=e.data;0===s.errcode?t.$router.push("/signin"):alert(s.errmsg)}).catch(function(t){console.log(t)})}}},i={render:function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"container signup-container"},[s("div",{staticClass:"findpwd"},[s("h3",{staticClass:"panel-title center-title"},[t._v("重置密码")]),t._v(" "),s("form",[[s("div",{staticClass:"row",staticStyle:{margin:"0"}},[s("div",{staticClass:"col-xs-4",staticStyle:{padding:"0"}},[s("el-select",{staticClass:"phone-suffix",attrs:{placeholder:"请选择"},model:{value:t.selected,callback:function(e){t.selected=e},expression:"selected"}},[s("el-option",{attrs:{value:"65"}},[t._v("新加坡 (+65)")]),t._v(" "),s("el-option",{attrs:{value:"1"}},[t._v("美国 (+1)")]),t._v(" "),s("el-option",{attrs:{value:"61"}},[t._v("澳大利亚 (+61)")]),t._v(" "),s("el-option",{attrs:{value:"81"}},[t._v("日本 (+81)")]),t._v(" "),s("el-option",{attrs:{value:"82"}},[t._v("韩国 (+82)")]),t._v(" "),s("el-option",{attrs:{value:"86"}},[t._v("中国 (+86)")]),t._v(" "),s("el-option",{attrs:{value:"852"}},[t._v("香港  (SAR) (+852)")]),t._v(" "),s("el-option",{attrs:{value:"853"}},[t._v("澳门 (+853)")]),t._v(" "),s("el-option",{attrs:{value:"856"}},[t._v("老挝 (+856)")]),t._v(" "),s("el-option",{attrs:{value:"886"}},[t._v("台湾 (+886)")])],1)],1),t._v(" "),s("div",{staticClass:"col-xs-8",staticStyle:{padding:"0"}},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.mobile,expression:"mobile"}],staticStyle:{width:"100%"},attrs:{type:"text",placeholder:"请输入你的手机号码"},domProps:{value:t.mobile},on:{input:function(e){e.target.composing||(t.mobile=e.target.value)}}})])])],t._v(" "),s("div",{staticClass:"smspanel"},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.vcode,expression:"vcode"}],staticClass:"sms",attrs:{type:"text",placeholder:"短信验证码"},domProps:{value:t.vcode},on:{input:function(e){e.target.composing||(t.vcode=e.target.value)}}}),t._v(" "),s("el-button",{staticClass:"sms-btn",class:{disabled:t.disableSms},attrs:{disabled:t.disableSms,type:"primary"},on:{click:t.getVcode}},[t._v("发送验证码"),s("span",{directives:[{name:"show",rawName:"v-show",value:t.timerId,expression:"timerId"}]},[t._v(" ("+t._s(t.countDown)+"s)")])])],1),t._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:t.passwd,expression:"passwd"}],attrs:{type:"password",placeholder:"请输入新的密码"},domProps:{value:t.passwd},on:{input:function(e){e.target.composing||(t.passwd=e.target.value)}}}),t._v(" "),s("button",{on:{click:function(e){e.preventDefault(),t.findPwd(e)}}},[t._v("找回密码")])],2)])])},staticRenderFns:[]};var o=s("VU/8")(a,i,!1,function(t){s("86Yl")},null,null);e.default=o.exports}});
//# sourceMappingURL=37.6c4158f8c603159be87c.js.map