import{u as be,i as ke,r as _,a as he,d as b,o as Se,b as xe,c as p,e as n,f as l,w as s,g as Ce,_ as k,h as a,j as c,Q as Oe,k as B,l as i,m as N,n as y,p as Ve,q as Le,s as Te,F as Qe,t as Re,v as Ie,x as Fe,y as Ue,z as v,A as u,B as Ae,C as m,D as De,E as G,G as Ee,R as Be,H as qe,I as $e}from"./app.10579c01.js";import{Q as J,a as f}from"./QSelect.32be28f9.js";import{Q as q}from"./QBtnToggle.445cdb1e.js";import{Q as Pe,a as Me}from"./QTable.4d294d9e.js";import{Q as w}from"./QTd.f19052d1.js";import{Q as ze}from"./QTr.00779787.js";import{_ as We}from"./_plugin-vue_export-helper.cdc0426e.js";import"./QItemLabel.1f7b55df.js";import"./QSeparator.0566634d.js";import"./QMarkupTable.d2f16f87.js";import"./QLinearProgress.84b4c331.js";import"./QCheckbox.bf3f01de.js";import"./use-checkbox.45f82c4c.js";const Q=O=>(qe("data-v-758f378e"),O=O(),$e(),O),je={class:"q-ma-md"},He=Q(()=>c("div",{class:"row full-width flex-center q-pt-xs myRoundTop bg-primary"},[c("div",{class:"col-12 flex flex-center"},[c("span",{class:"text-h4"},"Operations")])],-1)),Ne={class:"row full-width q-pt-md justify-between"},Ge={class:"col-auto"},Je={class:"row q-gutter-sm q-pl-md"},Ke={class:"col-auto"},Xe={class:"row q-pr-md q-gutter-sm"},Ye=Q(()=>c("div",{class:"row"},[c("div",{class:"col"},[c("span",{class:"myFont"},"Webway")])],-1)),Ze={class:"row"},et={class:"col"},tt={key:1},at={key:0},lt={key:1},nt={key:0},ot={key:1},st={key:0},it={key:1},rt={class:"row items-baseline"},ct={key:0,class:"col-auto"},ut={class:"rainbow-2 pr-2"},dt={class:"col-auto"},mt=["src"],_t={key:0},pt={key:0,class:"text-blue"},ft={key:1,class:"text-red"},wt={key:2,class:""},gt={key:1},yt={key:1,class:"col-auto"},vt={class:"rainbow-2 pr-2"},bt={key:0},kt={key:1},ht={key:0},St={key:1},xt={key:0},Ct={key:0},Ot={key:1},Vt={key:1},Lt={key:0},Tt={key:1},Qt={key:0},Rt={key:0},It={key:1},Ft={key:2},Ut={key:0,class:"text-md-center text-green"},At=Q(()=>c("span",{class:"font-weight-bold"}," WON ",-1)),Dt={key:1,class:"text-md-center text-red"},Et=Q(()=>c("span",{class:"font-weight-bold"}," LOST ",-1)),Bt={class:"row no-gutters justify-start items-center"},qt={class:"col-5"},$t={key:0},Pt={class:"text-positive"},Mt={key:0},zt={key:1},Wt={class:"text-positive"},jt={key:0},Ht={__name:"SoloOperations",setup(O){Ce(e=>({"3dbe7fb5":ve.value}));let d=be(),R=ke("can"),h=_(0),K=he(),I=_("");const X=b(()=>k(()=>import("./SoloCampaignProgressLine.cccb3048.js"),["assets/SoloCampaignProgressLine.cccb3048.js","assets/app.10579c01.js","assets/app.4fce15f2.css","assets/QBadge.6984755e.js","assets/QLinearProgress.84b4c331.js"])),Y=b(()=>k(()=>import("./index.600cd088.js"),["assets/index.600cd088.js","assets/app.10579c01.js","assets/app.4fce15f2.css"])),Z=b(()=>k(()=>import("./SoloCampaignOperationChip.4c8638c3.js"),["assets/SoloCampaignOperationChip.4c8638c3.js","assets/app.10579c01.js","assets/app.4fce15f2.css","assets/QSelect.32be28f9.js","assets/QItemLabel.1f7b55df.js"])),ee=b(()=>k(()=>import("./CampaignMap.fe299919.js"),["assets/CampaignMap.fe299919.js","assets/app.10579c01.js","assets/app.4fce15f2.css","assets/QTooltip.8710fb11.js"])),te=b(()=>k(()=>import("./SoloCampaginWebWay.61ab8bd4.js"),["assets/SoloCampaginWebWay.61ab8bd4.js","assets/app.10579c01.js","assets/app.4fce15f2.css"])),ae=b(()=>k(()=>import("./NewCampaginPriorityButton.f3d14632.js"),["assets/NewCampaginPriorityButton.f3d14632.js","assets/app.10579c01.js","assets/app.4fce15f2.css"]));Se(async()=>{await d.getWebwayStartSystems(),await d.getSoloOperationList(),Echo.private("solooperation").listen("SoloOperationUpdate",e=>{e.flag.flag==1&&d.updateSoloOperationList(e.flag.message)})}),xe(async()=>{Echo.leave("solooperation")}),document.title="Evestuff - Operations";let $=()=>{},S=_(null),F=_(),le=_(!1),V=_(32458),x=_(null),L=_(1),U=p(()=>F.value?d.newSoloOperationsRegionList.filter(e=>e.text.toLowerCase().indexOf(F.value)>-1):d.newSoloOperationsRegionList),ne=(e,o,t)=>{o(()=>{F.value=e.toLowerCase(),U.value.length>0&&e&&(S.value=U.value[0])})},C=_(null),A=_(),D=p(()=>A.value?d.newSoloOperationsConstellationList.filter(e=>e.text.toLowerCase().indexOf(A.value)>-1):d.newSoloOperationsConstellationList),oe=(e,o,t)=>{o(()=>{A.value=e.toLowerCase(),D.value.length>0&&e&&(C.value=D.value[0])})},se=p(()=>h.value==0?"fa-regular fa-bell":"fa-solid fa-bell"),ie=()=>{},re=()=>{h.value==0?h.value=1:h.value=0},P=p(()=>V.value?d.newSoloOperations.filter(e=>e.campaign[0].event_type==V.value):d.newSoloOperations),M=p(()=>S.value?P.value.filter(e=>e.campaign[0].constellation.region_id==S.value.value):P.value),T=p(()=>C.value?M.value.filter(e=>e.campaign[0].constellation_id==C.value.value):M.value),z=p(()=>x.value?x.value==3?T.value.filter(e=>e.campaign[0].alliance.color==3):x.value==2?T.value.filter(e=>e.campaign[0].alliance.standing>0):T.value.filter(e=>e.campaign[0].alliance.standing<=0):T.value),E=p(()=>h.value==1?z.value.filter(e=>e.priority==1):z.value),ce=p(()=>L.value==1?E.value.filter(e=>e.campaign[0].status_id==5||e.campaign[0].status_id==1||e.campaign[0].status_id==2):L.value==2?E.value.filter(e=>e.campaign[0].status_id==5||e.campaign[0].status_id==2):E.value.filter(e=>e.campaign[0].status_id==3||e.campaign[0].status_id==4)),W=e=>e==32458?"Ihub":"TCU",j=e=>{if(e.campaign[0].system.webway.length>0){var o=e.campaign[0].system.webway,t=o.filter(g=>g.start_system_id==d.webwaySelectedStartSystem.value);if(t.length>0){var r=t[0].jumps;return r}else return null}else return null},ue=e=>{var o={value:e.value,text:e.text};le.value=!1,d.updateWebwaySelectedStartSystem(o)},H=e=>{if(e.campaign[0].system.webway.length>0){var o=e.campaign[0].system.webway,t=o.filter(g=>g.start_system_id==d.webwaySelectedStartSystem.value);if(t.length>0){var r=t[0].webway;return r}else return null}else return null},de=p(()=>!!(d.webwayStartSystems&&d.webwayStartSystems.length>0)),me=p(()=>{var e=d.webwayStartSystems,o={value:30004759,text:"1DQ1-A"};return e.push(o),e.sort(function(t,r){return t.value-r.value||t.text.localeCompare(r.text)}),e}),_e=e=>{var o=me.value.filter(t=>t.value!=e);return o},pe=e=>{if(e.priority==1)return"style-2"},fe=_({sortBy:"progress",descending:!1,page:1,rowsPerPage:0}),we=e=>{R("access_campaigns")&&(e.campaign[0].status_id==2||e.campaign[0].status_id==5)&&K.push({path:`/op/${e.link}`})},ge=_([{name:"webway",align:"center",required:!1,label:"Webway",classes:"text-no-wrap",field:e=>e.id,format:e=>`${e}`,sortable:!1},{name:"region",required:!0,align:"left",label:"Region",classes:"text-no-wrap",style:"width: 7%",field:e=>e.campaign[0].constellation.region.region_name,format:e=>`${e}`,sortable:!1,filter:!0},{name:"constellation",required:!0,align:"left",label:"Constellation",classes:"text-no-wrap",field:e=>e.campaign[0].constellation.constellation_name,format:e=>`${e}`,sortable:!1,filter:!0},{name:"system",align:"left",classes:"text-no-wrap",label:"System",field:e=>e.campaign[0].system.system_name,format:e=>`${e}`,sortable:!1,filter:!0},{name:"alliance",align:"left",required:!0,classes:"text-no-wrap",label:"Alliance",field:e=>e.campaign[0].alliance.name,format:e=>`${e}`,sortable:!1,filter:!0},{name:"ticker",align:"left",required:!0,label:"Ticker",style:"width: 5%",classes:"text-no-wrap",field:e=>e.campaign[0].alliance.ticker,format:e=>`${e}`,sortable:!1,filter:!0},{name:"adm",label:"ADM",classes:"text-no-wrap",field:e=>e.campaign[0].system.adm,format:e=>`${e}`,sortable:!1,filter:!0},{name:"structure",label:"Structure",classes:"text-no-wrap",sortable:!1,filter:!1,field:e=>e.campaign[0].event_type,format:e=>e==32458?"IHUB":"TCU"},{name:"progress",label:"Start/Progress",classes:"text-no-wrap",align:"center",style:"width: 25%",sortable:!1,field:e=>e.campaign[0].start_time,format:e=>`${e}`},{name:"count",label:"Countdown/Age",align:"center",classes:"text-no-wrap",sortable:!1,field:e=>e.campaign[0].system.adm,format:e=>`${e}`},{name:"actions",label:"",align:"right",classes:"text-no-wrap",sortable:!1,field:e=>e.id,format:e=>`${e}`}]),ye=e=>Date.parse(e),ve=p(()=>{let e=30;return d.size.height-e+"px"});return(e,o)=>(a(),n("div",je,[l(Pe,{title:"Connections",class:"myTableOperations myRound bg-webBack",rows:ce.value,columns:ge.value,"table-class":" text-webway","table-header-class":" text-weight-bolder","row-key":"id",dark:"",dense:"",filter:I.value,ref:"tableRef",rounded:"",pagination:fe.value},{top:s(t=>[He,c("div",Ne,[c("div",Ge,[c("div",Je,[l(Oe,{rounded:"",standout:"",dense:"",debounce:"300",modelValue:I.value,"onUpdate:modelValue":o[0]||(o[0]=r=>I.value=r),clearable:"",placeholder:"Search"},{append:s(()=>[l(B,{name:"fa-solid fa-magnifying-glass"})]),_:1},8,["modelValue"]),l(J,{rounded:"",dense:"",clearable:"",standout:"","input-debounce":"0","label-color":"webway","option-value":"value","option-label":"text",modelValue:S.value,"onUpdate:modelValue":o[1]||(o[1]=r=>S.value=r),options:U.value,ref:"toRegionRef",label:"Region",onFilter:i(ne),onFilterAbort:i($),"map-options":"","use-input":"","hide-selected":"","fill-input":""},null,8,["modelValue","options","onFilter","onFilterAbort"]),l(J,{rounded:"",clearable:"",dense:"",standout:"","input-debounce":"0","label-color":"webway","option-value":"value","option-label":"text",modelValue:C.value,"onUpdate:modelValue":o[2]||(o[2]=r=>C.value=r),options:D.value,ref:"toConstellationRef",label:"Constellations",onFilter:i(oe),onFilterAbort:i($),"map-options":"","use-input":"","hide-selected":"","fill-input":""},null,8,["modelValue","options","onFilter","onFilterAbort"]),l(N,{dense:"",flat:"",round:"",icon:se.value,onClick:o[3]||(o[3]=r=>i(re)())},null,8,["icon"])])]),c("div",Ke,[c("div",Xe,[l(q,{modelValue:V.value,"onUpdate:modelValue":o[4]||(o[4]=r=>V.value=r),rounded:"",unelevated:"",clearable:"",color:"webDark","text-color":"gray","toggle-color":"primary","toggle-text-color":"gray",options:[{label:"Ihub",value:32458},{label:"TCU",value:32226}]},null,8,["modelValue"]),l(q,{modelValue:x.value,"onUpdate:modelValue":o[5]||(o[5]=r=>x.value=r),rounded:"",unelevated:"",clearable:"",color:"webDark","text-color":"gray","toggle-color":"primary","toggle-text-color":"gray",options:[{label:"Goon",value:3},{label:"Friendly",value:2},{label:"Hostile",value:1}]},null,8,["modelValue"]),l(q,{modelValue:L.value,"onUpdate:modelValue":o[6]||(o[6]=r=>L.value=r),rounded:"",unelevated:"",color:"webDark","text-color":"gray","toggle-color":"primary","toggle-text-color":"gray",options:[{label:"Upcoming",value:1},{label:"Active",value:2},{label:"Finished",value:3}]},null,8,["modelValue"])])])])]),"header-cell-webway":s(t=>[l(Me,{props:t},{default:s(()=>[Ye,c("div",Ze,[c("div",et,[de.value?(a(),y(N,{key:0,color:"webChip",size:"sm",label:i(d).webwaySelectedStartSystem.text},{default:s(()=>[l(Ve,null,{default:s(()=>[l(Le,{class:"my-card"},{default:s(()=>[l(Te,{bordered:""},{default:s(()=>[(a(!0),n(Qe,null,Re(i(_e)(i(d).webwaySelectedStartSystem.value),(r,g)=>Ie((a(),y(Fe,{clickable:"",key:g,onClick:Nt=>i(ue)(r)},{default:s(()=>[l(Ue,null,{default:s(()=>[v(u(r.text),1)]),_:2},1024)]),_:2},1032,["onClick"])),[[Ee],[Be]])),128))]),_:1})]),_:1})]),_:1})]),_:1},8,["label"])):(a(),n("span",tt,u(i(d).webwaySelectedStartSystem.text),1))])])]),_:2},1032,["props"])]),body:s(t=>[l(ze,{props:t,class:Ae(i(pe)(t.row)),onClick:r=>i(we)(t.row)},{default:s(()=>[l(w,{key:"webway",props:t},{default:s(()=>[i(j)(t.row)&&i(H)(t.row)?(a(),y(i(te),{key:0,jumps:i(j)(t.row),web:i(H)(t.row)},null,8,["jumps","web"])):m("",!0)]),_:2},1032,["props"]),l(w,{key:"region",props:t},{default:s(()=>[t.row.priority==0?(a(),n("span",at,u(t.row.campaign[0].constellation.region.region_name),1)):(a(),n("span",lt,[l(f,{label:t.row.campaign[0].constellation.region.region_name},null,8,["label"])]))]),_:2},1032,["props"]),l(w,{key:"constellation",props:t},{default:s(()=>[t.row.priority==0?(a(),n("span",nt,u(t.row.campaign[0].constellation.constellation_name),1)):(a(),n("span",ot,[l(f,{size:"md",label:t.row.campaign[0].constellation.constellation_name},null,8,["label"])]))]),_:2},1032,["props"]),l(w,{key:"system",props:t},{default:s(()=>[t.row.priority==0?(a(),n("span",st,u(t.row.campaign[0].system.system_name),1)):(a(),n("span",it,[l(f,{label:t.row.campaign[0].system.system_name},null,8,["label"])]))]),_:2},1032,["props"]),l(w,{key:"alliance",props:t},{default:s(()=>[c("div",rt,[t.row.priority==1?(a(),n("div",ct,[c("span",ut,[l(B,{size:"xs",name:"fa-solid fa-wand-magic-sparkles fa-bounce"})])])):m("",!0),c("div",dt,[l(De,{size:"lg",class:"q-pr-xl"},{default:s(()=>[c("img",{src:t.row.campaign[0].alliance.url},null,8,mt)]),_:2},1024),t.row.priority==0?(a(),n("span",_t,[t.row.campaign[0].alliance.standing>0?(a(),n("span",pt,u(t.row.campaign[0].alliance.name),1)):t.row.campaign[0].alliance.standing<0?(a(),n("span",ft,u(t.row.campaign[0].alliance.name),1)):(a(),n("span",wt,u(t.row.campaign[0].alliance.name),1))])):(a(),n("span",gt,[t.row.campaign[0].alliance.standing>0?(a(),y(f,{key:0,color:"primary"},{default:s(()=>[v(u(t.row.campaign[0].alliance.name),1)]),_:2},1024)):t.row.campaign[0].alliance.standing<0?(a(),y(f,{key:1,color:"red","text-color":"white"},{default:s(()=>[v(u(t.row.campaign[0].alliance.name),1)]),_:2},1024)):(a(),y(f,{key:2},{default:s(()=>[v(u(t.row.campaign[0].alliance.name),1)]),_:2},1024))]))]),t.row.priority==1?(a(),n("div",yt,[c("span",vt,[l(B,{size:"xs",name:"fa-solid fa-wand-magic-sparkles fa-bounce"})])])):m("",!0)])]),_:2},1032,["props"]),l(w,{key:"ticker",props:t},{default:s(()=>[t.row.priority==0?(a(),n("span",bt,u(t.row.campaign[0].alliance.ticker),1)):(a(),n("span",kt,[l(f,{label:t.row.campaign[0].alliance.ticker},null,8,["label"])]))]),_:2},1032,["props"]),l(w,{key:"adm",props:t},{default:s(()=>[t.row.priority==0?(a(),n("span",ht,u(t.row.campaign[0].system.adm),1)):(a(),n("span",St,[l(f,{label:t.row.campaign[0].system.adm},null,8,["label"])]))]),_:2},1032,["props"]),l(w,{key:"structure",props:t},{default:s(()=>[t.row.priority==0?(a(),n("span",xt,[t.row.campaign[0].event_type==32458?(a(),n("span",Ct,"IHUB")):(a(),n("span",Ot,"TCU"))])):m("",!0),t.row.priority==1?(a(),n("span",Vt,[t.row.campaign[0].event_type==32458?(a(),n("span",Lt,[l(f,{label:"IHUB"})])):(a(),n("span",Tt,[l(f,{label:"TCU"})]))])):m("",!0)]),_:2},1032,["props"]),l(w,{key:"progress",props:t},{default:s(()=>[t.row.campaign[0].status_id==1||t.row.campaign[0].status_id==5?(a(),n("div",Qt,[t.row.priority==0?(a(),n("span",Rt,u(t.row.campaign[0].start_time),1)):(a(),n("span",It,[l(f,{label:t.row.campaign[0].start_time},null,8,["label"])]))])):t.row.campaign[0].status_id==2?(a(),y(i(X),{key:1,attackScore:t.row.campaign[0].attackers_score,defenderScore:t.row.campaign[0].defenders_score,attackScoreOld:t.row.campaign[0].attackers_score_old,defenderScoreOld:t.row.campaign[0].defenders_score_old},null,8,["attackScore","defenderScore","attackScoreOld","defenderScoreOld"])):t.row.campaign[0].status_id==3||t.row.campaign[0].status_id==4?(a(),n("span",Ft,[t.row.campaign[0].attackers_score==0?(a(),n("p",Ut,[v(u(t.row.campaign[0].alliance.name)+" ",1),At,v(" the "+u(i(W)(t.row.campaign[0].event_type))+" timer. ",1)])):(a(),n("p",Dt,[v(u(t.row.campaign[0].alliance.name)+" ",1),Et,v(" the "+u(i(W)(t.row.campaign[0].event_type))+" timer. ",1)]))])):m("",!0)]),_:2},1032,["props"]),l(w,{key:"count",props:t},{default:s(()=>[c("div",Bt,[l(i(Z),{row:t.row},null,8,["row"]),l(i(ee),{onClick:o[7]||(o[7]=G(()=>{},["stop"])),system_name:t.row.campaign[0].system.system_name,region_name:t.row.campaign[0].constellation.region.region_name},null,8,["system_name","region_name"]),c("div",qt,[t.row.campaign[0].structure!=null?(a(),y(i(Y),{key:0,class:"q-pl-sm","emit-events":!1,time:i(ye)(t.row.campaign[0].structure.age),interval:6e4},{default:s(({hours:r,days:g})=>[t.row.priority==0?(a(),n("span",$t,[c("span",Pt,[g!=0?(a(),n("span",Mt,u(g)+" Days - ",1)):m("",!0),v(" "+u(r)+" Hours ",1)])])):m("",!0),t.row.priority==1?(a(),n("span",zt,[c("span",Wt,[l(f,{class:"text-positive"},{default:s(()=>[g!=0?(a(),n("span",jt,u(g)+" Days - ",1)):m("",!0),v(" "+u(r)+" Hours ",1)]),_:2},1024)])])):m("",!0)]),_:2},1032,["time"])):m("",!0)])])]),_:2},1032,["props"]),i(R)("edit_hack_priority")?(a(),y(w,{key:"actions",props:t,onClick:o[8]||(o[8]=G(r=>i(ie)(),["stop"]))},{default:s(()=>[i(R)("edit_hack_priority")?(a(),y(i(ae),{key:0,row:t.row},null,8,["row"])):m("",!0)]),_:2},1032,["props"])):m("",!0)]),_:2},1032,["props","class","onClick"])]),_:1},8,["rows","columns","filter","pagination"])]))}},ia=We(Ht,[["__scopeId","data-v-758f378e"]]);export{ia as default};
