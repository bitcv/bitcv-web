webpackJsonp([12],{"E+Fy":function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i={data:function(){return{mobile:"",passwd:"",vcode:"",checked:!0,timerId:"",disableSms:!1,countDown:60,selected:"+65",nations:{"+65":"新加坡 (+65)","+1":"美国/加拿大 (+1)","+61":"澳大利亚 (+61)","+81":"日本 (+81)","+82":"韩国 (+82)","+86":"中国 (+86)","+852":"香港 (+852)","+856":"老挝 (+856)","+886":"台湾 (+886)","+93":"阿富汗 (+93)","+355":"阿尔巴尼亚 (+355)","+213":"阿尔及利亚 (+213)","+684":"美属萨摩亚 (+684)","+376":"安道尔 (+376)","+244":"安哥拉 (+244)","+1264":"安圭拉 (+1264)","+672":"南极洲 (+672)","+1268":"安提瓜和巴布达 (+1268)","+54":"阿根廷 (+54)","+374":"亚美尼亚 (+374)","+297":"阿鲁巴 (+297)","+43":"奥地利 (+43)","+994":"阿塞拜疆 (+994)","+973":"巴林 (+973)","+880":"孟加拉国 (+880)","+1246":"巴巴多斯 (+1246)","+375":"白俄罗斯 (+375)","+32":"比利时 (+32)","+501":"伯利兹 (+501)","+229":"贝宁 (+229)","+1441":"百慕大 (+1441)","+975":"不丹 (+975)","+591":"玻利维亚 (+591)","+387":"波黑 (+387)","+267":"博茨瓦纳 (+267)","+55":"巴西 (+55)","+1284":"英属维尔京群岛 (+1284)","+673":"文莱 (+673)","+359":"保加利亚 (+359)","+226":"布基纳法索 (+226)","+95":"缅甸 (+95)","+257":"布隆迪 (+257)","+855":"柬埔寨 (+855)","+237":"喀麦隆 (+237)","+238":"佛得角 (+238)","+1345":"开曼群岛(+1345)","+236":"中非 (+236)","+235":"乍得 (+235)","+56":"智利 (+56)","+57":"哥伦比亚 (+57)","+269":"科摩罗 (+269)","+243":"刚果（金） (+243)","+242":"刚果（布） (+242)","+682":"库克群岛 (+682)","+506":"哥斯达黎加 (+506)","+225":"科特迪瓦 (+225)","+385":"克罗地亚 (+385)","+53":"古巴 (+53)","+357":"塞浦路斯 (+357)","+420":"捷克 (+420)","+45":"丹麦 (+45)","+253":"吉布提 (+253)","+1767":"多米尼克 (+1767)","+1809":"多米尼加 (+1809)","+593":"厄瓜多尔 (+593)","+20":"埃及 (+20)","+503":"萨尔瓦多 (+503)","+240":"赤道几内亚(+240)","+291":"厄立特里亚 (+291)","+251":"埃塞俄比亚 (+251)","+500":"福克兰群岛（马尔维纳斯） (+500)","+298":"法罗群岛 (+298)","+679":"斐济 (+679)","+":"芬兰Finland (+358)","+33":"法国France (+33)","+594":"法属圭亚那 (+594)","+689":"法属波利尼西亚 (+689)","+241":"加蓬 (+241)","+995":"格鲁吉亚 (+995)","+49":"德国 (+49)","+233":"加纳 (+233)","+350":"直布罗陀 (+350)","+30":"希腊 (+30)","+299":"格陵兰 (+299)","+1473":"格林纳达 (+1473)","+509":"瓜德罗普 (+590)","+1671":"关岛 (+1671)","+502":"危地马拉 (+502)","+1481":"根西岛 (+1481)","+224":"几内亚 (+224)","+245":"几内亚比绍 (+245)","+592":"圭亚那 (+592)","+379":"梵蒂冈Holy (+379)","+504":"洪都拉斯 (+504)","+36":"匈牙利 (+36)","+354":"冰岛 (+354)","+91":"印度 (+91)","+62":"印度尼西亚 (+62)","+98":"伊朗 (+98)","+964":"伊拉克 (+964)","+353":"爱尔兰 (+353)","+972":"以色列 (+972)","+39":"意大利 (+39)","+1876":"牙买加 (+1876)","+962":"约旦 (+962)","+73":"哈萨克斯坦 (+73)","+254":"肯尼亚 (+254)","+686":"基里巴斯 (+686)","+850":"朝鲜 (+850)","+965":"科威特 (+965)","+996":"吉尔吉斯斯坦 (+996)","+371":"拉脱维亚 (+371)","+961":"黎巴嫩 (+961)","+266":"莱索托 (+266)","+231":"利比里亚 (+231)","+218":"利比亚 (+218)","+423":"列支敦士登 (+423)","+370":"立陶宛 (+370)","+352":"卢森堡 (+352)","+853":"澳门 (+853)","+389":"前南马其顿 (+389)","+261":"马达加斯加 (+261)","+265":"马拉维 (+265)","+60":"马来西亚 (+60)","+960":"马尔代夫 (+960)","+223":"马里 (+223)","+356":"马耳他 (+356)","+692":"马绍尔群岛 (+692)","+596":"马提尼克 (+596)","+222":"毛里塔尼亚 (+222)","+230":"毛里求斯 (+230)","+52":"墨西哥 (+52)","+691":"密克罗尼西亚 (+691)","+373":"摩尔多瓦 (+373)","+377":"摩纳哥 (+377)","+976":"蒙古 (+976)","+1664":"蒙特塞拉特 (+1664)","+212":"摩洛哥(+212)","+258":"莫桑比克 (+258)","+264":"纳米尼亚 (+264)","+674":"瑙鲁 (+674)","+977":"尼泊尔 (+977)","+31":"荷兰 (+31)","+599":"荷属安的列斯 (+599)","+687":"新喀里多尼亚 (+687)","+64":"新西兰 (+64)","+505":"尼加拉瓜 (+505)","+227":"尼日尔 (+227)","+234":"尼日利亚 (+234)","+683":"纽埃 (+683)","+6723":"诺福克岛 (+6723)","+47":"挪威 (+47)","+968":"阿曼 (+968)","+92":"巴基斯坦 (+92)","+680":"帕劳 (+680)","+507":"巴拿马 (+507)","+675":"巴布亚新几内亚 (+675)","+595":"巴拉圭 (+595)","+51":"秘鲁 (+51)","+63":"菲律宾 (+63)","+48":"波兰 (+48)","+351":"葡萄牙 (+351)","+974":"卡塔尔 (+974)","+262":"留尼汪 (+262)","+40":"罗马尼亚 (+40)","+7":"俄罗斯 (+7)","+250":"卢旺达 (+250)","+290":"圣赫勒拿 (+290)","+1869":"圣基茨和尼维斯 (+1869)","+1758":"圣卢西亚 (+1758)","+508":"圣皮埃尔和密克隆 (+508)","+1784":"圣文森特和格林纳丁斯 (+1784)","+685":"萨摩亚 (+685)","+378":"圣马力诺 (+378)","+239":"圣多美和普林西比(+239)","+966":"沙特阿拉伯 (+966)","+221":"塞内加尔 (+221)","+381":"塞尔维亚和黑山 (+381)","+248":"塞舌尔 (+248)","+232":"塞拉利 (+232)","+421":"斯洛伐克 (+421)","+386":"斯洛文尼亚 (+386)","+677":"所罗门群岛 (+677)","+252":"索马里 (+252)","+27":"南非 (+27)","+34":"西班牙 (+34)","+94":"斯里兰卡 (+94)","+247":"苏丹 (+249)","+597":"苏里南 (+597)","+268":"斯威士兰 (+268)","+46":"瑞典 (+46)","+41":"瑞士 (+41)","+963":"叙利亚 (+963)","+992":"塔吉克斯坦 (+992)","+255":"坦桑尼亚 (+255)","+66":"泰国 (+66)","+1242":"巴哈马 (+1242)","+220":"冈比亚 (+220)","+228":"多哥 (+228)","+690":"托克劳 (+690)","+676":"汤加 (+676)","+1868":"特立尼达和多巴哥 (+1868)","+216":"突尼斯 (+216)","+90":"土耳其 (+90)","+993":"土库曼斯坦 (+993)","+1649":"特克斯和凯科斯群岛 (+1649)","+688":"图瓦卢 (+688)","+256":"乌干达 (+256)","+380":"乌克兰 (+380)","+971":"阿拉伯联合酋长国 (+971)","+44":"英国 (+44)","+598":"乌拉圭 (+598)","+998":"乌兹别克斯坦 (+998)","+678":"瓦努阿图 (+678)","+58":"委内瑞拉 (+58)","+84":"越南 (+84)"}}},methods:{signup:function(){var t=this;if(!this.selected)return alert("请选择手机国家号");var e=this.selected;return"+"===e.substr(0,1)&&(e=e.substr(1)),new RegExp(/^\d{7,11}$/).test(this.mobile)?this.passwd.length<6?alert("密码长度至少需要6位"):this.vcode?void this.$http.post("/api/signup",{mobile:this.mobile,passwd:this.passwd,vcode:this.vcode,nation:e}).then(function(e){var s=e.data;0===s.errcode?t.$router.push("/signin"):alert(s.errmsg)}).catch(function(t){console.log(t)}):alert("验证码不能为空"):alert("请填写正确的手机号")},getVcode:function(){var t=this;if(!new RegExp(/^\d{7,11}$/).test(this.mobile))return alert("请填写正确的手机号");this.disableSms=!0,this.timerId=setInterval(function(){t.countDown<=1?(clearInterval(t.timerId),t.disableSms=!1,t.timerId="",t.countDown=60):t.countDown--},1e3),this.$http.post("/api/getVcode",{mobile:this.mobile}).then(function(t){var e=t.data;0===e.errcode||alert(e.errmsg)}).catch(function(t){console.log(t)})},change:function(){this.checked=!this.checked}}},a={render:function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"container signup-container"},[s("div",{staticClass:"signup"},[s("h2",{staticClass:"panel-title center-title",staticStyle:{"text-align":"center","font-size":"23px","margin-bottom":"50px"}},[t._v("欢迎注册 BCV")]),t._v(" "),s("form",[[s("div",{staticClass:"row",staticStyle:{margin:"0"}},[s("div",{staticClass:"col-xs-4",staticStyle:{padding:"0"}},[s("el-select",{staticClass:"phone-suffix",attrs:{placeholder:"请选择"},model:{value:t.selected,callback:function(e){t.selected=e},expression:"selected"}},t._l(t.nations,function(e,i){return s("el-option",{key:i,attrs:{value:i}},[t._v(t._s(e))])}))],1),t._v(" "),s("div",{staticClass:"col-xs-8",staticStyle:{padding:"0"}},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.mobile,expression:"mobile"}],staticClass:"phone",staticStyle:{width:"100%"},attrs:{type:"text",placeholder:"请输入手机号"},domProps:{value:t.mobile},on:{input:function(e){e.target.composing||(t.mobile=e.target.value)}}})])])],t._v(" "),s("div",{staticClass:"smspanel"},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.vcode,expression:"vcode"}],staticClass:"sms-input",staticStyle:{"border-right-width":"0"},attrs:{type:"text",placeholder:"短信验证码"},domProps:{value:t.vcode},on:{input:function(e){e.target.composing||(t.vcode=e.target.value)}}}),t._v(" "),s("div",{staticClass:"floatboder"}),t._v(" "),s("el-button",{staticClass:"sms-btn",class:{disabled:t.disableSms},attrs:{disabled:t.disableSms,type:"primary"},on:{click:t.getVcode}},[t._v("获取验证码"),s("span",{directives:[{name:"show",rawName:"v-show",value:t.timerId,expression:"timerId"}]},[t._v(" ("+t._s(t.countDown)+"s)")])])],1),t._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:t.passwd,expression:"passwd"}],attrs:{type:"password",placeholder:"密码"},domProps:{value:t.passwd},on:{input:function(e){e.target.composing||(t.passwd=e.target.value)}}}),t._v(" "),s("button",{staticClass:"signup-btn",on:{click:function(e){e.preventDefault(),t.signup(e)}}},[t._v("注册")]),t._v(" "),t._m(0)],2)])])},staticRenderFns:[function(){var t=this.$createElement,e=this._self._c||t;return e("h5",{staticStyle:{"margin-top":"25px"}},[e("span",{staticStyle:{color:"#aaa"}},[this._v("已有账号?")]),e("a",{staticStyle:{cursor:"pointer"},attrs:{href:"/signin"}},[e("span",{staticStyle:{color:"#ff8b13"}},[this._v(" 去登录")])])])}]};var n=s("VU/8")(i,a,!1,function(t){s("YR0s")},null,null);e.default=n.exports},YR0s:function(t,e){}});
//# sourceMappingURL=12.0119af6bc58b4f4f8cc4.js.map