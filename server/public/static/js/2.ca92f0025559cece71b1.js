webpackJsonp([2,28,37,40],{"3o7U":function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a=s("Dd8w"),n=s.n(a),l=s("NYxO"),r={data:function(){return{isFinished:!1,list:[{id:9,address:"sjjdhdhd",number:300,status:1}]}},computed:n()({},Object(l.d)({path:function(t){return t.route.path}}))},i={render:function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"step3"},[s("h5",[t._v(t._s(t.isFinished?"发放完成":"发放中"))]),t._v(" "),s("el-table",{staticStyle:{width:"100%"},attrs:{data:t.list}},[s("el-table-column",{attrs:{prop:"id",label:"序号",width:"100"}}),t._v(" "),s("el-table-column",{attrs:{prop:"address",label:"地址"}}),t._v(" "),s("el-table-column",{attrs:{prop:"number",label:"数量",width:"180"}}),t._v(" "),s("el-table-column",{attrs:{prop:"status",label:"状态",width:"180"},scopedSlots:t._u([{key:"default",fn:function(e){return[s("span",{class:1===e.row.status?"text-success":""},[t._v("\n          "+t._s(["发放中","已完成"][e.row.status])+"\n        ")])]}}])})],1),t._v(" "),s("footer",[s("el-row",[s("el-col",{attrs:{span:16}},[s("el-progress",{attrs:{percentage:56}})],1),t._v(" "),s("el-col",{staticClass:"text-warning",attrs:{span:8}},[t._v("下载完成报告")])],1)],1)],1)},staticRenderFns:[]};var o=s("VU/8")(r,i,!1,function(t){s("VgTN")},null,null);e.default=o.exports},P178:function(t,e){},"SwJ/":function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a=s("Dd8w"),n=s.n(a),l=s("NYxO"),r=s("X2mQ"),i=s("xipm"),o={components:{VueQr:r.a},data:function(){return{isRecharge:!0,info:{total:3e4,cost:16,type:0,itype:1,BCVnumber:5e3,ETHnumber:6e3},recData:{address:"0xsajaiaksoalksidujh8093jd84kd9s0a9djhvu87"},assets:{BVC:2e3,EHT:378,BTC:288}}},computed:n()({},Object(l.d)({path:function(t){return t.route.path}})),methods:{handleChange:function(t){this.info.itype=0===t?1:0},handleRecharge:function(){var t=this;i.a.$on("handleEmit",function(e){e&&(t.recData=e)}),this.isRecharge=!0},handleConfirm:function(){this.$emit("finished")},handleCopy:function(){console.log("copy")},handleRefresh:function(){console.log("刷新")},handleFinished:function(){this.isRecharge=!1}}},c={render:function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"step2"},[t.isRecharge?s("div",[s("div",{staticClass:"recharge-top"},[s("el-row",[s("el-col",{staticClass:"left",attrs:{span:18}},[s("h5",[t._v("充值地址")]),t._v(" "),s("div",[s("span",[t._v(t._s(t.recData.address))]),t._v(" "),s("i",{staticClass:"el-icon-document",on:{click:t.handleCopy}})]),t._v(" "),s("p",[t._v("\n            提示："),s("span",[t._v("单笔充值不得低于0.003BCV")]),t._v("，\n            我们不会处理少于该金额的BCV充值要求。\n          ")])]),t._v(" "),s("el-col",{staticClass:"right",attrs:{span:6}},[s("vue-qr",{staticClass:"qrcode",attrs:{text:t.recData.address||"",margin:10}}),t._v(" "),s("p",[t._v("或扫二维码立即充值")])],1)],1)],1),t._v(" "),s("div",{staticClass:"recharge-bottom"},[s("h5",[t._v("\n        资产余额"),s("i",{staticClass:"el-icon-refresh",on:{click:t.handleRefresh}})]),t._v(" "),s("el-row",[s("el-col",{attrs:{span:8}},[s("span",[t._v(t._s(t.assets.BVC))]),t._v(" "),s("small",[t._v("BVC")])]),t._v(" "),s("el-col",{attrs:{span:8}},[s("span",[t._v(t._s(t.assets.EHT))]),t._v(" "),s("small",[t._v("EHT")])]),t._v(" "),s("el-col",{attrs:{span:8}},[s("span",[t._v(t._s(t.assets.BTC))]),t._v(" "),s("small",[t._v("BTC")])])],1)],1),t._v(" "),s("footer",{staticClass:"recharge-footer"},[s("el-button",{staticClass:"btn-primary",attrs:{type:"warning"},on:{click:t.handleFinished}},[t._v("充值完成")])],1)]):s("div",{staticClass:"confirm"},[s("h5",[t._v("确认发放")]),t._v(" "),s("div",{staticClass:"confirm-box"},[s("div",{staticClass:"confirm-content"},[s("div",{staticClass:"confirm-top"},[s("el-row",[s("el-col",{attrs:{span:12}},[s("h6",[t._v("发币总量")]),t._v(" "),s("p",[s("img",{attrs:{src:"/static/logo/bcv.png",alt:"logo"}}),t._v(" "),s("b",[t._v(t._s(t.info.total))]),t._v(" "),s("small",[t._v(t._s(["ETH","BCV"][t.info.itype]))])])]),t._v(" "),s("el-col",{attrs:{span:12}},[s("h6",[t._v("手续费")]),t._v(" "),s("p",[s("img",{attrs:{src:"/static/logo/bcv.png",alt:"logo"}}),t._v(" "),s("b",[t._v(t._s(t.info.cost))]),t._v(" "),s("small",[s("el-radio-group",{on:{change:t.handleChange},model:{value:t.info.type,callback:function(e){t.$set(t.info,"type",e)},expression:"info.type"}},[s("el-radio",{attrs:{label:0}},[t._v("ETH")]),t._v(" "),s("el-radio",{attrs:{label:1}},[t._v("BCV")])],1)],1)])])],1)],1),t._v(" "),s("div",{staticClass:"confirm-line"}),t._v(" "),s("div",{staticClass:"confirm-bottom"},[s("el-row",[s("el-col",{attrs:{span:12}},[s("h6",[t._v("可用BCV余额")]),t._v(" "),s("p",[s("img",{attrs:{src:"/static/logo/bcv.png",alt:"logo"}}),t._v(" "),s("span",[t._v(t._s(t.info.BCVnumber))]),t._v(" "),s("small",[t._v("枚")])])]),t._v(" "),s("el-col",{attrs:{span:12}},[s("h6",[t._v("可用ETH余额")]),t._v(" "),s("p",[s("img",{attrs:{src:"/static/logo/bcv.png",alt:"logo"}}),t._v(" "),s("span",[t._v(t._s(t.info.ETHnumber))]),t._v(" "),s("small",[t._v("\n                  枚\n                ")])])])],1)],1)]),t._v(" "),s("footer",{staticClass:"confirm-footer"},[s("el-row",[s("el-col",{attrs:{span:14}},[t._v("\n            您的余额不足，请先充值\n          ")]),t._v(" "),s("el-col",{attrs:{span:10}},[s("el-button",{attrs:{type:"warning",plain:""},on:{click:t.handleRecharge}},[t._v("去充值")]),t._v(" "),s("el-button",{staticClass:"btn-primary",attrs:{type:"warning"},on:{click:t.handleConfirm}},[t._v("确认发放")])],1)],1)],1)])])])},staticRenderFns:[]};var d=s("VU/8")(o,c,!1,function(t){s("dPLG")},null,null);e.default=d.exports},VgTN:function(t,e){},YiUC:function(t,e){},dPLG:function(t,e){},kZXB:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a=s("xipm"),n={data:function(){return{form:{address:"",type:0},rules:{address:[{validator:function(t,e,s){void 0!==e&&""!==e||s(new Error("必填")),e.startsWith("0x")&&42===e.length||s(new Error("请输入正确的合约地址")),s()}}],type:[{validator:function(t,e,s){void 0!==e&&""!==e||s(new Error("必填")),s()}}]},isUpload:!1,list:[{id:1,address:"0xsjksjdn",number:3,status:0}],total:{number:1e4,address:300}}},methods:{submit:function(){var t=this;this.$refs.form.validate(function(e){if(!e)return console.log("error submit!!"),!1;t.isUpload=!0})},fetch:function(){console.log("获取")},confirm:function(){this.$emit("finished",!0),a.a.$emit("handleEmit",this.form)}}},l={render:function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"step1"},[t.isUpload?s("div",[s("h4",[t._v("上传成功")]),t._v(" "),s("el-table",{staticStyle:{width:"100%"},attrs:{data:t.list}},[s("el-table-column",{attrs:{prop:"id",label:"序号",width:"180"}}),t._v(" "),s("el-table-column",{attrs:{prop:"address",label:"地址"}}),t._v(" "),s("el-table-column",{attrs:{prop:"number",label:"数量",width:"180"}}),t._v(" "),s("el-table-column",{attrs:{prop:"status",label:"状态",width:"180"}})],1),t._v(" "),s("footer",{staticClass:"footer"},[s("el-row",[s("el-col",{attrs:{span:7}},[s("h5",[t._v("总数量")]),t._v(" "),s("div",[t._v("\n            "+t._s(t.total.number)+"\n            "),s("small",[t._v("枚")])])]),t._v(" "),s("el-col",{attrs:{span:7}},[s("h5",[t._v("总地址")]),t._v(" "),s("div",[t._v("\n            "+t._s(t.total.address)+"\n            "),s("small",[t._v("条")])])]),t._v(" "),s("el-col",{staticClass:"text-center",attrs:{span:10}},[s("el-button",{staticClass:"btn-primary",attrs:{type:"warning"},on:{click:t.confirm}},[t._v("确定")]),t._v(" "),s("el-button",{staticClass:"text-primary",attrs:{type:"text"},on:{click:function(e){t.isUpload=!1}}},[t._v("重新上传")])],1)],1)],1)],1):s("el-form",{ref:"form",staticClass:"form",attrs:{model:t.form,rules:t.rules,"label-width":"120px"}},[s("el-form-item",{attrs:{label:"",prop:"type"}},[s("el-radio-group",{model:{value:t.form.type,callback:function(e){t.$set(t.form,"type",e)},expression:"form.type"}},[s("el-radio",{attrs:{label:0}},[t._v("正式发放")]),t._v(" "),s("el-radio",{attrs:{label:1,disabled:""}},[t._v("测试发放"),s("small",{staticStyle:{color:"#ccc"}},[t._v("（测试时仅向前两个地址发放代币）")])])],1)],1),t._v(" "),s("el-form-item",{attrs:{label:"智能合约地址",prop:"address"}},[s("el-input",{model:{value:t.form.address,callback:function(e){t.$set(t.form,"address",e)},expression:"form.address"}})],1),t._v(" "),s("el-form-item",{attrs:{label:"上传发放地址"}},[s("el-button",{staticClass:"btn-primary",attrs:{type:"warning"},on:{click:t.submit}},[t._v("点击上传")]),t._v(" "),s("el-button",{attrs:{type:"warning",plain:""},on:{click:t.fetch}},[t._v("获取模板")])],1)],1)],1)},staticRenderFns:[]};var r=s("VU/8")(n,l,!1,function(t){s("P178")},null,null);e.default=r.exports},vjWk:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a=s("Dd8w"),n=s.n(a),l=s("NYxO"),r=s("kZXB"),i=s("SwJ/"),o=s("3o7U"),c={components:{Step1:r.default,Step2:i.default,Step3:o.default},data:function(){return{active:0}},computed:n()({},Object(l.d)({path:function(t){return t.route.path}})),methods:{handleFinshed:function(t){t<2&&(this.active=t+1)}}},d={render:function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"acting-home"},[s("el-steps",{attrs:{active:t.active,"align-center":""}},[s("el-step",{attrs:{title:"上传"}}),t._v(" "),s("el-step",{attrs:{title:"确认"}}),t._v(" "),s("el-step",{attrs:{title:"发放"}})],1),t._v(" "),0===t.active?s("step1",{on:{finished:function(e){t.handleFinshed(0)}}}):t._e(),t._v(" "),1===t.active?s("step2",{on:{finished:function(e){t.handleFinshed(1)}}}):t._e(),t._v(" "),2===t.active?s("step3",{on:{finished:function(e){t.handleFinshed(2)}}}):t._e()],1)},staticRenderFns:[]};var v=s("VU/8")(c,d,!1,function(t){s("YiUC")},null,null);e.default=v.exports}});
//# sourceMappingURL=2.ca92f0025559cece71b1.js.map