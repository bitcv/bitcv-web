webpackJsonp([32],{"8qCv":function(t,a){},"c+ZJ":function(t,a,s){"use strict";Object.defineProperty(a,"__esModule",{value:!0});var e=s("Dd8w"),i=s.n(e),n=s("NYxO"),l={components:{Pagination:s("/6x9").a},data:function(){return{loading:!1,lockTime:{value:0,items:[{label:"全部",value:0},{label:"1个月",value:1},{label:"3个月",value:3},{label:"6个月",value:6},{label:"12个月",value:12}]},total:0,currentPage:1,list:[]}},computed:{params:function(){return{pageno:this.currentPage,perpage:10,lockTime:this.lockTime.value}}},created:function(){this.fetch()},methods:i()({},Object(n.b)(["getCandyList"]),{fetch:function(){var t=this;this.loading=!0,this.getCandyList(this.params).then(function(){var a=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},s=a.dataCount,e=void 0===s?0:s,i=a.dataList,n=void 0===i?[]:i;t.total=e,t.list=n,t.list.map(function(a){t.$set(a,"isDetail",!1)}),t.loading=!1}).catch(function(){t.loading=!1})},getInterest:function(t,a,s){return parseInt(t*a*s/12*100)/100},onFilterClick:function(t){this.lockTime.value=t,this.currentPage=1,this.fetch()},handleShow:function(t){this.list.map(function(a,s){s!==t&&(a.isDetail=!1)}),this.list[t].isDetail=!this.list[t].isDetail},handleHidden:function(t){this.list[t].isDetail=!1},onPageClick:function(t){this.currentPage=t,this.fetch()}})},c={render:function(){var t=this,a=t.$createElement,s=t._self._c||a;return s("div",{directives:[{name:"loading",rawName:"v-loading",value:t.loading,expression:"loading"}]},[s("div",{staticClass:"panel panel-custom"},[s("div",{staticClass:"panel-body filter-list"},[t._m(0),t._v(" "),s("dl",{staticClass:"dl-horizontal"},[s("dt",[t._v("锁仓期限")]),t._v(" "),s("dd",t._l(t.lockTime.items,function(a){return s("a",{key:a.value,class:{active:t.lockTime.value==a.value},attrs:{href:"javascript:;"},on:{click:function(s){t.onFilterClick(a.value)}}},[t._v(t._s(a.label))])}))])])]),t._v(" "),s("div",{staticClass:"panel panel-custom"},[t.list.length?s("div",{staticClass:"table-responsive"},[s("table",{staticClass:"table hidden-xs"},[t._m(1),t._v(" "),s("tbody",t._l(t.list,function(a){return s("tr",{key:a.id},[s("td",[s("img",{staticClass:"img-rounded",attrs:{src:a.logoUrl,height:"30"}}),t._v(" "),s("span",[t._v(t._s(a.nameCn)),s("span",{staticClass:"text-gray small"},[t._v(t._s(a.tokenSymbol))])])]),t._v(" "),s("td",[s("span",{staticClass:"text-danger"},[t._v(t._s(t.getInterest(1e4,a.interestRate,a.lockTime))+"枚")])]),t._v(" "),s("td",[t._v(t._s(a.lockTime)+"个月")]),t._v(" "),s("td",[t._v(t._s(a.minAmount)+"枚")]),t._v(" "),s("td",[s("span",{staticClass:"text-primary"},[t._v(t._s(a.remainAmount)+"枚")])]),t._v(" "),s("td",[s("router-link",{staticClass:"btn btn-primary btn-sm btn-nocorner",attrs:{to:{path:"/candyRoom/candyBuy",query:a}}},[t._v("立即抢购")])],1)])}))]),t._v(" "),s("div",{staticClass:"visible-xs xs-box"},[t._m(2),t._v(" "),s("div",{staticClass:"xs-body"},t._l(t.list,function(a,e){return s("div",{key:a.id,staticClass:"xs-item"},[s("div",{staticClass:"xs-row",on:{click:function(a){t.handleShow(e)}}},[s("span",[s("img",{staticClass:"img-rounded",attrs:{src:a.logoUrl,height:"30"}}),t._v(" "),s("b",[t._v(t._s(a.nameCn)),s("i",{staticClass:"text-gray small"},[t._v(t._s(a.tokenSymbol))])])]),t._v(" "),s("span",[t._v(t._s(t.getInterest(1e4,a.interestRate,a.lockTime))+"枚")]),t._v(" "),s("span",[s("router-link",{staticClass:"btn btn-primary btn-sm btn-nocorner",attrs:{to:{path:"/candyRoom/candyBuy",query:a}}},[t._v("立即抢购")])],1)]),t._v(" "),a.isDetail?s("div",{staticClass:"xs-detail"},[s("p",[s("span",[t._v("锁仓期：")]),t._v("\n                "+t._s(a.lockTime)+"个月\n              ")]),t._v(" "),s("p",[s("span",[t._v("起始额度：")]),t._v("\n                "+t._s(a.minAmount)+"枚\n              ")]),t._v(" "),s("p",[s("span",[t._v("剩余额度：")]),t._v("\n                "+t._s(a.remainAmount)+"枚\n\n                "),s("b",{staticClass:"b-hidden",on:{click:function(a){t.handleHidden(e)}}},[t._v("收起")])])]):t._e()])}))])]):s("div",{staticClass:"nodat"},[t._v("暂无数据")])]),t._v(" "),s("div",{staticClass:"text-right"},[s("pagination",{attrs:{total:t.total,"current-page":t.currentPage},on:{onPageClick:t.onPageClick}})],1)])},staticRenderFns:[function(){var t=this.$createElement,a=this._self._c||t;return a("div",[a("h3",{staticStyle:{margin:"20px 0 30px"}},[this._v("余币宝计划")])])},function(){var t=this,a=t.$createElement,s=t._self._c||a;return s("thead",[s("tr",{staticClass:"text-dark"},[s("th",[t._v("项目")]),t._v(" "),s("th",[t._v("回报（每万枚）")]),t._v(" "),s("th",[t._v("锁仓期")]),t._v(" "),s("th",[t._v("起始额度")]),t._v(" "),s("th",[t._v("剩余额度")]),t._v(" "),s("th",{staticStyle:{width:"100px"}},[t._v("操作")])])])},function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"xs-header text-gray"},[a("span",[this._v("项目")]),this._v(" "),a("span",[this._v("回报（每万枚）")]),this._v(" "),a("span",[this._v("操作")])])}]};var o=s("VU/8")(l,c,!1,function(t){s("8qCv")},"data-v-290d9309",null);a.default=o.exports}});
//# sourceMappingURL=32.2c307a795747e1fbb30a.js.map