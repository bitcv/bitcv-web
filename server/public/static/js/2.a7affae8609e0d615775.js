webpackJsonp([2,10,22,41],{"/GXt":function(t,e){},"3o7U":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=a("Dd8w"),n=a.n(o),s=a("NYxO"),l=a("oAV5"),r={props:{taskId:Number},data:function(){return{isFinished:!1,list:[{id:9,address:"sjjdhdhd",number:300,status:1}],dataList:[],dataCount:0,process:0,totalAmount:0,contractAddr:"",tokenSymbol:"",tableLoad:!1,timerId:""}},mounted:function(){var t=this;this.tableLoad=!0,this.fetch(),this.timerId||(this.timerId=setInterval(function(){t.fetch()},3e4))},beforeDestroy:function(){this.timerId&&clearInterval(this.timerId)},computed:n()({},Object(s.d)({path:function(t){return t.route.path}})),methods:n()({},Object(s.b)(["getDispenseList"]),{fetch:function(){var t=this;this.getDispenseList({taskId:this.taskId}).then(function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};t.tableLoad=!1,t.dataList=e.dataList,t.dataCount=e.dataCount,t.process=e.process,t.totalAmount=e.totalAmount,t.contractAddr=e.contractAddr,t.tokenSymbol=e.tokenSymbol})},maskStr:function(t,e){return Object(l.d)(t,e)}})},i={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{directives:[{name:"loading",rawName:"v-loading",value:t.tableLoad,expression:"tableLoad"}],staticClass:"step3"},[a("h5",[t._v(t._s(t.isFinished?"发放完成":"发放中")),a("i",{staticClass:"el-icon-refresh",on:{click:t.fetch}})]),t._v(" "),a("el-table",{staticStyle:{width:"100%"},attrs:{data:t.dataList}},[a("el-table-column",{attrs:{type:"index",label:"序号",width:"100"}}),t._v(" "),a("el-table-column",{attrs:{prop:"toAddr",label:"地址"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("a",{attrs:{href:"https://etherscan.io/token/"+t.contractAddr+"?a="+e.row.toAddr,target:"_blank"}},[t._v("\n          "+t._s(t.maskStr(e.row.toAddr,5))+"\n        ")])]}}])}),t._v(" "),a("el-table-column",{attrs:{prop:"amount",label:"数量",width:"180"}}),t._v(" "),a("el-table-column",{attrs:{prop:"status",label:"状态",width:"180"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("span",{class:4===e.row.status?"text-success":""},[t._v("\n          "+t._s(["","待发送","待发送","转账中","已完成"][e.row.status])+"\n        ")])]}}])}),t._v(" "),a("el-table-column",{attrs:{prop:"txHash",label:"交易哈希"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("a",{attrs:{href:"https://etherscan.io/tx/"+e.row.txHash,target:"_blank"}},[t._v("\n          "+t._s(t.maskStr(e.row.txHash,5))+"\n        ")])]}}])})],1),t._v(" "),a("footer",[a("el-row",[a("el-col",{attrs:{span:16}},[a("el-progress",{attrs:{percentage:100*t.process}})],1),t._v(" "),t.isFinished?a("el-col",{staticClass:"text-warning",attrs:{span:8}},[t._v("下载完成报告")]):t._e()],1)],1)],1)},staticRenderFns:[]};var c=a("VU/8")(r,i,!1,function(t){a("hQoB")},null,null);e.default=c.exports},E1je:function(t,e){},KWnc:function(t,e){},"SwJ/":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=a("Dd8w"),n=a.n(o),s=a("NYxO"),l={components:{VueQr:a("X2mQ").a},props:{orderData:Object},data:function(){return{isRecharge:!1,info:{total:3e4,cost:1699999,type:0,itype:1,BCVnumber:5e3,ETHnumber:6e3},recData:{address:"0xsajaiaksoalksidujh8093jd84kd9s0a9djhvu87"},assets:{BVC:2e3,EHT:378,BTC:288},tokenProtocol:1,assetList:[],walletAddr:"",balanceLoad:!1,rechareLoad:!1,loading:!1}},mounted:function(){this.fetchBalance()},updated:function(){console.log("update")},computed:{tokenData:function(){var t=this,e={};return console.log("tokendata"),this.assetList.forEach(function(a){parseInt(a.tokenId)===parseInt(t.orderData.tokenId)&&(e=a)},this),e},ethData:function(){var t={};return console.log("ethdata"),this.assetList.forEach(function(e){"ETH"===e.symbol&&(t=e)}),console.log(t),t},needTokenAmount:function(){var t=this.orderData.totalAmount-this.tokenData.amount;return t>0?t:0},needEthAmount:function(){var t=(.0016*(this.orderData.totalCount+1)*Math.pow(10,18)-this.ethData.amount*Math.pow(10,18))/Math.pow(10,18);return t>0?t:0}},methods:n()({},Object(s.b)(["getDispenseBalance","getDispenseWallet","confirmDispense"]),{fetchBalance:function(){var t=this;this.balanceLoad=!0,this.getDispenseBalance({tokenProtocol:this.tokenProtocol}).then(function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};t.assetList=e.dataList,t.balanceLoad=!1})},handleChange:function(t){this.info.itype=0===t?1:0},handleRecharge:function(){var t=this;this.rechargeLoad=!0,this.getDispenseWallet({tokenProtocol:this.tokenProtocol}).then(function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};t.walletAddr=e.walletAddr,t.isRecharge=!0,t.rechargeLoad=!1})},handleConfirm:function(){var t=this;this.loading=!0,this.confirmDispense({}).then(function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};t.loading=!1,t.$emit("finished",{taskId:e.taskId})})},handleCopy:function(){this.$refs.copyInput.$el.firstElementChild.select(),document.execCommand("Copy"),this.$message.success("复制成功!")},handleRefresh:function(){this.fetchBalance()},handleFinished:function(){this.fetchBalance(),this.isRecharge=!1}})},r={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{directives:[{name:"loading",rawName:"v-loading",value:t.loading,expression:"loading"}],staticClass:"step2"},[t.isRecharge?a("div",[a("div",{directives:[{name:"loading",rawName:"v-loading",value:t.rechareLoad,expression:"rechareLoad"}],staticClass:"recharge-top"},[a("el-row",[a("el-col",{staticClass:"left",attrs:{span:18}},[a("h5",[t._v("充值地址")]),t._v(" "),a("div",[a("el-input",{ref:"copyInput",attrs:{readonly:""},model:{value:t.walletAddr,callback:function(e){t.walletAddr=e},expression:"walletAddr"}}),t._v(" "),a("i",{staticClass:"el-icon-document",on:{click:t.handleCopy}})],1),t._v(" "),a("p",[t._v("\n          提示："),a("span",[t._v("单笔充值不得低于0.003"+t._s(t.tokenData.symbol))]),t._v("，\n            我们不会处理少于该金额的充值要求。\n          ")])]),t._v(" "),a("el-col",{staticClass:"right",attrs:{span:6}},[a("vue-qr",{staticClass:"qrcode",attrs:{text:t.walletAddr,margin:10}}),t._v(" "),a("p",[t._v("或扫二维码立即充值")])],1)],1)],1),t._v(" "),a("div",{directives:[{name:"loading",rawName:"v-loading",value:t.balanceLoad,expression:"balanceLoad"}],staticClass:"recharge-bottom"},[a("h5",[t._v("\n        资产余额"),a("i",{staticClass:"el-icon-refresh",on:{click:t.handleRefresh}})]),t._v(" "),a("el-row",[a("el-col",{attrs:{span:12}},[a("span",[t._v(t._s(t.tokenData.amount))]),t._v(" "),a("small",[t._v(t._s(t.tokenData.symbol))])]),t._v(" "),a("el-col",{attrs:{span:12}},[a("span",[t._v(t._s(t.ethData.amount))]),t._v(" "),a("small",[t._v(t._s(t.ethData.symbol))])])],1),t._v(" "),a("el-row",[a("el-col",{attrs:{span:12}},[a("span",{staticStyle:{display:"block","font-size":"20px"}},[t._v("还需充值")]),t._v(" "),a("span",[t._v(t._s(t.needTokenAmount))]),t._v(" "),a("small",[t._v(t._s(t.tokenData.symbol))])]),t._v(" "),a("el-col",{attrs:{span:12}},[a("span",{staticStyle:{display:"block","font-size":"20px"}},[t._v("还需充值")]),t._v(" "),a("span",[t._v(t._s(t.needEthAmount))]),t._v(" "),a("small",[t._v(t._s(t.ethData.symbol))])])],1)],1),t._v(" "),a("footer",{staticClass:"recharge-footer"},[a("el-button",{staticClass:"btn-primary",attrs:{type:"warning"},on:{click:t.handleFinished}},[t._v("充值完成")])],1)]):a("div",{directives:[{name:"loading",rawName:"v-loading",value:t.balanceLoad,expression:"balanceLoad"}],staticClass:"confirm"},[a("h5",[t._v("确认发放")]),t._v(" "),a("div",{staticClass:"confirm-box"},[a("div",{staticClass:"confirm-content"},[a("div",{staticClass:"confirm-top"},[a("el-row",[a("el-col",{attrs:{span:12}},[a("h6",[t._v("发币总量")]),t._v(" "),a("p",[a("img",{attrs:{src:t.tokenData.logoUrl,alt:"logo"}}),t._v(" "),a("b",[t._v(t._s(t.orderData.totalAmount))]),t._v(" "),a("small",[t._v(t._s(t.tokenData.symbol))])])]),t._v(" "),a("el-col",{attrs:{span:12}},[a("h6",[t._v("手续费")]),t._v(" "),a("p",[a("img",{attrs:{src:t.ethData.logoUrl,alt:"logo"}}),t._v(" "),a("b",[t._v(t._s(.0016*(t.orderData.totalCount+1)))]),t._v(" "),a("small",[t._v("ETH")])])])],1)],1),t._v(" "),a("div",{staticClass:"confirm-line"}),t._v(" "),a("div",{staticClass:"confirm-bottom"},[a("el-row",[a("el-col",{attrs:{span:12}},[a("h6",[t._v("可用"+t._s(t.tokenData.symbol)+"余额")]),t._v(" "),a("p",[a("img",{attrs:{src:t.tokenData.logoUrl,alt:"logo"}}),t._v(" "),a("span",[t._v(t._s(t.tokenData.amount))]),t._v(" "),a("small",[t._v("枚")]),t._v(" "),a("br"),t._v(" "),t.tokenData.amount<t.orderData.totalAmount?a("i",[t._v("余额不足，请先充值")]):t._e()])]),t._v(" "),a("el-col",{attrs:{span:12}},[a("h6",[t._v("可用ETH余额")]),t._v(" "),a("p",[a("img",{attrs:{src:t.ethData.logoUrl,alt:"logo"}}),t._v(" "),a("span",[t._v(t._s(t.ethData.amount))]),t._v(" "),a("small",[t._v("\n                  枚\n                ")]),t._v(" "),a("br"),t._v(" "),t.ethData.amount<.0016*(t.orderData.totalCount+1)?a("i",[t._v("余额不足，请先充值")]):t._e()])])],1)],1)]),t._v(" "),a("footer",{staticClass:"confirm-footer"},[a("el-button",{attrs:{type:"warning",plain:""},on:{click:t.handleRecharge}},[t._v("去充值")]),t._v(" "),a("el-button",{staticClass:"btn-primary",attrs:{type:"warning"},on:{click:t.handleConfirm}},[t._v("确认发放")])],1)])])])},staticRenderFns:[]};var i=a("VU/8")(l,r,!1,function(t){a("/GXt")},null,null);e.default=i.exports},hQoB:function(t,e){},kZXB:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=a("Dd8w"),n=a.n(o),s=a("NYxO"),l={data:function(){return{formData:{isTest:0,tokenType:0,contractAddr:"",tokenId:0,tokenSymbol:""},formRule:{contractAddr:[{validator:function(t,e,a){/^0x[0-9a-fA-F]{40}$/.test(e)?a():a(new Error("请输入正确的ERC20合约地址！"))},trigger:"blur"}]},isUpload:!1,list:[],dataCount:0,uniqueCount:0,wrongCount:0,totalAmount:0,tokenType:0,uploadDisable:!0,tokenLoad:!1,selectLoad:!1,options:[{value:0,label:"ERC20"},{value:1,label:"ETH"}]}},watch:{"formData.tokenType":function(){var t=this;this.formData.tokenId=0,this.formData.tokenSymbol="",this.formData.contractAddr="",1===this.formData.tokenType&&(this.uploadDisable=!0,this.selectLoad=!0,this.getTokenBySymbol({tokenSymbol:"ETH"}).then(function(e){t.formData.tokenId=e.tokenId,t.formData.tokenSymbol=e.tokenSymbol,t.uploadDisable=!1,t.selectLoad=!1}))}},methods:n()({},Object(s.b)(["addUserDispenseAsset","getTokenInfo","getTokenBySymbol"]),{handleBefore:function(t){if(!this.formData.tokenId)return this.$message.warn("请填写合约地址"),!1},handleSuccess:function(t){0===t.errcode&&(this.isUpload=!0,this.list=t.data.dataList,this.dataCount=t.data.dataCount,this.totalAmount=t.data.totalAmount,this.wrongCount=t.data.wrongCount)},handleError:function(t){this.$message.error("上传失败，请重试")},fetchSample:function(){window.downloadFile("/static/file/sample.xls")},getToken:function(){var t=this;/^0x[0-9a-fA-F]{40}$/.test(this.formData.contractAddr)&&(this.uploadDisable=!0,this.tokenLoad=!0,this.getTokenInfo({contractAddr:this.formData.contractAddr}).then(function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};t.formData.tokenId=e.tokenId,t.formData.tokenSymbol=e.tokenSymbol,t.uploadDisable=!1,t.tokenLoad=!1}))},confirm:function(){var t=this;this.addUserDispenseAsset({tokenId:this.formData.tokenId}).then(function(){t.$emit("finished",{tokenId:t.formData.tokenId,totalAmount:t.totalAmount,totalCount:t.dataCount-t.wrongCount})})}})},r={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"step1"},[t.isUpload?a("div",[a("h4",[t._v("上传成功")]),t._v(" "),a("el-table",{staticStyle:{width:"100%"},attrs:{data:t.list,height:"400"}},[a("el-table-column",{attrs:{type:"index"}}),t._v(" "),a("el-table-column",{attrs:{prop:"address",width:"400",label:"地址"}}),t._v(" "),a("el-table-column",{attrs:{prop:"amount",label:"数量"}}),t._v(" "),a("el-table-column",{attrs:{prop:"status",label:"状态"}})],1),t._v(" "),a("footer",{staticClass:"footer"},[a("el-row",[a("el-col",{attrs:{span:5}},[a("h5",[t._v("总数量")]),t._v(" "),a("div",[t._v(t._s(t.totalAmount)),a("small",[t._v("枚")])])]),t._v(" "),a("el-col",{attrs:{span:5}},[a("h5",[t._v("总地址")]),t._v(" "),a("div",[t._v(t._s(t.dataCount)),a("small",[t._v("条")])])]),t._v(" "),a("el-col",{attrs:{span:5}},[a("h5",[t._v("错误数据")]),t._v(" "),a("div",[t._v(t._s(t.wrongCount)),a("small",[t._v("条")])])]),t._v(" "),a("el-col",{staticClass:"text-center",attrs:{span:9}},[a("el-button",{staticClass:"btn-primary",attrs:{type:"warning"},on:{click:t.confirm}},[t._v("确定")]),t._v(" "),a("el-upload",{staticClass:"upload-btn",attrs:{name:"addr",action:"/api/parseAddrFile",data:t.formData,"before-upload":t.handleBefore,"on-success":t.handleSuccess,"on-error":t.handleError,"show-file-list":!1}},[a("el-button",{staticClass:"text-primary",attrs:{type:"text"}},[t._v("重新上传")])],1)],1)],1)],1)],1):a("el-form",{ref:"form",staticClass:"form",attrs:{model:t.formData,rules:t.formRule,"label-width":"100px"}},[a("el-form-item",[a("el-radio-group",{model:{value:t.formData.isTest,callback:function(e){t.$set(t.formData,"isTest",e)},expression:"formData.isTest"}},[a("el-radio",{attrs:{label:0}},[t._v("正式发放")]),t._v(" "),a("el-radio",{attrs:{label:1}},[t._v("测试发放"),a("small",{staticStyle:{color:"#ccc"}},[t._v("（测试时仅向前两个地址发放代币）")])])],1)],1),t._v(" "),a("el-form-item",{staticClass:"inline-item select-item",attrs:{label:"发放类别"}},[a("el-select",{directives:[{name:"loading",rawName:"v-loading",value:t.selectLoad,expression:"selectLoad"}],attrs:{placeholder:"请选择"},model:{value:t.formData.tokenType,callback:function(e){t.$set(t.formData,"tokenType",e)},expression:"formData.tokenType"}},t._l(t.options,function(t){return a("el-option",{key:t.value,attrs:{label:t.label,value:t.value}})}))],1),t._v(" "),0===t.formData.tokenType?a("el-form-item",{staticClass:"inline-item input-item",attrs:{prop:"contractAddr"}},[0===t.tokenType?a("el-input",{staticClass:"step1-input",attrs:{placeholder:"请输入合约地址"},on:{blur:t.getToken},model:{value:t.formData.contractAddr,callback:function(e){t.$set(t.formData,"contractAddr",e)},expression:"formData.contractAddr"}},[a("i",{directives:[{name:"loading",rawName:"v-loading",value:t.tokenLoad,expression:"tokenLoad"}],attrs:{slot:"suffix"},slot:"suffix"},[t._v(t._s(t.formData.tokenSymbol))])]):t._e()],1):t._e(),t._v(" "),a("el-form-item",{attrs:{label:"上传地址"}},[a("el-tooltip",{staticClass:"item",attrs:{effect:"dark",content:"请输入合约地址",disabled:!t.uploadDisable,placement:"bottom"}},[a("el-upload",{staticClass:"upload-btn",attrs:{name:"addr",action:"/api/parseAddrFile","before-upload":t.handleBefore,"on-success":t.handleSuccess,"on-error":t.handleError,data:t.formData,disabled:t.uploadDisable,"show-file-list":!1}},[a("el-button",{staticClass:"btn-primary",attrs:{slot:"trigger",type:"warning"},slot:"trigger"},[t._v("点击上传")])],1)],1),t._v(" "),a("el-button",{attrs:{type:"warning",plain:""},on:{click:t.fetchSample}},[t._v("获取模板")])],1)],1)],1)},staticRenderFns:[]};var i=a("VU/8")(l,r,!1,function(t){a("KWnc")},null,null);e.default=i.exports},vjWk:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=a("Dd8w"),n=a.n(o),s=a("NYxO"),l=a("kZXB"),r=a("SwJ/"),i=a("3o7U"),c={components:{Step1:l.default,Step2:r.default,Step3:i.default},data:function(){return{active:0,orderData:{tokenId:0,totalAmount:0,totalCount:0},taskId:0}},computed:n()({},Object(s.d)({path:function(t){return t.route.path}})),methods:{finishStep1:function(t){this.orderData.tokenId=t.tokenId,this.orderData.totalAmount=t.totalAmount,this.orderData.totalCount=t.totalCount,this.active=1},finishStep2:function(t){this.taskId=t.taskId,this.active=2},handleFinished:function(t){this.active=t<2?t+1:0}}},d={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"acting-home"},[a("el-steps",{attrs:{active:t.active,"align-center":""}},[a("el-step",{attrs:{title:"上传"}}),t._v(" "),a("el-step",{attrs:{title:"确认"}}),t._v(" "),a("el-step",{attrs:{title:"发放"}})],1),t._v(" "),0===t.active?a("step1",{on:{finished:t.finishStep1}}):t._e(),t._v(" "),1===t.active?a("step2",{attrs:{orderData:t.orderData},on:{finished:t.finishStep2}}):t._e(),t._v(" "),2===t.active?a("step3",{attrs:{taskId:t.taskId},on:{finished:function(e){t.handleFinished(2)}}}):t._e()],1)},staticRenderFns:[]};var u=a("VU/8")(c,d,!1,function(t){a("E1je")},null,null);e.default=u.exports}});
//# sourceMappingURL=2.a7affae8609e0d615775.js.map