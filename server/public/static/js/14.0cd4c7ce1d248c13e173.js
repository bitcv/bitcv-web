webpackJsonp([14],{IiwL:function(t,r,s){"use strict";Object.defineProperty(r,"__esModule",{value:!0});var e={data:function(){return{bitcv:{},form:{number:""},numberError:""}},created:function(){this.fetch()},methods:{fetch:function(){this.bitcv=this.$route.query,this.form.number=this.bitcv.minAmount},handleSubmit:function(){this.numberError="",this.form.number<parseInt(this.bitcv.minAmount)?this.numberError="has-error":(this.bitcv.number=this.form.number,this.bitcv.report=this.getInterest(this.form.number,this.bitcv.interestRate,this.bitcv.lockTime),this.$router.push({path:"/candyRoom/candyOrder",query:this.bitcv}))}}},i={render:function(){var t=this,r=t.$createElement,s=t._self._c||r;return s("div",{staticClass:"container buying"},[s("ol",{staticClass:"breadcrumb"},[s("li",{staticClass:"active"},[s("router-link",{attrs:{to:{path:"/candyRoom/candyList"}}},[t._v(" "+t._s(t.$t("label.ybb")))])],1),t._v(" "),s("li",[t._v(t._s(t.$t("label.buy_c")))])]),t._v(" "),s("div",{staticClass:"row bitcv"},[s("div",{staticClass:"col-xs-3 col-md-3"},[s("img",{attrs:{src:t.bitcv.logoUrl,alt:"BitCV",height:"60"}}),t._v(" "),s("div",{staticClass:"clear"},[t._v(" ")]),t._v(" "),s("b",[t._v(t._s(t.bitcv.tokenSymbol)),s("span",[t._v(t._s(t.bitcv.nameCn))])])]),t._v(" "),s("div",{staticClass:"col-xs-3 col-md-3"},[s("b",[t._v(t._s(t.bitcv.remainAmount))]),t._v(" "),s("p",[t._v(t._s(t.$t("label.leave_amount")))])]),t._v(" "),s("div",{staticClass:"col-xs-3 col-md-3"},[s("b",[t._v(t._s(t.bitcv.minAmount))]),t._v(" "),s("p",[t._v(t._s(t.$t("label.start_amount")))])]),t._v(" "),s("div",{staticClass:"col-xs-3 col-md-3"},[s("b",[t._v(t._s(t.bitcv.lockTime)),s("span",[t._v(" "+t._s(t.$t("label.month")))])]),t._v(" "),s("p",[t._v(t._s(t.$t("label.lock")))])])]),t._v(" "),s("div",{staticClass:"content"},[s("div",{staticClass:"form-inline row buying-form"},[s("div",{staticClass:"form-group col-md-4",class:t.numberError},[s("label",{attrs:{for:"number"}},[t._v(t._s(t.$t("label.recharge_a"))+"？")]),t._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:t.form.number,expression:"form.number"}],staticClass:"form-control",attrs:{type:"number",id:"number",placeholder:t.$t("label.p_in"),min:"1"},domProps:{value:t.form.number},on:{input:function(r){r.target.composing||t.$set(t.form,"number",r.target.value)}}}),t._v(" "),t.numberError?s("span",[t._v(t._s(t.$t("label.bigger")))]):t._e()]),t._v(" "),s("div",{staticClass:"col-md-2"},[t._v("——>")]),t._v(" "),s("div",{staticClass:"form-group col-md-4"},[s("label",{attrs:{for:"report"}},[t._v(t._s(t.$t("label.ybb"))+t._s(t.$t("label.return")))]),t._v(" "),s("input",{staticClass:"form-control",attrs:{type:"number",id:"report",placeholder:t.$t("label.p_in"),min:"1",readonly:""},domProps:{value:t.getInterest(t.form.number,t.bitcv.interestRate,t.bitcv.lockTime)}})]),t._v(" "),s("div",{staticClass:"col-md-10 buying-form-submit"},[s("button",{staticClass:"btn btn-warning",on:{click:t.handleSubmit}},[t._v(t._s(t.$t("label.order_now")))])])])])])},staticRenderFns:[]};var a=s("VU/8")(e,i,!1,function(t){s("ytvZ")},"data-v-8d88d1d8",null);r.default=a.exports},ytvZ:function(t,r){}});
//# sourceMappingURL=14.0cd4c7ce1d248c13e173.js.map