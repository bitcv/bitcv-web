webpackJsonp([27],{gDPf:function(t,s){},"tHq/":function(t,s,a){"use strict";Object.defineProperty(s,"__esModule",{value:!0});var i={data:function(){return{inputAmount:"",depositBoxData:{}}},mounted:function(){this.depositBoxData=this.$route.query,this.inputAmount=this.depositBoxData.minAmount},methods:{toDepositOrder:function(){if(parseInt(this.inputAmount)<parseInt(this.depositBoxData.minAmount))return alert("下单数量必须大于起始额度");this.depositBoxData.orderAmount=this.inputAmount,this.$router.push({path:"/candyRoom/candyOrder",query:this.depositBoxData})}}},e={render:function(){var t=this,s=t.$createElement,a=t._self._c||s;return a("div",{staticClass:"candy-buy"},[t._m(0),t._v(" "),a("div",{staticClass:"title-box",class:t.mediaClass()},[a("span",{staticClass:"title"},[t._v("剩余额度")]),t._v(" "),a("span",{staticClass:"text"},[t._v(t._s(t.depositBoxData.remainAmount))])]),t._v(" "),a("div",{staticClass:"content-area"},[a("div",{staticClass:"left-area",class:t.mediaClass()},[a("div",{staticClass:"row-box"},[a("span",{staticClass:"left-title"},[t._v("项目：")]),t._v(" "),a("div",{staticClass:"content-box"},[a("img",{attrs:{src:t.depositBoxData.logoUrl,alt:""}}),t._v(" "),a("div",{staticClass:"info-box"},[a("span",{staticClass:"title"},[t._v(t._s(t.depositBoxData.tokenSymbol))]),t._v(" "),a("span",{staticClass:"text"},[t._v(t._s(t.depositBoxData.nameCn))])])])]),t._v(" "),a("div",{staticClass:"row-box",class:t.mediaClass()},[a("span",{staticClass:"left-title"},[t._v("锁仓期：")]),t._v(" "),a("span",{staticClass:"content"},[t._v(t._s(t.depositBoxData.lockTime)+"个月")])]),t._v(" "),a("div",{staticClass:"row-box"},[a("span",{staticClass:"left-title"},[t._v("起始额度：")]),t._v(" "),a("span",{staticClass:"content"},[t._v(t._s(t.depositBoxData.minAmount))])])]),t._v(" "),a("div",{staticClass:"right-area",class:t.mediaClass()},[a("div",{staticClass:"top-row"},[a("div",{staticClass:"input-box"},[a("span",{staticClass:"title"},[t._v("充值数量")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.inputAmount,expression:"inputAmount"}],domProps:{value:t.inputAmount},on:{input:function(s){s.target.composing||(t.inputAmount=s.target.value)}}})]),t._v(" "),t._m(1),t._v(" "),a("div",{staticClass:"output-box"},[a("span",{staticClass:"title"},[t._v("余币宝回报")]),t._v(" "),a("span",{staticClass:"output"},[t._v(t._s(t.getInterest(t.inputAmount,t.depositBoxData.interestRate,t.depositBoxData.lockTime)))])])]),t._v(" "),a("div",{staticClass:"bottom-row"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.inputAmount,expression:"inputAmount"}],domProps:{value:t.inputAmount},on:{input:function(s){s.target.composing||(t.inputAmount=s.target.value)}}}),t._v(" "),a("span",{staticClass:"buy-btn",on:{click:t.toDepositOrder}},[t._v("去下单")])])])])])},staticRenderFns:[function(){var t=this.$createElement,s=this._self._c||t;return s("div",{staticClass:"header-nav"},[s("span",[this._v("余币宝")]),this._v("\n    >\n    "),s("span",[this._v("抢购额度")])])},function(){var t=this.$createElement,s=this._self._c||t;return s("div",{staticClass:"arrow-box"},[s("span",{staticClass:"arrow"},[this._v("->")])])}]};var n=a("VU/8")(i,e,!1,function(t){a("gDPf")},"data-v-37f0b0f8",null);s.default=n.exports}});
//# sourceMappingURL=27.d4d3e5d855af42e42027.js.map