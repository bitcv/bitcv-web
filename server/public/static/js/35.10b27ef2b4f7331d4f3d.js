webpackJsonp([35],{gmvd:function(t,e){},z4wH:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=s("Dd8w"),a=s.n(o),r=s("NYxO"),i={computed:a()({},Object(r.d)({id:function(t){return t.route.params.id},protocol:function(t){return t.route.params.protocol},symbol:function(t){return t.route.query.symbol},userInfo:function(t){return t.userInfo}})),data:function(){return{showTips:!0,walletAddr:"",vcode:"",timerId:"",disableSms:!1,countDown:60}},methods:{getVcode:function(){var t=this;this.disableSms=!0,this.timerId=setInterval(function(){t.countDown<=1?(clearInterval(t.timerId),t.disableSms=!1,t.timerId="",t.countDown=60):t.countDown--},1e3),this.$http.post("/api/getVcode",{mobile:this.userInfo.mobile}).then(function(t){0===t.data.errcode||alert(t.data.errmsg)}).catch(function(t){console.log(t)})},onSubmit:function(){var t=this;if(1==this.protocol)var e=/^0x[0-9a-f]{40}/i;else{if(2!=this.protocol&&3!=this.protocol)return!1;e=/^[0-9a-zA-Z]{34}$/i}if(!e.test(this.walletAddr))return alert("请填写正确的收币钱包地址");this.$http.post("/api/addUserWallet",{mobile:this.userInfo.mobile,vcode:this.vcode,tokenProtocol:this.protocol,walletAddr:this.walletAddr}).then(function(e){0===e.data.errcode?(console.log("withdraw"),t.$http.post("/api/withdraw",{assetId:t.$route.params.id}).then(function(e){0===e.data.errcode?(t.$message({type:"success",message:"提交成功!"}),t.$router.push("/wallet")):alert(e.data.errmsg)})):alert(e.data.errmsg)})},onCancel:function(){this.$router.push("/wallet")}}},n={render:function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("div",{staticClass:"poptips",class:{"poptips-show":t.showTips}},[t._m(0)]),t._v(" "),s("div",{staticClass:"container"},[t._m(1),t._v(" "),t._m(2),t._v(" "),t._m(3),t._v(" "),t._m(4),t._v(" "),s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-6 col-md-offset-3"},[s("form",{staticClass:"form-horizontal"},[s("div",{staticClass:"form-group no-corner"},[s("label",{staticClass:"control-label col-md-4"},[t._v(t._s(t.symbol)+"钱包地址:")]),t._v(" "),s("div",{staticClass:"col-md-8"},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.walletAddr,expression:"walletAddr"}],staticClass:"form-control",attrs:{type:"text",placeholder:"请输入相应币种的钱包地址"},domProps:{value:t.walletAddr},on:{input:function(e){e.target.composing||(t.walletAddr=e.target.value)}}})])]),t._v(" "),s("div",{staticClass:"form-group no-corner"},[s("label",{staticClass:"control-label col-md-4"},[t._v("输入手机号:")]),t._v(" "),s("div",{staticClass:"col-md-8"},[s("div",{staticClass:"input-group"},[s("input",{staticClass:"form-control",attrs:{disabled:"",type:"text"},domProps:{value:t.userInfo.mobile}}),t._v(" "),s("span",{staticClass:"input-group-btn"},[s("button",{staticClass:"btn btn-gray-light",attrs:{disabled:t.disableSms,type:"button"},on:{click:t.getVcode}},[t.disableSms?s("span",[t._v(" 请等待("+t._s(t.countDown)+"s)")]):s("span",[t._v("获取验证码")])])])])])]),t._v(" "),s("div",{staticClass:"form-group no-corner"},[s("label",{staticClass:"control-label col-md-4"},[t._v("输入手机验证码:")]),t._v(" "),s("div",{staticClass:"col-md-8"},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.vcode,expression:"vcode"}],staticClass:"form-control",attrs:{type:"text"},domProps:{value:t.vcode},on:{input:function(e){e.target.composing||(t.vcode=e.target.value)}}})])]),t._v(" "),t._m(5),t._v(" "),t._m(6),t._v(" "),s("div",{staticClass:"form-group"},[s("div",{staticClass:"col-md-8 col-md-offset-4"},[s("button",{staticClass:"btn btn-primary",staticStyle:{padding:"6px 20px"},attrs:{type:"button"},on:{click:t.onSubmit}},[t._v("提交")]),t._v(" "),s("button",{staticClass:"btn btn-gray",staticStyle:{"margin-left":"20px",padding:"6px 20px",color:"#fff"},attrs:{type:"button"},on:{click:t.onCancel}},[t._v("取消")])])])])])])])])},staticRenderFns:[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"container"},[e("i",{staticClass:"icon poptips-icon"}),this._v(" "),e("span",[this._v("提示：本次矿工费由币威平台承担")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("p",[e("br")])},function(){var t=this.$createElement,e=this._self._c||t;return e("p",[e("br")])},function(){var t=this.$createElement,e=this._self._c||t;return e("p",[e("br")])},function(){var t=this.$createElement,e=this._self._c||t;return e("p",[e("br")])},function(){var t=this.$createElement,e=this._self._c||t;return e("p",[e("br")])},function(){var t=this.$createElement,e=this._self._c||t;return e("p",[e("br")])}]};var l=s("VU/8")(i,n,!1,function(t){s("gmvd")},"data-v-1d12ff8e",null);e.default=l.exports}});
//# sourceMappingURL=35.10b27ef2b4f7331d4f3d.js.map