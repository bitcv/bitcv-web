webpackJsonp([33],{f812:function(t,e){},fOQt:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a={data:function(){return{newList:[]}},mounted:function(){var t=this;this.$http.post("api/getNewsList",{pageno:1,perpage:10}).then(function(e){var n=e.data;console.log(n),0===n.errcode&&(t.newList=n.data)})}},s={render:function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"new-area"},t._l(t.newList,function(e,a){return n("a",{key:a},[n("div",{staticClass:"new-table-panel"},[n("router-link",{attrs:{to:{path:"newsdetail/"+e.id}}},[n("img",{attrs:{src:e.bannerUrl,alt:""}}),t._v(" "),n("div",{staticClass:"content"},[n("span",{staticClass:"content-title"},[t._v(t._s(e.title))]),t._v(" "),n("span",{staticClass:"content-text",domProps:{innerHTML:t._s(e.content)}}),t._v(" "),n("span",{staticClass:"content-time"},[t._v(t._s(e.releaseTime))])])])],1)])}))},staticRenderFns:[]};var i=n("VU/8")(a,s,!1,function(t){n("f812")},"data-v-1b054db6",null);e.default=i.exports}});
//# sourceMappingURL=33.bee998a7ef99b4fd15b0.js.map