webpackJsonp([32],{SYRS:function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a={data:function(){return{projList:[]}},components:{ProjIntro:r("0VlW").default},mounted:function(){var t=this;this.$http.post("/api/getProjList",{pageno:1,perpage:6}).then(function(e){var r=e.data;0===r.errcode?t.projList=r.data.projList:alert(r.errmsg)})}},n={render:function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"proj-show-panel"},[e("h3",{staticClass:"panel-title"},[this._v("发现新公司")]),this._v(" "),e("div",{staticClass:"main-container"},this._l(this.projList,function(t){return e("router-link",{key:t.id,attrs:{to:{path:"projDetail/"+t.id}}},[e("proj-intro",{attrs:{"proj-data":t}})],1)}))])},staticRenderFns:[]};var i=r("VU/8")(a,n,!1,function(t){r("usy0")},"data-v-1d7a3497",null);e.default=i.exports},usy0:function(t,e){}});
//# sourceMappingURL=32.8918aa78fef93690e1a6.js.map